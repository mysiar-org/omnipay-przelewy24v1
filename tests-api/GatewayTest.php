<?php

declare(strict_types=1);

namespace API;

use Omnipay\Omnipay;
use Omnipay\Przelewy24\Message\AbstractResponse;
use PHPUnit\Framework\TestCase;

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
}
