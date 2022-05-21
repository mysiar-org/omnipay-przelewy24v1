<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\CardPayRequest;
use Omnipay\Przelewy24\Message\CardPayResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CardPayRequestTest extends TestCase
{
    /**
     * @var CardPayRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CardPayRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'transactionId' => '29fa01f8-6bb8-4187-9fb0-ec6e1a62a731',
            'number' => '9010100052000004',
            'expiry' => '0535',
            'cvv' => '1234',
            'name' => 'Franek Dolas',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('29fa01f8-6bb8-4187-9fb0-ec6e1a62a731', $data['transactionToken']);
        $this->assertSame('9010100052000004', $data['cardNumber']);
        $this->assertSame('0535', $data['cardDate']);
        $this->assertSame('1234', $data['cvv']);
        $this->assertSame('Franek Dolas', $data['clientName']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CardPaySuccess.txt');
        /** @var CardPayResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(CardPayResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('1234567890', $response->getTransactionId());
        $this->assertSame('https://this-is-redirect-url.com', $response->getRedirectUrl());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('CardPayAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardPayResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendInvalidDataFailure(): void
    {
        $this->setMockHttpResponse('CardPayInvalidDataFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardPayResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }

    public function testSendUnableToPayFailure(): void
    {
        $this->setMockHttpResponse('CardPayUnableToPayFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardPayResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_CONFLICT, $response->getCode());
        $this->assertSame('Unable to make payment.', $response->getMessage());
    }
}
