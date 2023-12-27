<?php

namespace MoamalatPay\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use MoamalatPay\Events\ApprovedRefundTransaction;
use MoamalatPay\Events\ApprovedSaleTransaction;
use MoamalatPay\Events\ApprovedTransaction;
use MoamalatPay\Events\ApprovedVoidRefundTransaction;
use MoamalatPay\Events\ApprovedVoidSaleTransaction;
use MoamalatPay\Events\DisallowedRequestEvent;
use MoamalatPay\Events\UnverfiedTransaction;
use MoamalatPay\Events\VerfiedTransaction;
use MoamalatPay\Tests\TestCase;

class NotificationsAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test config.
     */
    public function test_config_loaded()
    {
        $this->assertNotNull(config('moamalat-pay.merchant_id'));
        $this->assertNotNull(config('moamalat-pay.terminal_id'));
        $this->assertNotNull(config('moamalat-pay.key'));
        $this->assertNotNull(config('moamalat-pay.production'));
        $this->assertNotNull(config('moamalat-pay.show_logs'));
        $this->assertNotNull(config('moamalat-pay.notification.key'));
    }

    /**
     * Initialize base notifications api testing
     *
     * @param  array  $dispatched events should dispatched
     * @param  array  $notdispatched events should not dispatched
     * @param  array  $extraData override or add extra properties to transaction
     * @return void
     */
    public function init_test_api_notifications_transaction($dispatched, $notdispatched, $extraData = [])
    {
        Event::fake();

        // load request from json to array
        $data = json_decode(file_get_contents(__DIR__.'./../_fixtures/transactions/verfied.json'), true);
        $body = array_merge($data, $extraData);

        // call api notificaitons
        //$this->postJson(route(config('moamalat-pay.notification.route_name')), $body)
        //There is issuse with $this->postJson it sends body empty , that is why I use $this->withHeaders
        $this->withHeaders([])->post(route(config('moamalat-pay.notification.route_name')), $body)
            ->assertStatus(200)
            ->assertJson(['Message' => 'Success', 'Success' => true]);

        // assert dispatched events
        foreach ($dispatched as $e) {
            Event::assertDispatched($e, 1);
        }

        // assert not dispatched events
        foreach ($notdispatched as $e) {
            Event::assertNotDispatched($e);
        }

        // validated transaction saved in database
        $this->assertDatabaseCount(config('moamalat-pay.notification.table'), 1);
    }

    /**
     * Test approved transaction notification
     */
    public function test_api_notifications_approved_transaction()
    {
        $this->init_test_api_notifications_transaction(
            [ // dispatched
                VerfiedTransaction::class,
                ApprovedTransaction::class,
                ApprovedSaleTransaction::class,
            ],
            [ // not dispatched
                ApprovedRefundTransaction::class,
                ApprovedVoidSaleTransaction::class,
                ApprovedVoidRefundTransaction::class,
                UnverfiedTransaction::class,
                DisallowedRequestEvent::class,
            ]
        );
    }

    /**
     * Test approved refund transaction notification
     */
    public function test_api_notifications_approved_refund_transaction()
    {
        $this->init_test_api_notifications_transaction(
            [ // dispatched
                VerfiedTransaction::class,
                ApprovedTransaction::class,
                ApprovedRefundTransaction::class,
            ],
            [ // not dispatched
                ApprovedSaleTransaction::class,
                ApprovedVoidSaleTransaction::class,
                ApprovedVoidRefundTransaction::class,
                UnverfiedTransaction::class,
                DisallowedRequestEvent::class,
            ],
            [
                'TxnType' => 2,
            ]
        );
    }

    /**
     * Test approved void sale transaction notification
     */
    public function test_api_notifications_approved_void_sale_transaction()
    {
        $this->init_test_api_notifications_transaction(
            [ // dispatched
                VerfiedTransaction::class,
                ApprovedTransaction::class,
                ApprovedVoidSaleTransaction::class,
            ],
            [ // not dispatched
                ApprovedSaleTransaction::class,
                ApprovedRefundTransaction::class,
                ApprovedVoidRefundTransaction::class,
                UnverfiedTransaction::class,
                DisallowedRequestEvent::class,
            ],
            [
                'TxnType' => 3,
            ]
        );
    }

    /**
     * Test approved void refund transaction notification
     */
    public function test_api_notifications_approved_void_refund_transaction()
    {
        $this->init_test_api_notifications_transaction(
            [ // dispatched
                VerfiedTransaction::class,
                ApprovedTransaction::class,
                ApprovedVoidRefundTransaction::class,
            ],
            [ // not dispatched
                ApprovedSaleTransaction::class,
                ApprovedRefundTransaction::class,
                ApprovedVoidSaleTransaction::class,
                UnverfiedTransaction::class,
                DisallowedRequestEvent::class,
            ],
            [
                'TxnType' => 4,
            ]
        );
    }

    /**
     * Test approved transaction notification
     */
    public function test_api_notifications_unverfied_transaction()
    {

        $this->init_test_api_notifications_transaction(
            [ // dispatched
                UnverfiedTransaction::class,
            ],
            [ // not dispatched
                VerfiedTransaction::class,
                ApprovedTransaction::class,
                ApprovedSaleTransaction::class,
                ApprovedRefundTransaction::class,
                ApprovedVoidSaleTransaction::class,
                ApprovedVoidRefundTransaction::class,
            ],
            [
                'SecureHash' => 'Invaild hash for testing unverfied',
            ]
        );
    }

    /**
     * Test notify from disallowed ip
     */
    public function test_api_notifications_notify_from_disallowed_ip()
    {
        // we set invalid ip as allowed ip, to check if api will catch our ip as disallowed ip
        Config::set('moamalat-pay.notification.allowed_ips', ['12.0.0.999']);

        Event::fake();

        // call api notificaitons
        $this->postJson(route(config('moamalat-pay.notification.route_name')))
            ->assertStatus(403);

        Event::assertDispatched(DisallowedRequestEvent::class, 1);
        Event::assertNotDispatched(UnverfiedTransaction::class);
        Event::assertNotDispatched(VerfiedTransaction::class);
        Event::assertNotDispatched(ApprovedTransaction::class);
        Event::assertNotDispatched(ApprovedSaleTransaction::class);
        Event::assertNotDispatched(ApprovedRefundTransaction::class);
        Event::assertNotDispatched(ApprovedVoidSaleTransaction::class);
        Event::assertNotDispatched(ApprovedVoidRefundTransaction::class);
    }
}
