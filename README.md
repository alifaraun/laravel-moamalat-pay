# Laravel Moamalat Pay

This package allows you to easily use Moamalat [ligthbox](http://docs.moamalat.net:55/lightbox.html) to add payment gateway in your laravel project.

---
**NOTE**
This is not official library from Moamalat , It is just an open source Library.
---


## Table of contents 
- [Installation](#installation)
- [Configuration File](#configuration-file)
- [Configuration for testing purpose](#configuration-for-testing-purpose)
  * [Merchant](#merchant)
  * [Card](#card)
- [Usage](#usage)
    + [Laravel componet](#laravel-componet)
    + [Laravel methods](#laravel-methods)
    + [Front end Javascript](#front-end-javascript)
    + [Check processing status](#check-processing-status)
    + [Get Transaction in back-end](#get-transaction-in-back-end)
      - [Examples](#examples)
  * [Security](#security)
- [Credits](#credits)
- [License](#license)



## Installation

You can install the package via composer:

```bash
composer require alifaraun/laravel-moamalat-pay
```

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
	'show_logs' => true,
];

```

set your configurations in `.env` file:

```bash
MOAMALATPAY_MID=
MOAMALATPAY_TID=
MOAMALATPAY_KEY=
MOAMALATPAY_PRODUCTION=
```

## Configuration for testing purpose

### Merchant 
``` bash
Merchant id	: 10081014649
Terminal Id	: 99179395
Secure key	: 39636630633731362D663963322D346362642D386531662D633963303432353936373431
```

### Card 
``` bash
Card : 6394993077260781
EXP  : 10/23
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
addEventListener("moamalatCompleted", function(e) {
    e.detail // response data
    /* e.detail
	{
	    "TxnDate": "220822141359",
	    "SystemReference": "1233114",
	    "NetworkReference": "223414600869",
	    "MerchantReference": "1641729671",
	    "Amount": "10",
	    "Currency": "434",
	    "PaidThrough": "Card",
	    "PayerAccount": "639499XXXXXX0781",
	    "PayerName": "SS",
	    "ProviderSchemeName": "",
	    "SecureHash": "EB085D519BCC0887EA1031939F15DE230991E8C1E8731CD11A6916A5B7FC29D8",
	    "DisplayData": "",
	    "TokenCustomerId": "",
	    "TokenCard": ""
	}
    */
})

addEventListener("moamalatError", function(e) {
    e.detail // response data
    /* e.detail
	{
	    "error": "CUBEEX5212216:Authentication Failed",
	    "Amount": "200.031",
	    "MerchantReferenece": "",
	    "DateTimeLocalTrxn": "220818232732",
	    "SecureHash": "1C8B1301AD4C00BE66EC25FD45A81D0C4030C79EF53CA903FA5009ECCAD08D46"
	}
    */
})
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
          "Amnt" => "10",
          "AmountTrxn" => "10",
          "AuthCode" => null,
          "CardNo" => "639499XXXXXX0781",
          "CardType" => "",
          "Currency" => "LYD",
          "ExternalTxnId" => null,
          "FeeAmnt" => "0",
          "HasToken" => true,
          "ISForceSendCVCForRefund" => true,
          "IsMustVoidTotalAmount" => true,
          "IsPointTrasnaction" => false,
          "IsRefund" => false,
          "IsRefundEnabled" => true,
          "IsSend" => false,
          "MerchantReference" => "1641729671",
          "MobileNumber" => null,
          "OriginalTxnId" => "",
          "RRN" => "223414600869",
          "ReceiptNo" => "223414600869",
          "RefundButton" => 1,
          "RefundReason" => "",
          "RefundSource" => "",
          "RefundUserCreator" => "",
          "RelatedTxnTotalAmount" => null,
          "RemainingRefundAmount" => "10",
          "ResCodeDesc" => "Approved",
          "STAN" => "600869",
          "SenderName" => "SS",
          "Status" => "Approved",
          "TipAmnt" => "0",
          "TransType" => "Sale",
          "TransactionChannel" => "Card",
          "TransactionId" => "1233114",
          "TxnDateTime" => "22/08/22  14:13",
          "TxnIcon" => 2
        ]
*/

$transaction->get('CardNo');
// return 639499XXXXXX0781

$transaction->getWithDefault('Card','card-not-found');
// return card-not-found

$transaction->checkApproved();
// if transaction status is Approved it will return true 

$transaction->checkApproved(10000,'639499XXXXXX0781');
// if transaction is status is Approved , amount=10000 and CardNo=639499XXXXXX0781 it will return true
```


<!-- 
### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
 -->

### Security

If you discover any security related issues, please email ali1996426@hotmail.com instead of using the issue tracker.

## Credits

-   [Ali Faraun](https://github.com/alifaraun)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT) 
