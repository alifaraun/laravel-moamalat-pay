<?php

namespace MoamalatPay\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use MoamalatPay\Http\Requests\GenerateSecureKeyRequest;

/**
 * Class ConfigController
 */
class ConfigController extends BaseController
{
    /**
     * Genearte SecureHash for use in payment transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSecureKey(GenerateSecureKeyRequest $request)
    {
        $TerminalId = config('moamalat-pay.terminal_id');
        $MerchantId = config('moamalat-pay.merchant_id');
        $amount = $request->amount;
        $merchantReference = $request->merchantReference;
        $key = hex2bin(config('moamalat-pay.key'));
        $DateTimeLocalTrxn = time();
        $encode_data = "Amount={$amount}&DateTimeLocalTrxn={$DateTimeLocalTrxn}&MerchantId={$MerchantId}&MerchantReference={$merchantReference}&TerminalId={$TerminalId}";

        return response()->json([
            'secureHash' => hash_hmac('sha256', $encode_data, $key),
            'DateTimeLocalTrxn' => $DateTimeLocalTrxn,
        ]);
    }
}
