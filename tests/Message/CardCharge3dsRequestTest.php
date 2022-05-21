<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\CardCharge3dsRequest;
use Omnipay\Przelewy24\Message\CardCharge3dsResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CardCharge3dsRequestTest extends TestCase
{
    /**
     * @var CardCharge3dsRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CardCharge3dsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            "token" => "29fa01f8-6bb8-4187-9fb0-ec6e1a62a731",
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('29fa01f8-6bb8-4187-9fb0-ec6e1a62a731', $data['token']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CardCharge3dsSuccess.txt');
        /** @var CardCharge3dsResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(CardCharge3dsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('1234567890123', $response->getTransactionId());
    }

    public function testSendAuthFailure()
    {
        $this->setMockHttpResponse('CardCharge3dsAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardCharge3dsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendInvalidDataFailure()
    {
        $this->setMockHttpResponse('CardCharge3dsInvalidDataFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardCharge3dsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Invalid input data', $response->getMessage());
    }
}
