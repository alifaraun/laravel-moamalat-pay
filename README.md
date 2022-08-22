# Laravel Moamalat Pay

This package allows you to easily use Moamalat [ligthbox](http://docs.moamalat.net:55/lightbox.html) to add payment gateway in your laravel project.

---
**NOTE**
This is not official library from Moamalat , It is just an open source Library.
---

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
})

addEventListener("moamalatError", function(e) {
    e.detail // response data
})
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
