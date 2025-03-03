<?php

namespace MoamalatPay;

use Exception;
use Illuminate\Support\Facades\Http;

class Refund
{
    /**
     * Response
     *
     * @var array
     */
    private $response;

    /**
     * Terminal ID
     *
     * @var string|int
     */
    private $terminal_id;

    /**
     * Merchant ID
     *
     * @var string|int
     */
    private $merchant_id;

    /**
     * Secure Key
     *
     * @var string
     */
    private $key;

    /**
     * Create a new refund instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->terminal_id = config('moamalat-pay.terminal_id');
        $this->merchant_id = config('moamalat-pay.merchant_id');
        $this->key = hex2bin(config('moamalat-pay.key'));
    }

    /**
     * Refund transaction
     *
     * @param  array  $extra
     * @return mixed
     */
    protected function refund($extra)
    {
        $DateTimeLocalTrxn = time();
        $encode_data = "DateTimeLocalTrxn={$DateTimeLocalTrxn}&MerchantId={$this->merchant_id}&TerminalId={$this->terminal_id}";

        if (config('moamalat-pay.production')) {
            $url = 'https://npg.moamalat.net/cube/paylink.svc/api/RefundTransaction';
        } else {
            $url = 'https://tnpg.moamalat.net/cube/paylink.svc/api/RefundTransaction';
        }

        $response = Http::post($url, array_merge([
            'TerminalId' => $this->terminal_id,
            'MerchantId' => $this->merchant_id,
            'DateTimeLocalTrxn' => $DateTimeLocalTrxn,
            'SecureHash' => hash_hmac('sha256', $encode_data, $this->key),
        ], $extra));

        if ($response->status() != 200 || $response['Success'] != true) {
            throw new Exception($response->offsetGet('Message'));
        }

        $this->response = $response->json();

        return $this;
    }

    /**
     * Refund transaction by system reference of transaction
     *
     * @param  string|int  $systemReference
     * @param  string|int  $amount
     * @return $this
     */
    public function refundBySystemReference($systemReference, $amount)
    {
        return $this->refund([
            'SystemReference' => $systemReference,
            'AmountTrxn' => $amount,
        ]);
    }

    /**
     * Refund transaction by network reference of transaction
     *
     * @param  string|int  $networkReference
     * @param  string|int  $amount
     * @return $this
     */
    public function refundByNetworkReference($networkReference, $amount)
    {
        return $this->refund([
            'NetworkReference' => $networkReference,
            'AmountTrxn' => $amount,
        ]);
    }

    /**
     * Get all properties of reponse
     *
     * @return array
     */
    public function getAll()
    {
        return $this->response;
    }

    /**
     * Get property of transaction
     *
     * @param  $property  key
     * @return mixed
     */
    public function get($property)
    {
        return $this->response[$property];
    }

    /**
     * Get property of reponse , if property not exists return default value
     *
     * @return mixed
     */
    public function getWithDefault($property, $default = null)
    {
        if (array_key_exists($property, $this->response)) {
            return $this->response[$property];
        }

        return $default;
    }

    /**
     * Get SystemReference of new refund transaction
     *
     * @return string|int
     */
    public function getRefNumber()
    {
        return $this->get('RefNumber');
    }
}
