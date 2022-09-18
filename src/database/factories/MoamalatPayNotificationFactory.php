<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MoamalatPay\Models\MoamalatPayNotification;


$factory->define(
    MoamalatPayNotification::class,
    function (Faker $faker) {

        $data = [
            'MerchantId' => config('moamalat-pay.merchant_id'),
            'TerminalId' => config('moamalat-pay.terminal_id'),
            'DateTimeLocalTrxn' => now(),
            'TxnType' => $faker->numberBetween(1, 4),
            'Message' => $faker->word(),
            'PaidThrough' => $faker->randomElement(['Card', 'Tahweel']),
            'SystemReference' => $faker->uuid(),
            'NetworkReference' => $faker->uuid(),
            'MerchantReference' => $faker->uuid(),
            'Amount' => $faker->randomNumber(),
            'Currency' => 434,
            'PayerAccount' => $faker->creditCardNumber(),
            'PayerName' => $faker->word(),
            'ActionCode' => $faker->randomNumber(2),
        ];

        return $data + [
            'request' => json_encode($data),
            'verified' => $faker->boolean(),
            'ip' => $faker->ipv4()
        ];
    }
);
