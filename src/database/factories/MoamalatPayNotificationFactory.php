<?php

namespace MoamalatPay\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MoamalatPayNotificationFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $data = [
            'MerchantId' => config('moamalat-pay.merchant_id'),
            'TerminalId' => config('moamalat-pay.terminal_id'),
            'DateTimeLocalTrxn' => now(),
            'TxnType' => $this->faker->numberBetween(1, 4),
            'Message' => $this->faker->word(),
            'PaidThrough' => $this->faker->randomElement(['Card', 'Tahweel']),
            'SystemReference' => $this->faker->uuid(),
            'NetworkReference' => $this->faker->uuid(),
            'MerchantReference' => $this->faker->uuid(),
            'Amount' => $this->faker->randomNumber(),
            'Currency' => 434,
            'PayerAccount' => $this->faker->creditCardNumber(),
            'PayerName' => $this->faker->word(),
            'ActionCode' => $this->faker->randomNumber(2),
        ];

        return $data + [
            'request' => json_encode($data),
            'verified' => $this->faker->boolean(),
            'ip' => $this->faker->ipv4()
        ];
    }
}
