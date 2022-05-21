<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\PurchaseOfflineRequest;
use Omnipay\Przelewy24\Message\PurchaseOfflineResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOfflineRequestTest extends TestCase
{
    /**
     * @var PurchaseOfflineRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseOfflineRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'token' => '29fa01f8-6bb8-4187-9fb0-ec6e1a62a731',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('29fa01f8-6bb8-4187-9fb0-ec6e1a62a731', $data['token']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('PurchaseOfflineSuccess.txt');
        /** @var PurchaseOfflineResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseOfflineResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
        $data = $response->getInfo();
        $this->assertCount(7, $data);
        $this->assertSame(1234567890, $data['transactionId']);
        $this->assertSame('cb53d93f-3933-48dd-9756-13e6c03a74c0', $data['sessionId']);
        $this->assertSame(1234, $data['amount']);
        $this->assertSame('statement-full', $data['statement']);
        $this->assertSame('PL21109024021694854264921521', $data['iban']);
        $this->assertSame('Franek Dolas', $data['ibanOwner']);
        $this->assertSame('Strzebrzeszyn 123', $data['ibanOwnerAddress']);
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('PurchaseOfflineAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseOfflineResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendInvalidDataFailure(): void
    {
        $this->setMockHttpResponse('PurchaseOfflineInvalidDataFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseOfflineResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }
}
