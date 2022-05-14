<?php

declare(strict_types=1);

namespace API;

use Omnipay\Omnipay;
use Omnipay\Przelewy24\Message\AbstractResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\VarDumper;

class GatewayTest extends TestCase
{
    private $gateway;

    public function setUp()
    {
        $settings = [
            'merchantId' => getenv('P24V1_MERCHANT_ID'),
            'posId' => getenv('P24V1_POS_ID'),
            'crc' => getenv('P24V1_CRC'),
            'reportKey' => getenv('P24V1_REPORT_KEY'),
            'language' => getenv('P24V1_LANGUAGE'),
            'testMode' => true,
        ];

        $this->gateway = Omnipay::create("Przelewy24");
        $this->gateway->initialize($settings);
    }

    public function testAccess(): void
    {
        $response = $this->gateway->testAccess()->send();
        $this->assertSame(AbstractResponse::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());
        $this->assertTrue($response->isSuccessful());

        $this->gateway->setReportKey('dummy');
        $response = $this->gateway->testAccess()->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(AbstractResponse::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame("Incorrect authentication", $response->getMessage());
    }

    public function testMethods(): void
    {
        $response = $this->gateway->methods()->send();
        $this->assertSame(AbstractResponse::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());
        $this->assertTrue($response->isSuccessful());
        $this->assertGreaterThan(0, count($response->getMethods()));
    }

    public function testPurchase(): void
    {
        $sessionId = $this->randomString();

        $response = $this->gateway->purchase([
            'sessionId' => $sessionId,
            'amount' => 1000,
            'currency' => 'PLN',
            'description' => 'Transaction description',
            'email' => 'franek@dolas.com',
            'country' => 'PL',
            'returnUrl' => 'https://omnipay-przelewy24v1.requestcatcher.com/return',
            'notifyUrl' => 'https://omnipay-przelewy24v1.requestcatcher.com/notify',
        ])->send();

        VarDumper::dump($sessionId);
        VarDumper::dump($response->getRedirectUrl());

        $this->assertSame(AbstractResponse::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());
        $this->assertTrue($response->isSuccessful());
        $this->assertContains('https://sandbox.przelewy24.pl/trnRequest/', $response->getRedirectUrl());
        $this->assertSame(35, strlen($response->getToken()));
    }

    private function randomString(int $length = 15): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
