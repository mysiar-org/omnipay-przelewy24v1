<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\TestAccessRequest;
use Omnipay\Przelewy24\Message\TestAccessResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class TestAccessRequestTest extends TestCase
{
    /**
     * @var TestAccessRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new TestAccessRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame([], $data);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('TestAccessSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(TestAccessResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('TestAccessAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(TestAccessResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendInvalidDataFailure(): void
    {
        $this->setMockHttpResponse('TestAccessInvalidDataFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(TestAccessResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }
}
