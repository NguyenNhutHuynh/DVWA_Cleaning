<?php

/**
 * API Kiểm tra Trạng thái Thanh toán
 * 
 * URL: GET /check_status.php?booking_id=123 hoặc GET /check_status.php?order_code=DVWA_...
 * 
 * Response: {"status": "pending"} hoặc {"status": "paid"}
 */

declare(strict_types=1);

// Nhập DB class
require_once __DIR__ . '/../app/Core/DB.php';
use App\Core\DB;

// Set header - TRƯỚC KHI echo bất kỳ cái gì
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', '0');

// ============================================================================
// HỆ THỐNG TRẢ VỀ JSON
// ============================================================================

function returnJson(string $status): void {
    // Không có khoảng trắng thừa, không có HTML
    echo json_encode(["status" => $status], JSON_UNESCAPED_UNICODE);
    exit;
}

function returnError(string $message): void {
    http_response_code(400);
    echo json_encode(["error" => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

// ============================================================================
// KIỂM TRA REQUEST METHOD
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    returnError('Method not allowed');
}

// ============================================================================
// LẤY PARAMETERS
// ============================================================================

$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
$orderCode = isset($_GET['order_code']) ? trim((string)$_GET['order_code']) : '';

// Phải có ít nhất một trong hai
if ($bookingId <= 0 && empty($orderCode)) {
    returnError('Missing booking_id or order_code');
}

// ============================================================================
// TRUY VẤN DATABASE
// ============================================================================

try {
    $db = DB::pdo();
    $status = 'pending'; // Mặc định
    
    if (!empty($orderCode)) {
        // Tìm theo order_code
        $stmt = $db->prepare("
            SELECT status FROM payment_transactions 
            WHERE order_code = :order_code 
            LIMIT 1
        ");
        $stmt->execute([':order_code' => $orderCode]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($row) {
            $status = $row['status'] ?? 'pending';
        }
    } elseif ($bookingId > 0) {
        // Tìm theo booking_id
        $stmt = $db->prepare("
            SELECT status FROM payment_transactions 
            WHERE booking_id = :booking_id 
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->execute([':booking_id' => $bookingId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($row) {
            $status = $row['status'] ?? 'pending';
        }
    }
    
    returnJson($status);
    
} catch (\Exception $e) {
    error_log('check_status.php error: ' . $e->getMessage());
    http_response_code(500);
    returnError('Database error');
}