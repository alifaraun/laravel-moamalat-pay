<?php

namespace MoamalatPay\Tests;

use MoamalatPay\Providers\MoamalatPayProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // additional setup
        $this->withFactories(__DIR__.'/../src/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            MoamalatPayProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('moamalat-pay', [
            'merchant_id' => '10004188779',
            'terminal_id' => '49077229',
            'key' => '39353638663431622D303136622D343235322D623330632D383361633838383965373965',
            'production' => false,
            'show_logs' => true,
            'generate-securekey' => [
                'url' => 'moamalat-pay/securekey',
                'route_name' => 'moamalat_pay.generate_securekey',
            ],
            'notification' => [
                'key' => '39353638663431622D303136622D343235322D623330632D383361633838383965373965',
                'url' => 'moamalat-pay/notify',
                'route_name' => 'moamalat_pay.notification',
                'table' => 'moamalat_pay_notifications',
                'allowed_ips' => ['*'],
            ],
        ]);
        // perform environment setup
    }
}
