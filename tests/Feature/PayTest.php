<?php

namespace MoamalatPay\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use MoamalatPay\Pay;
use MoamalatPay\Tests\TestCase;
use MoamalatPay\View\Components\Pay as ComponentsPay;

class PayTest extends TestCase
{


    /**
     * Test config.
     */
    public function test_container_instance()
    {
        $this->assertInstanceOf(Pay::class, app('moamalat-pay'));
    }

    /**
     * Test config.
     */
    public function test_render()
    {
        $blade = app('moamalat-pay')->init();
        $this->assertStringContainsString('class MoamalataPay', $blade);
        $this->assertStringContainsString('let _moamalatPay = new MoamalataPay(', $blade);
        $this->assertStringNotContainsString('_moamalatPay.pay(', $blade);
        $this->assertStringContainsString("_moamalatPay.pay(1000, '');", app('moamalat-pay')->pay(1000));
    }

    /**
     * Test config.
     */
    public function test_component()
    {
        $blade = (new ComponentsPay())->render()->render();
        $this->assertStringContainsString('class MoamalataPay', $blade);
        $this->assertStringContainsString('let _moamalatPay = new MoamalataPay(', $blade);
        $this->assertStringNotContainsString('_moamalatPay.pay(', $blade);
        $this->assertStringNotContainsString("_moamalatPay.pay(1000, '');", $blade);
    }
}
