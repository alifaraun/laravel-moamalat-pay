<?php

namespace MoamalatPay\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use MoamalatPay\Factories\MoamalatPayNotificationFactory;

/**
 * Class MoamalatPayNotification
 *
 * @property int $id
 * @property string $MerchantId
 * @property string $TerminalId
 * @property string $DateTimeLocalTrxn
 * @property string $TxnType
 * @property string $Message
 * @property string $PaidThrough
 * @property string $SystemReference
 * @property string $NetworkReference
 * @property string $MerchantReference
 * @property string $Amount
 * @property string $Currency
 * @property string $PayerAccount
 * @property string $PayerName
 * @property string $ActionCode
 * @property array $request
 * @property bool $verified
 * @property string|null $ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'MerchantId',
    'TerminalId',
    'DateTimeLocalTrxn',
    'TxnType',
    'Message',
    'PaidThrough',
    'SystemReference',
    'NetworkReference',
    'MerchantReference',
    'Amount',
    'Currency',
    'PayerAccount',
    'PayerName',
    'ActionCode',
    'request',
    'verified',
    'ip',
])]
class MoamalatPayNotification extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): MoamalatPayNotificationFactory
    {
        return MoamalatPayNotificationFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'request' => 'array',
            'verified' => 'boolean',
        ];
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('moamalat-pay.notification.table', parent::getTable());
    }

    /**
     * Scope a query to only include approved transactions.
     */
    #[Scope]
    protected function approved(Builder $query): void
    {
        $query->where('ActionCode', '00');
    }

    /**
     * Scope a query to only include verified transactions.
     */
    #[Scope]
    protected function verified(Builder $query): void
    {
        $query->where('verified', 1);
    }

    /**
     * Scope a query to only include transactions with current credentials terminal_id and merchant_id.
     */
    #[Scope]
    protected function currentCredential(Builder $query): void
    {
        $query
            ->where('MerchantId', config('moamalat-pay.merchant_id'))
            ->where('TerminalId', config('moamalat-pay.terminal_id'));
    }
}
