<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\BlikAliasesByEmailCustomRequest;
use Omnipay\Przelewy24\Message\BlikAliasesByEmailCustomResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class BlikAliasesByEmailCustomRequestTest extends TestCase
{
    /**
     * @var BlikAliasesByEmailCustomRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new BlikAliasesByEmailCustomRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            "email" => "adam@migacz.pl",
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('adam@migacz.pl', $data['email']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailSuccess.txt');
        /** @var BlikAliasesByEmailCustomResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailCustomResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertCount(2, $response->getAliases());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailCustomResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailCustomResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendNotFound(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailNotFound.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailCustomResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getCode());
        $this->assertSame("Transaction not found", $response->getMessage());
    }
}
