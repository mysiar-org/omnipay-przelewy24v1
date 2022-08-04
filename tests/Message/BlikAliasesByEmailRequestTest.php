<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\BlikAliasesByEmailRequest;
use Omnipay\Przelewy24\Message\BlikAliasesByEmailResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class BlikAliasesByEmailRequestTest extends TestCase
{
    /**
     * @var BlikAliasesByEmailRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new BlikAliasesByEmailRequest($this->getHttpClient(), $this->getHttpRequest());
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
        /** @var BlikAliasesByEmailResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertCount(2, $response->getAliases());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendNotFound(): void
    {
        $this->setMockHttpResponse('BlikAliasesByEmailNotFound.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(BlikAliasesByEmailResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getCode());
        $this->assertSame("Transaction not found", $response->getMessage());
    }
}
