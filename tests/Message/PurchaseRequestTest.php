<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Common\CreditCard;
use Omnipay\Przelewy24\Gateway;
use Omnipay\Przelewy24\Message\PurchaseRequest;
use Omnipay\Przelewy24\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $card = new CreditCard([
            'email' => 'franek@dolas.com',
            'country' => 'PL',
        ]);

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantId' => '144354',
            'posId' => '144354',
            'crc' => '1287875353948',
            'sessionId' => '42',
            'amount' => '12.66',
            'currency' => 'PLN',
            'description' => 'Transaction Description',
            'email' => 'franek@dolas.com',
            'country' => 'PL',
            'returnUrl' => 'https://www.example.com/return',
            'notifyUrl' => 'https://www.example.com/notify',
            'card' => $card,
        ]);
    }

    public function channelProvider(): array
    {
        return [
            [Gateway::P24_CHANNEL_ALL],
            [null],
        ];
    }

    /**
     * @dataProvider channelProvider
     *
     * @param mixed $channel
     */
    public function testGetData($channel): void
    {
        $card = new CreditCard([
            'email' => 'franek@dolas.com',
            'country' => 'PL',
        ]);

        $this->request->initialize([
            'merchantId' => '144354',
            'posId' => '144354',
            'crc' => '1287875353948',
            'sessionId' => '42',
            'amount' => '12.66',
            'currency' => 'PLN',
            'description' => 'Transaction Description',
            'email' => 'franek@dolas.com',
            'country' => 'PL',
            'returnUrl' => 'https://www.example.com/return',
            'notifyUrl' => 'https://www.example.com/notify',
            'card' => $card,
            'channel' => $channel,
        ]);

        $data = $this->request->getData();

        $this->assertSame('42', $data['sessionId']);
        $this->assertSame(1266, $data['amount']);
        $this->assertSame('PLN', $data['currency']);
        $this->assertSame('Transaction Description', $data['description']);
        $this->assertSame('franek@dolas.com', $data['email']);
        $this->assertSame('PL', $data['country']);
        $this->assertSame('https://www.example.com/return', $data['urlReturn']);
        $this->assertSame('https://www.example.com/notify', $data['urlStatus']);
        $this->assertSame('15276e08cc84868619e1054ccf15d638337cae2bced6cb5a48bb799a3b52144692bce63408db85c84a6ca0461a999885', $data['sign']);

        if (null === $channel) {
            $this->assertCount(11, $data);
        } else {
            $this->assertSame($channel, $data['channel']);
            $this->assertCount(12, $data);
        }
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertEquals(
            'https://secure.przelewy24.pl/trnRequest/3F17389551-5285CA-F0B10D-A700D9B023',
            $response->getRedirectUrl()
        );
        $this->assertNull($response->getRedirectData());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(Response::HTTP_OK, $response->getCode());
        $this->assertSame('', $response->getMessage());
    }

    public function testSendSignatureFailure(): void
    {
        $this->setMockHttpResponse('PurchaseSignatureFailure.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getCode());
        $this->assertSame('Incorrect CRC value', $response->getMessage());
    }
}
