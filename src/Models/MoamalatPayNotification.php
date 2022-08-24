<?php

namespace MoamalatPay\Models;

use Illuminate\Database\Eloquent\Model;

class MoamalatPayNotification extends Model
{

    public $table = 'moamalat_pay_notifications';

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
        'ip'
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
