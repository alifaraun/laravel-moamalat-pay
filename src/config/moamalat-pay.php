<?php

return [
    /*
	|--------------------------------------------------------------------------
	| Moamalat Payment Gateway Config
	|--------------------------------------------------------------------------
	|
	| These options to set your configurations of muamalat
	|
	*/

    // MID => merchant_id or outlet_number
    'merchant_id' => env('MOAMALATPAY_MID'),

    // TID => terminal_id
    'terminal_id' => env('MOAMALATPAY_TID'),

    // Secure key
    'key' => env('MOAMALATPAY_KEY'),

    /*
	|--------------------------------------------------------------------------
	| Production
	|--------------------------------------------------------------------------
	|
	| If the production is set to "true", you will work on production environment
    | otherwise it will use testing environment
	|
	*/
    'production' => env('MOAMALATPAY_PRODUCTION', false),

    /*
	|--------------------------------------------------------------------------
	| Show
	|--------------------------------------------------------------------------
	|
	| If the show_logs is set to "true", you will see configurations
    | and response of requests in browser console
	|
	*/
    'show_logs' => false,

];
