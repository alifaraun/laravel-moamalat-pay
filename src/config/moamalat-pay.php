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


    /*
	|--------------------------------------------------------------------------
	| Generate Secure Hash api
	|--------------------------------------------------------------------------
	|
	| This is service (api) to generate secureHash to be used in pay in Lightbox.
	|
	| url is route of api of generate secureHash
    |
	| route_name is name of route of api of generate secureHash
	|
	*/
    'generate-securekey' => [
        'url' =>  'moamalat-pay/securekey',
        'route_name' =>  'moamalat_pay.generate_securekey',
    ],


    /*
	|--------------------------------------------------------------------------
	| Notification (Webhook) api
	|--------------------------------------------------------------------------
	|
	| This is service from moamalat on any transaction you will receive notification
	| on api (webhook)
	|
	| key is your private notification key to use it in validate transaction requests
	|
	| url is route to receive notification
	|
	| table is name of table that will be used to save notifications
	|
	| allowed_ips are ips that will receive notification from them
	| ['*'] means receive from any ip but it is not secure to receive notifcations from anyone
	| you should ask moamalat on ips of their servers and use them
	|
	*/
    'notification' => [
        'key' => env('MOAMALATPAY_NOTIFICATION_KEY'),
        'url' =>  'moamalat-pay/notify',
        'route_name' =>  'moamalat_pay.notification',
        'table' => 'moamalat_pay_notifications',
        'allowed_ips' => ['*'],

    ]
];
