<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\BlikChargeByCodeRequest;
use Omnipay\Przelewy24\Message\BlikChargeByCodeResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class BlikChargeByCodeRequestTest extends TestCase
{
    /**
     * @var BlikChargeByCodeRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new BlikChargeByCodeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            "token" => "test1",
            "blikCode" => "test2",
            "aliasValue" => "test3",
            "aliasLabel" => "test4",
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('test1', $data['token']);
        $this->assertSame('test2', $data['blikCode']);
        $this->assertSame('test3', $data['aliasValue']);
        $this->assertSame('test4', $data['aliasLabel']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeSuccess.txt');
        /** @var BlikChargeByCodeResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByCodeResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame("test123", $response->getOrderId());
        $this->assertSame("success", $response->getChargeMessage());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByCodeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByCodeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testUndefinedError(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeUndefinedError.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByCodeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getCode());
        $this->assertSame("Undefined error", $response->getMessage());
    }
}
