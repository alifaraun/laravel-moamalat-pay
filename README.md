# Laravel Moamalat Pay

[![Build Status](https://github.com/alifaraun/laravel-moamalat-pay/actions/workflows/ci-cd.yml/badge.svg)](https://github.com/alifaraun/laravel-moamalat-pay/actions)
[![Latest Stable Version](https://poser.pugx.org/alifaraun/laravel-moamalat-pay/v/stable.svg)](https://packagist.org/packages/alifaraun/laravel-moamalat-pay)
[![Total Downloads](https://poser.pugx.org/alifaraun/laravel-moamalat-pay/downloads.svg)](https://packagist.org/packages/alifaraun/laravel-moamalat-pay)
[![License](https://poser.pugx.org/alifaraun/laravel-moamalat-pay/license)](https://packagist.org/packages/alifaraun/laravel-moamalat-pay)

This package allows you to easily use Moamalat [ligthbox](https://docs.moamalat.net/lightBox.html) to add payment gateway in your laravel project.

---

**NOTE**
This is not official library from Moamalat , It is just an open source Library.

---

## Table of contents

- [Installation](#installation)
- [Configuration File](#configuration-file)
- [Configuration for testing purpose](#configuration-for-testing-purpose)
  - [Merchant](#merchant)
  - [Card](#card)
- [Usage](#usage)
  - [Laravel componet](#laravel-componet)
  - [Laravel methods](#laravel-methods)
  - [Front end Javascript](#front-end-javascript)
  - [Check processing status](#check-processing-status)
  - [Get Transaction in back-end](#get-transaction-in-back-end)
    - [Examples](#examples)
  - [Notifications Service (Webhook)](#notifications-service-webhook)
    - [Available Scopes](#available-scopes)
    - [Events](#events)
  - [Refund and Void Transactions](#refund-and-void-transactions)
    - [Examples](#examples-1)
- [Testing](#testing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```bash
composer require alifaraun/laravel-moamalat-pay
```

**Laravel Version Compatibility**

| Laravel | Package | install command                                          |
| :------ | :------ | :------------------------------------------------------- |
| 12.x.x  | 6.x     | `composer require alifaraun/laravel-moamalat-pay "^6.0"` |
| 11.x.x  | 5.x     | `composer require alifaraun/laravel-moamalat-pay "^5.0"` |
| 10.x.x  | 4.x     | `composer require alifaraun/laravel-moamalat-pay "^4.0"` |
| 9.x.x   | 3.x     | `composer require alifaraun/laravel-moamalat-pay "^3.0"` |
| 8.x.x   | 2.x     | `composer require alifaraun/laravel-moamalat-pay "^2.0"` |
| ^7.25.0 | 1.x     | `composer require alifaraun/laravel-moamalat-pay "^1.0"` |

If you want to customize configurations:

```bash
php artisan vendor:publish --provider="moamalat-pay"
```

## Configuration File

The configuration file **moamalat-pay.php** is located in the **config** folder. Following are its contents when published:

```php

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
    | Enable Cancel Event On Success
    |--------------------------------------------------------------------------
    |
    | If the enable_cancel_event_on_success is set to "true", the cancel event will be triggered even if the payment is successful
    |
    */
    'enable_cancel_event_on_success' => true,

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
```

set your configurations in `.env` file:

```bash
MOAMALATPAY_MID=
MOAMALATPAY_TID=
MOAMALATPAY_KEY=
MOAMALATPAY_NOTIFICATION_KEY=
MOAMALATPAY_PRODUCTION=
```

## Configuration for testing purpose

### Merchant

```bash
Merchant id	: 10081014649
Terminal Id	: 99179395
Secure key	: 3a488a89b3f7993476c252f017c488bb
```

### Card

```bash
Card : 6395043835180860
Card : 6395043165725698
Card : 6395043170446256
EXP  : 01/27
OTP  : 111111
```

## Usage

#### Laravel componet

```blade
// Initialize pay
<x-moamalat-pay />

// when pass amount property, it will show pay form directly
<x-moamalat-pay amount="1000" />
```

#### Laravel methods

To call it using methods

```php
// Initialize pay
app('moamalat-pay')->init();

// call pay
// $amount int libyan dirham not dinar
//$reference is optional
app('moamalat-pay')->pay(int $amount,string $reference = '');
```

#### Front end Javascript

To call pay from js

```js
_moamalatPay.pay(int amount,string reference = '')
```

#### Check processing status

Available events to check if operation success or fail

```js
addEventListener("moamalatCompleted", function (e) {
  e.detail; // response data
  /* e.detail
    {
    "TxnDate": "250303164850",
    "SystemReference": "1301679",
    "NetworkReference": "506216680586",
    "MerchantReference": "test-demo",
    "Amount": "1000",
    "Currency": "434",
    "PaidThrough": "Card",
    "PayerAccount": "639504XXXXXX3733",
    "PayerName": "ALI",
    "ProviderSchemeName": "",
    "SecureHash": "915B22C4E2A96DB03F755D4254F65C87486AE3B1C19B9BCDE9481836832F4822",
    "CardToken": null,
    "CustomerId": null
    }
    */
});

addEventListener("moamalatError", function (e) {
  e.detail; // response data
  /* e.detail
	{
	    "error": "CUBEEX5212216:Authentication Failed",
	    "Amount": "200.031",
	    "MerchantReferenece": "",
	    "DateTimeLocalTrxn": "220818232732",
	    "SecureHash": "1C8B1301AD4C00BE66EC25FD45A81D0C4030C79EF53CA903FA5009ECCAD08D46"
	}
    */
});

addEventListener("moamalatCancel", function () {});
```

#### Get Transaction in back-end

```php
use MoamalatPay\Transaction;

// get transaction from NPG(moamalat)
$transaction = new Transaction($networkReference, $merchantReference);
// Throws an exception if there is a problem in loading the transaction

/** available methods to interact with transaction **/

/**
 * Get all properties of transaction
 * @return Array
 */
$transaction->getAll();

/**
 * Get property of transaction
 * @param $property key
 * @return mixed
 */
$transaction->get($property);


/**
 * Get property of reponse , if property not exists return default value
 *
 * @param $property
 * @param $default
 * @return mixed
 */
$transaction->getWithDefault($property, $default = null);

/**
 * Get all properties of reponse
 * @return Array
 */
$transaction->getResponse();

/**
 * Check status of transaction is Approved
 *
 * @param $amount
 * @param $card
 * @return boolean
 */
$transaction->checkApproved($amount = null, $card = null);

```

##### Examples

```php
use MoamalatPay\Transaction;

// get transaction from NPG(moamalat)
$transaction = new Transaction("223414600869","1641729671");


$transaction->getAll();
/* return
    [
      "TransactionChannel" => "Card",
      "CardNo" => "639504XXXXXX0860",
      "SenderName" => "OMAR",
      "CardType" => null,
      "Amnt" => "1000",
      "AmountTrxn" => "1000",
      "FeeAmnt" => "0.000",
      "TipAmnt" => "0.000",
      "Status" => "Approved",
      "Currency" => "LYD",
      "TransType" => "Sale",
      "IsPointTrasnaction" => false,
      "STAN" => "625396",
      "RRN" => "506118625396",
      "MerchantReference" => "test-demo",
      "ReceiptNo" => "506118625396",
      "MobileNumber" => null,
      "TransactionId" => "1301669",
      "ExternalTxnId" => null,
      "TxnDateTime" => "02/03/25  18=>25",
      "IsRefundEnabled" => true,
      "ResCodeDesc" => "Approved",
      "IsSend" => false,
      "ISForceSendCVCForRefund" => true,
      "HasToken" => true,
      "AuthCode" => null,
      "RefundButton" => 0,
      "RemainingRefundAmount" => "1000.000",
      "TerminalId" => 49077229,
      "IsRefund" => false,
      "RefundReason" => "",
      "RefundSource" => "",
      "RefundUserCreator" => "",
      "OriginalTxnId" => "",
      "TxnIcon" => 2,
      "IsMustVoidTotalAmount" => false,
      "IsCardPresent" => false,
      "RelatedTxnTotalAmount" => null,
      "UserDefinedData" => [],
      "InvoiceServiceSummary" => null
    ]
*/

$transaction->get('CardNo');
// return 639504XXXXXX0860

$transaction->getWithDefault('Card','card-not-found');
// return card-not-found

$transaction->checkApproved();
// if transaction status is Approved it will return true

$transaction->checkApproved(10000,'639504XXXXXX0860');
// if transaction is status is Approved , amount=10000 and CardNo=639504XXXXXX0860 it will return true
```

### Notifications Service (Webhook)

Notification Services allow merchants to receive notifications whenever a transaction is generated for their accounts

We save all notifications in database with fire events depends on transaction type and status

```php
/*
MoamalatPay\Models\MoamalatPayNotification \\ Eloquent Model
\\ properites
id
MerchantId
TerminalId
DateTimeLocalTrxn
TxnType
Message
PaidThrough
SystemReference
NetworkReference
MerchantReference
Amount
Currency
PayerAccount
PayerName
ActionCode
request
ip
verified
created_at
*/
```

#### Available Scopes

```php
// filter to get approved transactions (ActionCode = 00)
MoamalatPay\Models\MoamalatPayNotification::approved()

// filter to get verified transactions (verified = 1)
MoamalatPay\Models\MoamalatPayNotification::verified()

// filter to get transactions for currency terminal_id and merchant_id in config
MoamalatPay\Models\MoamalatPayNotification::currentCredential()
```

#### Events

Example of how to add listener , check [laravel documentation](https://laravel.com/docs/events) for more info

```php
// event will be fired when receive request from ip not exists in allowed_ips in config of moamalat-pay
Event::listen(function (MoamalatPay\Events\DisallowedRequestEvent $event) {
});

// event will be fired after check secureHas is unverified
Event::listen(function (MoamalatPay\Events\UnverfiedTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

// event will be fired after check secureHas is verified
Event::listen(function (MoamalatPay\Events\VerfiedTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

// event will be fired after check secureHas is verified and transaction status is approved
Event::listen(function (MoamalatPay\Events\ApprovedTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

// event will be fired after check secureHas is verified and transaction status is approved
// and type of transaction is : 1: Sale
Event::listen(function (MoamalatPay\Events\ApprovedSaleTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

// event will be fired after check secureHas is verified and transaction status is approved
// and type of transaction is : 2: Refund
Event::listen(function (MoamalatPay\Events\ApprovedRefundTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

// event will be fired after check secureHas is verified and transaction status is approved
// and type of transaction is : 3: Void Sale
Event::listen(function (MoamalatPay\Events\ApprovedVoidSaleTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

// event will be fired after check secureHas is verified and transaction status is approved
// and type of transaction is : 4: Void Refund
Event::listen(function (MoamalatPay\Events\ApprovedVoidRefundTransaction $event) {
   $event->notification // Eloquent Model of transaction
});

```

#### Refund and Void Transactions

When the refund is called before settlement (usually settlement at the end of the day), it will be void, otherwise it will be refunded

```php

/**
 * Refund transaction by system reference of transaction
 * @param string|integer $systemReference
 * @param string|integer $amount
 * @return array content response of moamalat
 */
app('moamalat-pay-refund')->refundBySystemReference($systemReference, $amount)->getAll()
// Throws an exception if there is a problem in refund the transaction


/**
 * Refund transaction by network reference of transaction
 * @param string|integer $networkReference
 * @param string|integer $amount
 * @return array content response of moamalat
 */
app('moamalat-pay-refund')->refundByNetworkReference($networkReference, $amount)->getAll()
// Throws an exception if there is a problem in refund the transaction

/* response : return of getAll() method
{
  "SystemTxnId": 0,
  "ActionCode": null,
  "AuthCode": null,
  "ExternalTxnId": null,
  "RefNumber": "1301681", // System reference for the new refund transaction
  "TxnDate": null,
  "ReceiptNumber": null,
  "ReceiverAccountNumber": null,
  "ReceiverName": null,
  "ReceiverScheme": null,
  "MerchantReference": null,
  "SystemReference": 0,
  "NetworkReference": null,
  "IsEnableRefund": false,
  "DecimalFraction": 3,
  "IsYallaRefundPending": false,
  "TransactionId": null,
  "ReferenceId": null,
  "Success": true,
  "Message": "Approved",
  "SecureHashData": null,
  "SecureHash": null,
  "StatusCode": 200
}
*/
```

##### Examples

```php
$r = app('moamalat-pay-refund')->refundBySystemReference("1233114", "10");
// or
$r = app('moamalat-pay-refund')->refundByNetworkReference("223414600869", "10");

// will return instance of MoamalatPay\Refund class

/**
 * Get all properties of reponse
 * @return array
 */
$r->getAll();
/* response
{
  "SystemTxnId": 0,
  "ActionCode": null,
  "AuthCode": null,
  "ExternalTxnId": null,
  "RefNumber": "1301681", // System reference for the new refund transaction
  "TxnDate": null,
  "ReceiptNumber": null,
  "ReceiverAccountNumber": null,
  "ReceiverName": null,
  "ReceiverScheme": null,
  "MerchantReference": null,
  "SystemReference": 0,
  "NetworkReference": null,
  "IsEnableRefund": false,
  "DecimalFraction": 3,
  "IsYallaRefundPending": false,
  "TransactionId": null,
  "ReferenceId": null,
  "Success": true,
  "Message": "Approved",
  "SecureHashData": null,
  "SecureHash": null,
  "StatusCode": 200
}
*/

/**
 * Get property of transaction
 * @param $property key
 * @return mixed
 */
$r->get($property);
$r->get('Message');
// return Approved


/**
 * Get property of reponse , if property not exists return default value
 *
 * @param $property
 * @param $default
 * @return mixed
 */
$r->getWithDefault($property, $default = null);
$r->getWithDefault('Card', 'No Card');
// return No Card


/**
 * Get SystemReference of new refund transaction
 * @return string|integer
 */
$r->getRefNumber();
// return 1233678

```

## Testing

Run the tests with:

```
composer test
// or
./vendor/bin/phpunit
```

Run Static Analysis Tool (PHPStan)

```
composer analyse
// or
./vendor/bin/phpstan analyse
```

<!--
### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
 -->

## Security

If you discover any security related issues, please email ali1996426@hotmail.com instead of using the issue tracker.

## Credits

- [Ali Faraun](https://github.com/alifaraun)
- [All Contributors](../../contributors)

## License

The MIT License (MIT)
