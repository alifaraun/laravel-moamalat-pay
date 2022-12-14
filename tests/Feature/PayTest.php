<?php

namespace MoamalatPay\Tests\Feature;

use MoamalatPay\Pay;
use MoamalatPay\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class PayTest extends TestCase
{

    use InteractsWithViews;


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
        $view = $this->blade('<x-moamalat-pay amount="1000" />');
        $view->assertSeeText('class MoamalataPay');
        $view->assertSeeText('let _moamalatPay = new MoamalataPay(');
        $view->assertSeeText('_moamalatPay.pay(1000, "");', false);
    }
}
