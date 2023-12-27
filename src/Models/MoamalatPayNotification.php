<?php

namespace MoamalatPay\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MoamalatPayNotification
 *
 * @version Sep 17, 2022, 5:12 pm UTC
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
 * @property string $request
 * @property bool $verified
 * @property string $ip
 */
class MoamalatPayNotification extends Model
{
    public $fillable = [
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
    ];

    protected $casts = [
        'MerchantId' => 'string',
        'TerminalId' => 'string',
        'DateTimeLocalTrxn' => 'string',
        'TxnType' => 'string',
        'Message' => 'string',
        'PaidThrough' => 'string',
        'SystemReference' => 'string',
        'NetworkReference' => 'string',
        'MerchantReference' => 'string',
        'Amount' => 'string',
        'Currency' => 'string',
        'PayerAccount' => 'string',
        'PayerName' => 'string',
        'ActionCode' => 'string',
        'request' => 'string',
        'verified' => 'string',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('moamalat-pay.notification.table', parent::getTable());
    }

    /**
     * Scope a query to only include approved transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeApproved($query)
    {
        $query->where('ActionCode', '00');
    }

    /**
     * Scope a query to only include verified transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeVerified($query)
    {
        $query->where('verified', 1);
    }

    /**
     * Scope a query to only include transactions with current credentials terminal_id and mercahnt_id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeCurrentCredential($query)
    {
        $query
            ->where('MerchantId', config('moamalat-pay.merchant_id'))
            ->where('TerminalId', config('moamalat-pay.terminal_id'));
    }
}
