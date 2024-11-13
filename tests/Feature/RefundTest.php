<?php

namespace MoamalatPay\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use MoamalatPay\Refund;
use MoamalatPay\Tests\TestCase;

class RefundTest extends TestCase
{
    protected $transaction;

    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        // response of success requests
        $respone = [
            'Message' => 'Approved',
            'Success' => true,
            'ActionCode' => null,
            'AuthCode' => null,
            'DecimalFraction' => 3,
            'ExternalTxnId' => null,
            'IsEnableRefund' => false,
            'MerchantReference' => null,
            'NetworkReference' => null,
            'ReceiptNumber' => null,
            'ReceiverAccountNumber' => null,
            'ReceiverName' => null,
            'ReceiverScheme' => null,
            'RefNumber' => '1233674',
            'SystemReference' => 0,
            'SystemTxnId' => 0,
            'TxnDate' => null,
        ];

        // set fake requests to make testing faster
        Http::fake([
            //'https://tnpg.moamalat.net/cube/paylink.svc/api/FilterTransactions' => Http::response($respone, 200),
            'https://tnpg.moamalat.net/cube/paylink.svc/api/RefundTransaction' => function ($r) use ($respone) {
                if ($r->offsetGet('MerchantId') == 'testing_authentication_failed') { // response for testing authentication failed
                    return Http::response([
                        'Message' => 'Authentication failed.',
                        'StackTrace' => null,
                        'ExceptionType' => 'System.InvalidOperationException',
                    ], 401);
                }
                if ($r->offsetExists('NetworkReference') && $r->offsetGet('NetworkReference') == 'testing_already_refunded') { // response for testing authentication failed
                    return Http::response([
                        'Message' => 'CUBEEX5250616:Transaction Already Refunded',
                        'Success' => false,
                        'ActionCode' => null,
                        'AuthCode' => null,
                        'DecimalFraction' => 3,
                        'ExternalTxnId' => null,
                        'IsEnableRefund' => false,
                        'MerchantReference' => null,
                        'NetworkReference' => null,
                        'ReceiptNumber' => null,
                        'ReceiverAccountNumber' => null,
                        'ReceiverName' => null,
                        'ReceiverScheme' => null,
                        'RefNumber' => null,
                        'SystemReference' => 0,
                        'SystemTxnId' => 0,
                        'TxnDate' => null,
                    ], 200);
                }

                return $respone; // response for testing success request
            },
        ]);

        $this->transaction = app(Refund::class);
    }

    /**
     * Load transaction to use it in test something
     */
    protected function loadTransaction()
    {
        $this->transaction->refundByNetworkReference('226214209277', '10');
    }

    /**
     * Test config.
     */
    public function test_container_instance()
    {
        $this->assertInstanceOf(Refund::class, app('moamalat-pay-refund'));
    }

    /**
     * Test
     */
    public function test_refund_by_system_reference()
    {
        $this->transaction->refundBySystemReference('226214209277', '10');
        $this->assertEquals($this->transaction->get('Message'), 'Approved');
    }

    /**
     * Test
     */
    public function test_refund_by_network_reference()
    {
        $this->transaction->refundByNetworkReference('226214209277', '10');
        $this->assertEquals($this->transaction->get('Message'), 'Approved');
    }

    /**
     * Test
     */
    public function test_already_refunded()
    {
        $this->expectExceptionMessage('Transaction Already Refunded');
        (new Refund)->refundByNetworkReference('testing_already_refunded', '10');
    }

    /**
     * Test
     */
    public function test_authentication_failed()
    {
        $this->expectExceptionMessage('Authentication failed.');
        Config::set('moamalat-pay.merchant_id', 'testing_authentication_failed');
        (new Refund)->refundByNetworkReference('226214209277', '10');
    }

    /**
     * Test
     */
    public function test_get_refNumber()
    {
        $this->loadTransaction();
        $this->assertEquals('1233674', $this->transaction->getRefNumber());
    }

    /**
     * Test
     */
    public function test_get_property()
    {
        $this->loadTransaction();
        $this->assertEquals('Approved', $this->transaction->get('Message'));
        //$this->expectExceptionMessage('Undefined index: CardNotFound');
        //$this->assertEquals('Approved', $this->transaction->get('CardNotFound'));
    }

    /**
     * Test
     */
    public function test_get_property_with_default_value()
    {
        $this->loadTransaction();
        $this->assertEquals('card-not-found', $this->transaction->getWithDefault('Card', 'card-not-found'));
    }

    /**
     * Test
     */
    public function test_get_all()
    {
        $this->loadTransaction();
        $keys = [
            'Message',
            'Success',
            'ActionCode',
            'AuthCode',
            'DecimalFraction',
            'ExternalTxnId',
            'IsEnableRefund',
            'MerchantReference',
            'NetworkReference',
            'ReceiptNumber',
            'ReceiverAccountNumber',
            'ReceiverName',
            'ReceiverScheme',
            'RefNumber',
            'SystemReference',
            'SystemTxnId',
            'TxnDate',
        ];
        $this->assertEquals($keys, array_keys($this->transaction->getAll()), 'Keys are not equal');
    }
}
