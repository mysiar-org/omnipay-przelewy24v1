<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\CardChargeRequest;
use Omnipay\Przelewy24\Message\CardChargeResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CardChargeRequestTest extends TestCase
{
    /**
     * @var CardChargeRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CardChargeRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $this->setMockHttpResponse('CardChargeSuccess.txt');
        /** @var CardChargeResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(CardChargeResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('1234567890', $response->getTransactionId());
    }

    public function testSendAuthFailure(): void
    {
        $this->setMockHttpResponse('CardChargeAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardChargeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendInvalidDataFailure(): void
    {
        $this->setMockHttpResponse('CardChargeInvalidDataFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardChargeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }
}
