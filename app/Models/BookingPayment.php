<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

final class BookingPayment
{
    private const TAX_RATE = 0.1;
    private const COMPANY_RATE = 0.15;
    private static ?array $paymentColumnsCache = null;

    public static function createIfNotExists(int $bookingId, int $customerId, int $workerId, float $servicePrice): void
    {
        if (self::byBookingId($bookingId) !== null) {
            return;
        }

        $tax = round($servicePrice * self::TAX_RATE, 2);
        $companyFee = round($servicePrice * self::COMPANY_RATE, 2);
        $workerSalary = round($servicePrice - $tax - $companyFee, 2);

        $servicePriceColumn = self::resolveColumn(['service_price', 'total_price', 'amount', 'total_amount', 'price']);
        $taxColumn = self::resolveColumn(['tax_amount', 'tax']);
        $companyFeeColumn = self::resolveColumn(['company_fee', 'platform_fee', 'commission_fee', 'company_commission']);
        $workerSalaryColumn = self::resolveColumn(['worker_salary', 'worker_amount', 'payout_amount']);
        $statusColumn = self::resolveColumn(['status', 'payment_status']);
        $createdAtColumn = self::resolveColumn(['created_at']);

        $columns = [];
        $values = [];
        $params = [];

        self::pushInsertPart($columns, $values, $params, 'booking_id', ':booking_id', $bookingId);
        self::pushInsertPart($columns, $values, $params, 'customer_id', ':customer_id', $customerId);
        self::pushInsertPart($columns, $values, $params, 'worker_id', ':worker_id', $workerId);

        if ($servicePriceColumn !== null) {
            self::pushInsertPart($columns, $values, $params, $servicePriceColumn, ':service_price', $servicePrice);
        }
        if ($taxColumn !== null) {
            self::pushInsertPart($columns, $values, $params, $taxColumn, ':tax_amount', $tax);
        }
        if ($companyFeeColumn !== null) {
            self::pushInsertPart($columns, $values, $params, $companyFeeColumn, ':company_fee', $companyFee);
        }
        if ($workerSalaryColumn !== null) {
            self::pushInsertPart($columns, $values, $params, $workerSalaryColumn, ':worker_salary', $workerSalary);
        }
        if ($statusColumn !== null) {
            self::pushInsertPart($columns, $values, $params, $statusColumn, ':status', 'ready');
        }
        if ($createdAtColumn !== null) {
            $columns[] = self::quoteIdentifier($createdAtColumn);
            $values[] = 'NOW()';
        }

        if (empty($columns)) {
            return;
        }

        $sql = 'INSERT INTO booking_payments (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';

        try {
            $stmt = DB::pdo()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $exception) {
            // Schema cũ có thể không tương thích hoàn toàn, bỏ qua để không chặn luồng chính.
        }
    }

    public static function byBookingId(int $bookingId): ?array
    {
        try {
            $stmt = DB::pdo()->prepare("SELECT * FROM booking_payments WHERE booking_id = :booking_id LIMIT 1");
            $stmt->execute(['booking_id' => $bookingId]);
            return $stmt->fetch() ?: null;
        } catch (PDOException $exception) {
            return null;
        }
    }

    public static function totals(): array
    {
        $servicePriceColumn = self::resolveColumn(['service_price', 'total_price', 'amount', 'total_amount', 'price']);
        $workerSalaryColumn = self::resolveColumn(['worker_salary', 'worker_amount', 'payout_amount']);
        $companyFeeColumn = self::resolveColumn(['company_fee', 'platform_fee', 'commission_fee', 'company_commission']);
        $taxColumn = self::resolveColumn(['tax_amount', 'tax']);

        $serviceExpr = $servicePriceColumn !== null ? 'COALESCE(SUM(' . self::quoteIdentifier($servicePriceColumn) . '), 0)' : '0';
        $workerExpr = $workerSalaryColumn !== null ? 'COALESCE(SUM(' . self::quoteIdentifier($workerSalaryColumn) . '), 0)' : '0';
        $companyExpr = $companyFeeColumn !== null ? 'COALESCE(SUM(' . self::quoteIdentifier($companyFeeColumn) . '), 0)' : '0';
        $taxExpr = $taxColumn !== null ? 'COALESCE(SUM(' . self::quoteIdentifier($taxColumn) . '), 0)' : '0';

        $sql = "SELECT
                {$serviceExpr} AS total_service_price,
                {$workerExpr} AS total_worker_salary,
                {$companyExpr} AS total_company_fee,
                {$taxExpr} AS total_tax
             FROM booking_payments";

        try {
            $stmt = DB::pdo()->query($sql);
            return $stmt->fetch() ?: self::defaultTotals();
        } catch (PDOException $exception) {
            return self::defaultTotals();
        }
    }

    /**
     * Admin nhập/cập nhật lương worker cho một booking.
     */
    public static function upsertWorkerSalary(
        int $bookingId,
        int $customerId,
        int $workerId,
        float $servicePrice,
        float $workerSalary,
        string $status = 'pending_payout'
    ): void {
        self::createIfNotExists($bookingId, $customerId, $workerId, $servicePrice);

        $workerSalaryColumn = self::resolveColumn(['worker_salary', 'worker_amount', 'payout_amount']);
        $statusColumn = self::resolveColumn(['status', 'payment_status']);
        $updatedAtColumn = self::resolveColumn(['updated_at']);

        $updates = [];
        $params = ['booking_id' => $bookingId];

        if ($workerSalaryColumn !== null) {
            $updates[] = self::quoteIdentifier($workerSalaryColumn) . ' = :worker_salary';
            $params['worker_salary'] = $workerSalary;
        }

        if ($statusColumn !== null) {
            $updates[] = self::quoteIdentifier($statusColumn) . ' = :status';
            $params['status'] = $status;
        }

        if ($updatedAtColumn !== null) {
            $updates[] = self::quoteIdentifier($updatedAtColumn) . ' = NOW()';
        }

        if (empty($updates)) {
            return;
        }

        $sql = 'UPDATE booking_payments SET ' . implode(', ', $updates) . ' WHERE booking_id = :booking_id';

        try {
            $stmt = DB::pdo()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $exception) {
            // Không chặn luồng chính nếu schema chưa đồng bộ.
        }
    }

    /**
     * Cập nhật trạng thái chi lương worker của booking.
     */
    public static function updateWorkerPayoutStatus(int $bookingId, string $status): void
    {
        $statusColumn = self::resolveColumn(['status', 'payment_status']);
        $updatedAtColumn = self::resolveColumn(['updated_at']);

        if ($statusColumn === null) {
            return;
        }

        $sql = 'UPDATE booking_payments SET ' . self::quoteIdentifier($statusColumn) . ' = :status';
        if ($updatedAtColumn !== null) {
            $sql .= ', ' . self::quoteIdentifier($updatedAtColumn) . ' = NOW()';
        }
        $sql .= ' WHERE booking_id = :booking_id';

        try {
            $stmt = DB::pdo()->prepare($sql);
            $stmt->execute([
                'status' => $status,
                'booking_id' => $bookingId,
            ]);
        } catch (PDOException $exception) {
            // Không chặn luồng chính nếu schema chưa đồng bộ.
        }
    }

    /**
     * Tổng hợp lương worker để theo dõi trả lương theo luồng mới.
     */
    public static function workerPayoutTotals(): array
    {
        $workerSalaryColumn = self::resolveColumn(['worker_salary', 'worker_amount', 'payout_amount']);
        $statusColumn = self::resolveColumn(['status', 'payment_status']);

        if ($workerSalaryColumn === null) {
            return [
                'total_salary_entered' => 0.0,
                'total_salary_paid' => 0.0,
                'total_salary_pending' => 0.0,
            ];
        }

        $salaryExpr = self::quoteIdentifier($workerSalaryColumn);
        $paidCase = $statusColumn !== null
            ? "CASE WHEN LOWER(" . self::quoteIdentifier($statusColumn) . ") = 'payout_paid' THEN {$salaryExpr} ELSE 0 END"
            : '0';
        $pendingCase = $statusColumn !== null
            ? "CASE WHEN LOWER(" . self::quoteIdentifier($statusColumn) . ") IN ('pending_payout', 'payout_processing', 'ready') THEN {$salaryExpr} ELSE 0 END"
            : '0';

        $sql = "SELECT
                    COALESCE(SUM({$salaryExpr}), 0) AS total_salary_entered,
                    COALESCE(SUM({$paidCase}), 0) AS total_salary_paid,
                    COALESCE(SUM({$pendingCase}), 0) AS total_salary_pending
                FROM booking_payments";

        try {
            $stmt = DB::pdo()->query($sql);
            $result = $stmt->fetch() ?: [];
            return [
                'total_salary_entered' => (float)($result['total_salary_entered'] ?? 0),
                'total_salary_paid' => (float)($result['total_salary_paid'] ?? 0),
                'total_salary_pending' => (float)($result['total_salary_pending'] ?? 0),
            ];
        } catch (PDOException $exception) {
            return [
                'total_salary_entered' => 0.0,
                'total_salary_paid' => 0.0,
                'total_salary_pending' => 0.0,
            ];
        }
    }

    private static function getPaymentColumns(): array
    {
        if (self::$paymentColumnsCache !== null) {
            return self::$paymentColumnsCache;
        }

        try {
            $stmt = DB::pdo()->query('SHOW COLUMNS FROM booking_payments');
            $rows = $stmt->fetchAll() ?: [];
            self::$paymentColumnsCache = array_values(array_map(
                static fn(array $row): string => (string)($row['Field'] ?? ''),
                $rows
            ));
        } catch (PDOException $exception) {
            self::$paymentColumnsCache = [];
        }

        return self::$paymentColumnsCache;
    }

    private static function resolveColumn(array $candidates): ?string
    {
        $columns = self::getPaymentColumns();
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }
        return null;
    }

    private static function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    private static function pushInsertPart(array &$columns, array &$values, array &$params, string $columnName, string $paramName, mixed $value): void
    {
        if (self::resolveColumn([$columnName]) === null) {
            return;
        }

        $columns[] = self::quoteIdentifier($columnName);
        $values[] = $paramName;
        $params[ltrim($paramName, ':')] = $value;
    }

    private static function defaultTotals(): array
    {
        return [
            'total_service_price' => 0,
            'total_worker_salary' => 0,
            'total_company_fee' => 0,
            'total_tax' => 0,
        ];
    }
}
