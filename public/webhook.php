<?php

/**
 * PayOS Webhook Handler - Xử lý Webhook từ PayOS
 * 
 * Quy trình:
 * 1. Kiểm tra chữ ký HMAC_SHA256 (ksort + hash_hmac)
 * 2. Lấy mã đơn hàng từ nội dung chuyển khoản (Regex) hoặc orderCode
 * 3. Cập nhật payment_transactions và bookings bằng PDO Prepared Statements
 * 4. Ghi log chi tiết mọi bước
 * 5. Trả về {"error": 0, "message": "Ok"} để PayOS xác nhận
 */

declare(strict_types=1);

// Cấu hình
define('LOG_FILE', __DIR__ . '/webhook.log');

// Nhập DB class
require_once __DIR__ . '/../app/Core/DB.php';
use App\Core\DB;

// Set headers
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '0');

// ============================================================================
// HỆ THỐNG LOGGING
// ============================================================================

function writeLog(string $message, ?array $data = null): void {
    $timestamp = date('Y-m-d H:i:s');
    $logMsg = "[{$timestamp}] {$message}";
    if ($data !== null) {
        $logMsg .= "\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    $logMsg .= "\n";
    file_put_contents(LOG_FILE, $logMsg, FILE_APPEND);
}

// ============================================================================
// BƯỚC 1: LẤY DỮ LIỆU VÀ SIGNATURE TỪ REQUEST
// ============================================================================

writeLog('=== NEW WEBHOOK REQUEST ===');

$rawData = file_get_contents('php://input');

if (empty($rawData)) {
    writeLog('ERROR: Empty raw data');
    echo json_encode(["error" => 1, "message" => "Empty data"]);
    exit;
}

// BẮT BUỘC: Đặt tên biến là $requestData để Bước 2 và Bước 3 dùng được
$requestData = json_decode($rawData, true);
writeLog('Received data:', $requestData);

// THỦ THUẬT: Bắt chữ ký từ Body (vì Header đã bị Ngrok ăn chặn)
$signature = $_SERVER['HTTP_PAYOS_SIGNATURE'] 
          ?? $_SERVER['HTTP_X_PAYOS_SIGNATURE'] 
          ?? $requestData['signature'] 
          ?? '';

if (empty($signature)) {
    writeLog('ERROR: Không tìm thấy chữ ký ở cả Header lẫn Body JSON');
    echo json_encode(["error" => 1, "message" => "Missing signature"]);
    exit;
}

writeLog('Đã bắt được chữ ký thành công!');

// ============================================================================
// BƯỚC 2: KIỂM TRA CHỮ KÝ HMAC_SHA256
// ============================================================================

// Lấy config PayOS
$config = require __DIR__ . '/../config/app.php';
$checksumKey = $config['payos']['checksum_key'] ?? '';

if (empty($checksumKey)) {
    writeLog('ERROR: Checksum key not configured');
    echo json_encode(["error" => 1, "message" => "Config error"]);
    exit;
}

$dataArray = $requestData['data'] ?? [];

// Sắp xếp mảng theo thứ tự A-Z trước
ksort($dataArray);

$transaction_str_arr = [];
foreach ($dataArray as $key => $value) {
    // 1. PayOS yêu cầu: Bắt buộc chuyển các giá trị null/undefined thành chuỗi rỗng, không được xóa
    if ($value === null || $value === 'null' || $value === 'undefined') {
        $value = '';
    } 
    
    // 2. Xử lý nếu có mảng con (Webhook thường không có, nhưng để đảm bảo an toàn tuyệt đối)
    if (is_array($value)) {
        $valueSortedElementObj = array_map(function ($ele) {
            if (is_array($ele)) ksort($ele);
            return $ele;
        }, $value);
        $value = json_encode($valueSortedElementObj, JSON_UNESCAPED_UNICODE);
    }
    
    // 3. Ghép vào mảng
    $transaction_str_arr[] = $key . "=" . $value;
}

// Nối mảng bằng dấu &
$signatureString = implode("&", $transaction_str_arr);

writeLog("Signature string chuẩn PayOS: {$signatureString}");

// Tính toán signature
$computedSignature = hash_hmac('sha256', $signatureString, $checksumKey);

writeLog("Computed signature: {$computedSignature}");
writeLog("Received signature: {$signature}");

// So sánh signature
if (!hash_equals($computedSignature, $signature)) {
    writeLog('ERROR: Signature verification FAILED - UNAUTHORIZED');
    echo json_encode(["error" => 1, "message" => "Signature invalid"]);
    exit;
}

writeLog('✓ Signature verification passed');


// ====================================================================
// BƯỚC 3: LẤY MÃ ĐƠN HÀNG (Dành cho Checkout Link PayOS)
// ====================================================================

// Lấy thẳng mã đơn hàng từ cục data mà PayOS gửi về (Không cần dùng Regex nữa)
$orderCode = $dataArray['orderCode'] ?? null;

if (empty($orderCode)) {
    writeLog("Webhook Error: Không tìm thấy orderCode trong payload!");
    echo json_encode(["error" => 1, "message" => "Missing orderCode"]);
    exit;
}

// Ép kiểu về số nguyên để đảm bảo khớp hoàn toàn với Database
$orderCode = (int)$orderCode;
writeLog("Đã nhận Webhook cho Order Code: " . $orderCode);

// LƯU Ý QUAN TRỌNG: 
// Từ đây trở xuống, hãy chắc chắn câu lệnh SQL của bạn đang UPDATE 
// bảng bookings (hoặc payment_transactions) dựa trên đúng cột lưu orderCode này.

// Lấy các thông tin khác
$amount = (float)($dataArray['amount'] ?? 0);
$responseCode = $requestData['code'] ?? '';

writeLog("Extracted data - Order: {$orderCode}, Amount: {$amount}, Code: {$responseCode}");

// ============================================================================
// BƯỚC 4: KIỂM TRA RESPONSE CODE TỪ PAYOS
// ============================================================================

if ($responseCode !== '00') {
    writeLog("WARNING: PayOS response code {$responseCode} - payment not successful");
    echo json_encode(["error" => 0, "message" => "Ok"]);
    exit;
}

// ============================================================================
// BƯỚC 5: KẾT NỐI DATABASE VÀ CẬP NHẬT
// ============================================================================

try {
    $db = DB::pdo();
    $db->beginTransaction();
    
    writeLog("Starting database update for order: {$orderCode}");
    
    // === 5A: Kiểm tra payment_transactions tồn tại ===
        $checkStmt = $db->prepare("SELECT id, booking_id, status, payment_method FROM payment_transactions WHERE order_code = :order_code LIMIT 1");
    $checkStmt->execute([':order_code' => $orderCode]);
    $paymentRecord = $checkStmt->fetch(\PDO::FETCH_ASSOC);
    
    if (!$paymentRecord) {
        writeLog("ERROR: Order {$orderCode} not found in payment_transactions");
        echo json_encode(["error" => 1, "message" => "Order not found"]);
        exit;
    }
    
    writeLog("Found payment record:", $paymentRecord);
    
    $bookingId = (int)$paymentRecord['booking_id'];
    $currentStatus = $paymentRecord['status'] ?? '';
        $paymentMethod = (string)($paymentRecord['payment_method'] ?? '');
    
    // Nếu đã paid rồi, bỏ qua (idempotent)
    if ($currentStatus === 'paid') {
        writeLog("WARNING: Order {$orderCode} already marked as paid");
        echo json_encode(["error" => 0, "message" => "Ok"]);
        exit;
    }
    
    // === 5B: Cập nhật payment_transactions status = 'paid' ===
    $updatePayment = $db->prepare("
        UPDATE payment_transactions 
        SET status = 'paid', paid_at = NOW(), updated_at = NOW()
        WHERE order_code = :order_code
    ");
    
    $result1 = $updatePayment->execute([':order_code' => $orderCode]);
    $affectedRows1 = $updatePayment->rowCount();
    
    if (!$result1) {
        throw new Exception("Failed to update payment_transactions");
    }
    
    writeLog("✓ Updated payment_transactions: {$affectedRows1} row(s)");
    
    // === 5C: Cập nhật bookings status = 'paid' (hoặc 'confirmed') ===
        if ($paymentMethod === 'worker_payout') {
            // === 5C: Giao dịch trả lương worker -> cập nhật booking_payments ===
            $statusColumn = null;
            $updatedAtColumn = null;

            $columnStmt = $db->query('SHOW COLUMNS FROM booking_payments');
            $columns = $columnStmt ? ($columnStmt->fetchAll(\PDO::FETCH_ASSOC) ?: []) : [];

            foreach ($columns as $column) {
                $field = (string)($column['Field'] ?? '');
                if ($field === 'status' || $field === 'payment_status') {
                    $statusColumn = $field;
                }
                if ($field === 'updated_at') {
                    $updatedAtColumn = $field;
                }
            }

            if ($statusColumn !== null) {
                $sql = "UPDATE booking_payments SET `{$statusColumn}` = 'payout_paid'";
                if ($updatedAtColumn !== null) {
                    $sql .= ", `{$updatedAtColumn}` = NOW()";
                }
                $sql .= " WHERE booking_id = :booking_id";

                $updatePayroll = $db->prepare($sql);
                $result2 = $updatePayroll->execute([':booking_id' => $bookingId]);
                $affectedRows2 = $updatePayroll->rowCount();

                if (!$result2) {
                    throw new Exception('Failed to update booking_payments payout status');
                }

                writeLog("✓ Updated booking_payments payout (booking {$bookingId}): {$affectedRows2} row(s)");
            } else {
                writeLog("WARNING: booking_payments has no status/payment_status column for booking {$bookingId}");
            }
        } else {
            // === 5C: Thanh toán của khách -> xác nhận booking ===
            $updateBooking = $db->prepare("
                UPDATE bookings 
                SET status = 'confirmed', updated_at = NOW()
                WHERE id = :booking_id
            ");

            $result2 = $updateBooking->execute([':booking_id' => $bookingId]);
            $affectedRows2 = $updateBooking->rowCount();

            if (!$result2) {
                throw new Exception("Failed to update bookings");
            }

            writeLog("✓ Updated bookings (ID {$bookingId}): {$affectedRows2} row(s)");
        }
    
    // Commit transaction
    $db->commit();
    writeLog('✓ Transaction committed successfully');
    
} catch (\Exception $e) {
    // Rollback nếu có lỗi
    if ($db && $db->inTransaction()) {
        $db->rollBack();
    }
    writeLog('ERROR: Database operation failed', [
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
    ]);
    echo json_encode(["error" => 1, "message" => "Database error"]);
    exit;
}

// ============================================================================
// BƯỚC 6: TRẢ VỀ RESPONSE CHO PAYOS
// ============================================================================

writeLog('✓ Webhook processed successfully');
writeLog('=== END WEBHOOK PROCESSING ===');

echo json_encode(["error" => 0, "message" => "Ok"]);
exit;