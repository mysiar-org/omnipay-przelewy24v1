<?php

use Omnipay\Omnipay;

require __DIR__ . '/vendor/autoload.php';
 $P24V1_MERCHANT_ID="176454";
 $P24V1_POS_ID="176454";
 $P24V1_CRC="3a3e4e2cbf6dc206";
 $P24V1_REPORT_KEY="9309ec6a9b3b72d8c6a2e5ebb813d89e";
 $P24V1_LANGUAGE="pl";
$settings = [
    'merchantId' => $P24V1_MERCHANT_ID,
    'posId' => $P24V1_POS_ID,
    'crc' => $P24V1_CRC,
    'reportKey' => $P24V1_REPORT_KEY,
    'language' => $P24V1_LANGUAGE,
    'testMode' => true,
];

$gateway = Omnipay::create("Przelewy24");
$gateway->initialize($settings);

$response = $gateway->refund([
    'requestId' => '141223',
    'refunds' => [
        [
            'orderId' => 'p24-K21-B08-H59',
            'sessionId' => '97ff4a8e-ac5f-4e87-9b7b-c34cf2d3e643',
            'amount' => '10.00'
        ]
    ],
    'refundsUuid' => '322141',
    'urlStatus' => 'https://ogsae.requestcatcher.com/'
])->send();

\Symfony\Component\VarDumper\VarDumper::dump($response);