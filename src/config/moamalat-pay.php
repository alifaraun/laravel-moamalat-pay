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
    'merchant_id' => env('MOAMALAT_PAY_MID'),

    // TID => terminal_id
    'terminal_id' => env('MOAMALAT_PAY_TID'),

    // Secure key
    'key' => env('MOAMALAT_PAY_KEY'),

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
