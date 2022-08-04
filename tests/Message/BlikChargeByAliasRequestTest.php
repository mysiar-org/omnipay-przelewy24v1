<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\BlikChargeByAliasRequest;
use Omnipay\Przelewy24\Message\BlikChargeByAliasResponse;
use Omnipay\Przelewy24\Message\BlikChargeByCodeRequest;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class BlikChargeByAliasRequestTest extends TestCase
{
    /**
     * @var BlikChargeByCodeRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new BlikChargeByAliasRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'token' => 'test1',
            'type' => 'alias',
            'aliasLabel' => 'test3',
            'aliasValue' => 'test4',
            'alternativeKey' => 'test5',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('test1', $data['token']);
        $this->assertSame('alias', $data['type']);
        $this->assertSame('test3', $data['aliasLabel']);
        $this->assertSame('test4', $data['aliasValue']);
        $this->assertSame('test5', $data['alternativeKey']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeSuccess.txt');
        /** @var BlikChargeByAliasResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByAliasResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame("test123", $response->getOrderId());
        $this->assertSame("success", $response->getChargeMessage());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByAliasResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByAliasResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testUndefinedError(): void
    {
        $this->setMockHttpResponse('BlikChargeByCodeUndefinedError.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByAliasResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getCode());
        $this->assertSame("Undefined error", $response->getMessage());
    }

    public function testAlternativeKeys(): void
    {
        $this->setMockHttpResponse('BlikChargeByAliasAlternativeKeysFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikChargeByAliasResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_CONFLICT, $response->getCode());
        $this->assertIsArray($response->getMessage());
        $this->assertArrayHasKey('alternativeKeys', $response->getMessage());
        $this->assertCount(2, $response->getMessage()['alternativeKeys']);
    }
}
