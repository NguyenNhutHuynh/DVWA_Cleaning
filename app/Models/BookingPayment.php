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
