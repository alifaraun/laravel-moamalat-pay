<?php

namespace MoamalatPay\Tests\Feature;


use MoamalatPay\Tests\TestCase;

class ConfigAPITest extends TestCase
{

    /**
     * Test that the Moamalat Pay config values are loaded.
     *
     * @return void
     */
    public function test_config_loaded()
    {
        $this->assertNotNull(config('moamalat-pay.generate-securekey.url'));
        $this->assertNotNull(config('moamalat-pay.generate-securekey.route_name'));
    }

    /**
     * Test generating a secure key with valid parameters.
     *
     * @return void
     */
    public function test_generate_secure_key()
    {
        $params = [
            'MID' => config('moamalat-pay.merchant_id'),
            'TID' => config('moamalat-pay.terminal_id'),
            'amount' => '1000',
        ];

        $this->getJson(route(config('moamalat-pay.generate-securekey.route_name'), $params))
            ->assertOk()
            ->assertJsonStructure(['secureHash', 'DateTimeLocalTrxn']);
    }

    /**
     * Test validation errors when generating a secure key without required parameters.
     *
     * @return void
     */
    public function test_generate_secure_key_validation()
    {
        $this->getJson(route(config('moamalat-pay.generate-securekey.route_name')))
            ->assertUnprocessable()
            ->assertJson([
                "message" => "The MID field is required. (and 2 more errors)",
                "errors" => [
                    "MID" => [
                        "The MID field is required."
                    ],
                    "TID" => [
                        "The TID field is required."
                    ],
                    "amount" => [
                        "The amount field is required."
                    ]
                ]
            ], true);
    }


    /**
     * Test generating a secure key with incorrect merchant/terminal IDs.
     *
     * @return void
     */
    public function test_generate_secure_key_with_incrorrect_configurations()
    {
        $params = [
            'MID' => '100000009',
            'TID' => '400000029',
            'amount' => '1000',
        ];

        $this->getJson(route(config('moamalat-pay.generate-securekey.route_name'), $params))
            ->assertUnprocessable()
            ->assertJson([
                "message" => "The MID is incorrect (and 1 more error)",
                "errors" => [
                    "MID" => [
                        "The MID is incorrect"
                    ],
                    "TID" => [
                        "The TID is incorrect"
                    ],
                ]
            ], true);
    }
}
