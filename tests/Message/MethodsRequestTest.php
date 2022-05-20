<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\MethodsRequest;
use Omnipay\Przelewy24\Message\MethodsResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class MethodsRequestTest extends TestCase
{
    /**
     * @var MethodsRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new MethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            "lang" => "en",
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('en', $data['lang']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('MethodsSuccess.txt');
        /** @var MethodsResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(MethodsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());
        $this->assertCount(43, $response->getMethods());

        $method = $response->getMethods()[0];

        $this->assertSame('Przelew z BPH', $method['name']);
        $this->assertSame(35, $method['id']);
        $this->assertSame('FastTransfers', $method['group']);
        $this->assertSame('FastTransfers', $method['subgroup']);
        $this->assertSame(true, $method['status']);
        $this->assertSame('https://static.przelewy24.pl/payment-form-logo/svg/35', $method['imgUrl']);
        $this->assertSame(
            'https://static.przelewy24.pl/payment-form-logo/svg/mobile/35',
            $method['mobileImgUrl']
        );
        $this->assertSame(true, $method['mobile']);
        $this->assertCount(3, $method['availabilityHours']);
        $this->assertSame('08-20', $method['availabilityHours']['mondayToFriday']);
        $this->assertSame('unavailable', $method['availabilityHours']['saturday']);
        $this->assertSame('unavailable', $method['availabilityHours']['sunday']);
    }

    public function testSendAuthFailure()
    {
        $this->setMockHttpResponse('MethodsAuthFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(MethodsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getCode());
        $this->assertSame('Incorrect authentication', $response->getMessage());
    }

    public function testSendNotFoundFailure()
    {
        $this->setMockHttpResponse('MethodsNotFoundFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(MethodsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getCode());
        $this->assertSame('Payment methods not found', $response->getMessage());
    }
}
