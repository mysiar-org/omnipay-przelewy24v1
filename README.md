# Omnipay: Przelewy24 API V1

**Przelewy24 API V1 gateway for the Omnipay PHP payment processing library**

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) ![example workflow](https://github.com/mysiar/omnipay-przelewy24v1/actions/workflows/tests.yml/badge.svg)

## API endpoints implemented

| API endpoint                 | Gateway method   |
|:-----------------------------|:-----------------|
| /api/v1/testAccess           | testAccess       |
| /api/v1/payment/methods      | methods          |
| /api/v1/transaction/register | purchase         |
| /api/v1/transaction/verify   | completePurchase |
| /api/v1/transaction/refund   | refund           |

## Install

This gateway can be installed with [Composer](https://getcomposer.org/):

``` bash
$ composer require mysiar/omnipay-przelewy24v1
```

## Usage

The following gateways are provided by this package:

* Przelewy24

Reference official documentation https://developers.przelewy24.pl/index.php

## Example

```php

require_once  __DIR__ . '/vendor/autoload.php';

use Omnipay\Omnipay;

/** @var \Omnipay\Przelewy24\Gateway $gateway */
$gateway = Omnipay::create('Przelewy24');

$gateway->initialize([
    'merchantId' => 'YOUR MERCHANT ID HERE',
    'posId'      => 'YOUR POS ID HERE',
    'crc'        => 'YOUR CRC KEY HERE',
    'reportKey'  => 'YOUR REPORT KEY HERE'
    'testMode'   => true,
]);

$params = [
    'sessionId' => 2327398739,
    'amount' => 12.34,
    'currency' => 'PLN',
    'description' => 'Payment test',
    'email' => 'franek@dolas.com',
    'country' => 'PL',
    'returnUrl' => 'www.your-domain.pl/return_here',
    'notifyUrl' => 'www.your-domain.pl/notify_here',
];

$response = $gateway->purchase($params)->send();
```

For more exmples check `tests-api/GatewayTest.php`

Optionally you can specify the payment channels.

```php
$gateway->initialize([
    //[...]
    'channel' => Gateway::P24_CHANNEL_CC,
]);

// or
$gateway->setChannel(Gateway::P24_CHANNEL_CC); 
```

Optionally you can specify language (default: en).

```php
$gateway->initialize([
    //[...]
    'language' => 'pl',
]);

// or
$gateway->setLanguage('pl'); 
```
