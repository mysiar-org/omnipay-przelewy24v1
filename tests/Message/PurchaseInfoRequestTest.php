<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Przelewy24\Message\PurchaseInfoRequest;
use Omnipay\Przelewy24\Message\PurchaseInfoResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PurchaseInfoRequestTest extends TestCase
{
    /**
     * @var PurchaseInfoRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new PurchaseInfoRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'sessionId' => '1234567890',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame('1234567890', $data['sessionId']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseInfoSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseInfoResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
        $this->assertCount(18, $response->getInfo());
    }
}
