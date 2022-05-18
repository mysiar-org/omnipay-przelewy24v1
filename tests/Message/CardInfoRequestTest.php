<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\CardInfoRequest;
use Omnipay\Przelewy24\Message\CardInfoResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CardInfoRequestTest extends TestCase
{
    /**
     * @var CardInfoRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CardInfoRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'transactionId' => 1234567890,
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame(1234567890, $data['transactionId']);
    }

    public function testSendInvalidDataFailure()
    {
        $this->setMockHttpResponse('CardInfoInvalidDataFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardInfoResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Wrong input data', $response->getMessage());
    }

    public function testSendAuthFailure()
    {
        $this->setMockHttpResponse('CardInfoAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardInfoResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendNotFoundFailure()
    {
        $this->setMockHttpResponse('CardInfoNotFoundFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardInfoResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getCode());
        $this->assertSame('Transaction not exists', $response->getMessage());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CardInfoSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CardInfoResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());

        $this->assertSame('ref-id', $response->getInfo()['refId']);
        $this->assertSame(0, $response->getInfo()['bin']);
        $this->assertSame('xxxx-xxxx-xxxx-1234', $response->getInfo()['mask']);
        $this->assertSame('VISA', $response->getInfo()['brand']);
        $this->assertSame('202505', $response->getInfo()['expiry']);
        $this->assertSame('e0dfeab6-39c2-42cb-b96e-fb0d6d48d503', $response->getInfo()['hash']);
    }
}
