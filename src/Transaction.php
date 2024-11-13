<?php

namespace MoamalatPay;

use Exception;
use Illuminate\Support\Facades\Http;

class Transaction
{
    /**
     * Response
     *
     * @var \Illuminate\Http\Client\Response
     */
    private $response;

    /**
     * Transaction properties
     *
     * @var array
     */
    private $data;

    /**
     * Create a new transaction instance.
     *
     * @param  string  $networkReference
     * @param  string  $merchantReference
     * @return void
     */
    public function __construct($networkReference, $merchantReference)
    {
        $TerminalId = config('moamalat-pay.terminal_id');
        $MerchantId = config('moamalat-pay.merchant_id');

        if (config('moamalat-pay.key_hex')) {
            $key = hex2bin(config('moamalat-pay.key'));
        } else {
            $key = pack('H*', config('moamalat-pay.key'));
        }

        $DateTimeLocalTrxn = time();
        $encode_data = "DateTimeLocalTrxn={$DateTimeLocalTrxn}&MerchantId={$MerchantId}&TerminalId={$TerminalId}";

        if (config('moamalat-pay.production')) {
            $url = 'https://npg.moamalat.net/cube/paylink.svc/api/FilterTransactions';
        } else {
            $url = 'https://tnpg.moamalat.net/cube/paylink.svc/api/FilterTransactions';
        }

        $response = Http::post($url, [
            'NetworkReference' => $networkReference,
            'MerchantReference' => $merchantReference,
            'TerminalId' => $TerminalId,
            'MerchantId' => $MerchantId,
            'DisplayLength' => 1,
            'DisplayStart' => 0,
            'DateTimeLocalTrxn' => $DateTimeLocalTrxn,
            'SecureHash' => hash_hmac('sha256', $encode_data, $key),
        ]);

        if ($response->status() != 200 || $response['TotalCountAllTransaction'] != 1) {
            throw new Exception($response->offsetGet('Message'));
        }

        $this->response = $response->json();
        $this->data = $this->response['Transactions'][0]['DateTransactions'][0];
    }

    /**
     * Get all properties of transaction
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Get property of transaction
     *
     * @param  $property  key
     * @return mixed
     */
    public function get($property)
    {
        return $this->data[$property];
    }

    /**
     * Get property of reponse , if property not exists return default value
     *
     * @return mixed
     */
    public function getWithDefault($property, $default = null)
    {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }

        return $default;
    }

    /**
     * Get all properties of reponse
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Check status of transaction is Approved
     *
     * @return bool
     */
    public function checkApproved($amount = null, $card = null)
    {
        $result = true;
        if ($amount != null) {
            $result = /* $result && */ $this->data['AmountTrxn'] == $amount;
        }
        if ($card != null) {
            $result = $result && $this->data['CardNo'] == $card;
        }

        return $result && $this->data['Status'] == 'Approved';
    }
}
