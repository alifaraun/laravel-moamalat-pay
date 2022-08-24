<?php

namespace MoamalatPay\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use MoamalatPay\Models\MoamalatPayNotification;

/**
 * Class NotificationController
 */

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'MerchantId' => 'nullable',
            'TerminalId' => 'nullable',
            'DateTimeLocalTrxn' => 'nullable',
            'TxnType' => 'nullable',
            'Message' => 'nullable',
            'PaidThrough' => 'nullable',
            'SystemReference' => 'nullable',
            'NetworkReference' => 'nullable',
            'MerchantReference' => 'nullable',
            'Amount' => 'nullable',
            'Currency' => 'nullable',
            'PayerAccount' => 'nullable',
            'PayerName' => 'nullable',
            'ActionCode' => 'nullable',
        ]);


        $data['ip'] = $request->ip();
        $data['request'] = json_encode($request->all());
        $data['verified'] = $this->validateSecureHas($request->input(['SecureHash']), $data['Amount'], $data['Currency'], $data['DateTimeLocalTrxn'], $data['MerchantId'], $data['TerminalId']);
        MoamalatPayNotification::create($data);

        return response()->json(["Message" => 'Success', 'Success' => true]);
    }

    /**
     * Validate if secure has correct to make sure notification is comming from Moamalat
     */
    private function validateSecureHas($secureHash, $Amount, $Currency, $DateTimeLocalTrxn, $MerchantId, $TerminalId)
    {
        try {
            $encode_data = "Amount=$Amount&Currency=$Currency&DateTimeLocalTrxn=$DateTimeLocalTrxn&MerchantId=$MerchantId&TerminalId=$TerminalId";
            $key = pack("H*", config('moamalat-pay.notification_key'));
            return strtoupper(hash_hmac('sha256', $encode_data, $key)) === strtoupper($secureHash);
        } catch (Exception $e) {
            return false;
        }
    }
}
