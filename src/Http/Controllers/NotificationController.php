<?php

namespace MoamalatPay\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use MoamalatPay\Events\UnverfiedTransaction;
use MoamalatPay\Events\VerfiedTransaction;
use MoamalatPay\Events\ApprovedTransaction;
use MoamalatPay\Events\ApprovedSaleTransaction;
use MoamalatPay\Events\ApprovedRefundTransaction;
use MoamalatPay\Events\ApprovedVoidSaleTransaction;
use MoamalatPay\Events\ApprovedVoidRefundTransaction;
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
        $notification  = MoamalatPayNotification::create($data);

        $this->dispatchEvents($notification);

        return response()->json(["Message" => 'Success', 'Success' => true]);
    }

    protected function dispatchEvents(MoamalatPayNotification $notification)
    {
        if ($notification->verified) {
            VerfiedTransaction::dispatch($notification);
            if (/* $notification->Message == 'Approved' && */$notification->ActionCode === '00') { // aproved
                ApprovedTransaction::dispatch($notification);
                switch ($notification->TxnType) {
                    case '1':
                        ApprovedSaleTransaction::dispatch($notification);
                        break;
                    case '2':
                        ApprovedRefundTransaction::dispatch($notification);
                        break;
                    case '3':
                        ApprovedVoidSaleTransaction::dispatch($notification);
                        break;
                    case '4':
                        ApprovedVoidRefundTransaction::dispatch($notification);
                        break;
                }
            }
        } else {
            UnverfiedTransaction::dispatch($notification);
        }
    }

    /**
     * Validate if secure has correct to make sure notification is comming from Moamalat
     */
    protected function validateSecureHas($secureHash, $Amount, $Currency, $DateTimeLocalTrxn, $MerchantId, $TerminalId)
    {
        try {
            $encode_data = "Amount=$Amount&Currency=$Currency&DateTimeLocalTrxn=$DateTimeLocalTrxn&MerchantId=$MerchantId&TerminalId=$TerminalId";
            $key = pack("H*", config('moamalat-pay.notification.key'));
            return strtoupper(hash_hmac('sha256', $encode_data, $key)) === strtoupper($secureHash);
        } catch (Exception $e) {
            return false;
        }
    }
}
