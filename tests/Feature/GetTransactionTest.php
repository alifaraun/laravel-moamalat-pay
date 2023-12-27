<?php

namespace MoamalatPay\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use MoamalatPay\Tests\TestCase;
use MoamalatPay\Transaction;

class GetTransactionTest extends TestCase
{
    protected $transaction;

    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        // response of success requests
        $respone = [
            'Message' => null,
            'Success' => true,
            'TotalAmountAllTransaction' => 1000,
            'TotalAmountTipsTransaction' => 0,
            'TotalCountAllTransaction' => 1,
            'Transactions' => [
                [
                    'Date' => '03/09/2022',
                    'DateTotalAmount' => '1000',
                    'DateTransactions' => [
                        [
                            'Amnt' => '1000',
                            'AmountTrxn' => '1000',
                            'AuthCode' => null,
                            'CardNo' => '639499XXXXXX2740',
                            'CardType' => '',
                            'Currency' => 'LYD',
                            'ExternalTxnId' => null,
                            'FeeAmnt' => '0',
                            'HasToken' => true,
                            'ISForceSendCVCForRefund' => true,
                            'IsMustVoidTotalAmount' => false,
                            'IsPointTrasnaction' => false,
                            'IsRefund' => false,
                            'IsRefundEnabled' => true,
                            'IsSend' => false,
                            'MerchantReference' => '475217323',
                            'MobileNumber' => null,
                            'OriginalTxnId' => '',
                            'RRN' => '224601434990',
                            'ReceiptNo' => '224601434990',
                            'RefundButton' => 0,
                            'RefundReason' => '',
                            'RefundSource' => '',
                            'RefundUserCreator' => '',
                            'RelatedTxnTotalAmount' => null,
                            'RemainingRefundAmount' => '1000',
                            'ResCodeDesc' => 'Approved',
                            'STAN' => '434990',
                            'SenderName' => 'MS',
                            'Status' => 'Approved',
                            'TipAmnt' => '0',
                            'TransType' => 'Sale',
                            'TransactionChannel' => 'Card',
                            'TransactionId' => '1233317',
                            'TxnDateTime' => '03/09/22  01:44',
                            'TxnIcon' => 2,
                        ],
                    ],
                ],
            ],

        ];

        // set fake requests to make testing faster
        Http::fake([
            //'https://tnpg.moamalat.net/cube/paylink.svc/api/FilterTransactions' => Http::response($respone, 200),
            'https://tnpg.moamalat.net/cube/paylink.svc/api/FilterTransactions' => function ($r) use ($respone) {
                if ($r->offsetGet('MerchantId') == 'testing_authentication_failed') { // response for testing authentication failed
                    return Http::response([
                        'Message' => 'Authentication failed.',
                        'StackTrace' => null,
                        'ExceptionType' => 'System.InvalidOperationException',
                    ], 401);
                }
                if ($r->offsetGet('NetworkReference') == 'testing_not_found') { // response for testing transaction not found
                    return Http::response([
                        'Message' => 'Transaction not found',
                        'Success' => true,
                        'TotalAmountAllTransaction' => 0,
                        'TotalAmountTipsTransaction' => null,
                        'TotalCountAllTransaction' => 0,
                        'Transactions' => [],
                    ]);
                }

                return $respone; // response for testing success request
            },
        ]);

        $this->transaction = new Transaction('224601434990', '475217323');
    }

    /**
     * Test config.
     */
    public function test_transaction_not_found()
    {
        $this->expectExceptionMessage('Transaction not found');
        new Transaction('testing_not_found', '475217323');
    }

    /**
     * Test config.
     */
    public function test_authentication_failed()
    {
        $this->expectExceptionMessage('Authentication failed.');
        Config::set('moamalat-pay.merchant_id', 'testing_authentication_failed');
        new Transaction('224601434990', '475217323');
    }

    /**
     * Test config.
     */
    public function test_get_property()
    {
        $this->assertEquals('639499XXXXXX2740', $this->transaction->get('CardNo'));
        //$this->expectExceptionMessage('Undefined index: CardNotFound');
        //$this->assertEquals('639499XXXXXX2740', $this->transaction->get('CardNotFound'));
    }

    /**
     * Test config.
     */
    public function test_get_property_with_default_value()
    {
        $this->assertEquals('card-not-found', $this->transaction->getWithDefault('Card', 'card-not-found'));
    }

    /**
     * Test config.
     */
    public function test_check_approved()
    {
        $this->assertTrue($this->transaction->checkApproved());
        $this->assertTrue($this->transaction->checkApproved(1000));
        $this->assertTrue($this->transaction->checkApproved(1000, '639499XXXXXX2740'));
        $this->assertNotTrue($this->transaction->checkApproved(2000, '639499XXXXXX2740'));
    }

    /**
     * Test config.
     */
    public function test_get_all()
    {
        $keys = [
            'Amnt',
            'AmountTrxn',
            'AuthCode',
            'CardNo',
            'CardType',
            'Currency',
            'ExternalTxnId',
            'FeeAmnt',
            'HasToken',
            'ISForceSendCVCForRefund',
            'IsMustVoidTotalAmount',
            'IsPointTrasnaction',
            'IsRefund',
            'IsRefundEnabled',
            'IsSend',
            'MerchantReference',
            'MobileNumber',
            'OriginalTxnId',
            'RRN',
            'ReceiptNo',
            'RefundButton',
            'RefundReason',
            'RefundSource',
            'RefundUserCreator',
            'RelatedTxnTotalAmount',
            'RemainingRefundAmount',
            'ResCodeDesc',
            'STAN',
            'SenderName',
            'Status',
            'TipAmnt',
            'TransType',
            'TransactionChannel',
            'TransactionId',
            'TxnDateTime',
            'TxnIcon',
        ];
        $this->assertEquals($keys, array_keys($this->transaction->getAll()), 'Keys are not equal');
    }

    /**
     * Test config.
     */
    public function test_get_response()
    {
        $expected = [
            'Message' => null,
            'Success' => true,
            'TotalAmountAllTransaction' => 1000,
            'TotalAmountTipsTransaction' => 0,
            'TotalCountAllTransaction' => 1,
        ];
        $actual = $this->transaction->getResponse();
        unset($actual['Transactions']);
        $this->assertEquals($expected, $actual);
    }
}
