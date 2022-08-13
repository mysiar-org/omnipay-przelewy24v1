# Omnipay: Przelewy24 API V1

**Przelewy24 API V1 gateway for the Omnipay PHP payment processing library**

[![CI](https://github.com/mysiar-org/omnipay-przelewy24v1/actions/workflows/ci.yml/badge.svg)](https://github.com/mysiar-org/omnipay-przelewy24v1/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/github/release/mysiar-org/omnipay-przelewy24v1.svg)](https://github.com/mysiar/omnipay-przelewy24v1/releases)
[![Total downloads](https://img.shields.io/packagist/dt/mysiar/omnipay-przelewy24v1.svg)](https://packagist.org/packages/mysiar/omnipay-przelewy24v1)
[![GitHub license](https://img.shields.io/github/license/mysiar-org/omnipay-przelewy24v1)](https://github.com/mysiar-org/omnipay-przelewy24v1/blob/main/LICENSE)
[![PHP Version Require](http://poser.pugx.org/mysiar/omnipay-przelewy24v1/require/php)](https://packagist.org/packages/mysiar/omnipay-przelewy24v1)
[![GitHub stars](https://img.shields.io/github/stars/mysiar-org/omnipay-przelewy24v1)](https://github.com/mysiar-org/omnipay-przelewy24v1/stargazers)

[![Coverage](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=coverage&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Maintainability Rating](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=sqale_rating&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Quality Gate Status](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=alert_status&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Reliability Rating](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=reliability_rating&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Security Rating](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=security_rating&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Bugs](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=bugs&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Code Smells](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=code_smells&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Duplicated Lines (%)](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=duplicated_lines_density&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Lines of Code](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=ncloc&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Technical Debt](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=sqale_index&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)
[![Vulnerabilities](https://sq.mysiar.dev/api/project_badges/measure?project=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc&metric=vulnerabilities&token=e3ee022dee8869d296c924b840b66e0aefb86f62)](https://sq.mysiar.dev/dashboard?id=mysiar-org_omnipay-przelewy24v1_AYKYVbX_XdtDi-mnE6gc)

## API endpoints implemented

| API endpoint                        | Gateway method   |
|:------------------------------------|:-----------------|
| /api/v1/testAccess                  | testAccess       |
| /api/v1/payment/methods             | methods          |
| /api/v1/transaction/register        | purchase         |
| /api/v1/transaction/verify          | completePurchase |
| /api/v1/transaction/refund          | refund           |
| /api/v1/transaction/by/sessionId    | purchaseInfo     |
| /api/v1/card/info                   | cardInfo         |
| /api/v1/card/pay                    | cardPay          |
| /api/v1/card/charge                 | cardCharge       |
| /api/v1/card/chargeWith3ds          | cardCharge3ds    |
| /api/v1/transaction/registerOffline | purchaseOffline  |

## Install

This gateway can be installed with [Composer](https://getcomposer.org/):

``` bash
$ composer require mysiar/omnipay-przelewy24v1
```

## Usage

The following gateways are provided by this package:

* Przelewy24

Reference official documentation https://developers.przelewy24.pl/index.php?en

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

For more examples check 
* [tests-api/GatewayTest.php](tests-api/GatewayTest.php)
* [tests/Message/](tests/Message/)

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
