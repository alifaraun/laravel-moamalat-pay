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
}
