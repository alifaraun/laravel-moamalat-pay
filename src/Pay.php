<?php

namespace MoamalatPay;

class Pay
{
    public function init(): string
    {
        return view('moamalat-pay::pay')->render();
    }

    public function pay(string|int|float $amount, string $reference = ''): string
    {
        return '<script> _moamalatPay.pay(' . json_encode($amount) . ', ' . json_encode($reference) . '); </script>';
    }
}
