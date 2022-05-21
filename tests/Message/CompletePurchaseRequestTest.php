<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\CompletePurchaseRequest;
use Omnipay\Przelewy24\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => '144354',
            'posId' => '144354',
            'crc' => '1287875353948',
            'sessionId' => '42',
            'amount' => '12.00',
            'currency' => 'PLN',
            'transaction_id' => '10273987',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('42', $data['sessionId']);
        $this->assertSame(1200, $data['amount']);
        $this->assertSame('PLN', $data['currency']);
        $this->assertSame('10273987', $data['orderId']);
        $this->assertSame('9990dc235a73939da03d7033f302dc4f6d621d662f03f5eb8db827f95d2a048c48f5fe28b5b4a9be13a1fc4a58058055', $data['sign']);
        $this->assertCount(7, $data);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('', $response->getMessage());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Incorrect CRC value', $response->getMessage());
    }
}
