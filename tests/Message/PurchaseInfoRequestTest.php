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

        $this->assertSame(1234567890, $response->getInfo()['orderId']);
        $this->assertSame('20c62d6b-5ff0-46a0-97eb-eea0dd5b4a93', $response->getInfo()['sessionId']);
        $this->assertSame(1, $response->getInfo()['status']);
        $this->assertSame('12.34', $response->getInfo()['amount']);
        $this->assertSame('PLN', $response->getInfo()['currency']);
        $this->assertSame('202205161730', $response->getInfo()['date']);
        $this->assertSame('202205161730', $response->getInfo()['dateOfTransaction']);
        $this->assertSame(154, $response->getInfo()['paymentMethod']);
        $this->assertSame('transaction description', $response->getInfo()['description']);
        $this->assertSame(0, $response->getInfo()['batchId']);
        $this->assertSame("12", $response->getInfo()['fee']);
        $this->assertSame("P24-K12-B34-H56", $response->getInfo()['statement']);
        $this->assertSame('franek@dolas.com', $response->getInfo()['email']);
        $this->assertSame('Franek Dolas', $response->getInfo()['name']);
        $this->assertSame('Kościuszki 12', $response->getInfo()['address']);
        $this->assertSame('Kraków', $response->getInfo()['city']);
        $this->assertSame('30-611', $response->getInfo()['postcode']);
    }
}
