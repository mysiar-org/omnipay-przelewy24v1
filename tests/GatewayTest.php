<?php

declare(strict_types=1);

use Omnipay\Przelewy24\Gateway;
use Omnipay\Przelewy24\Message\BlikAliasesByEmailCustomRequest;
use Omnipay\Przelewy24\Message\BlikAliasesByEmailRequest;
use Omnipay\Przelewy24\Message\BlikChargeByAliasRequest;
use Omnipay\Przelewy24\Message\BlikChargeByCodeRequest;
use Omnipay\Przelewy24\Message\CardChargeRequest;
use Omnipay\Przelewy24\Message\CardInfoRequest;
use Omnipay\Przelewy24\Message\CardPayRequest;
use Omnipay\Przelewy24\Message\CompletePurchaseRequest;
use Omnipay\Przelewy24\Message\MethodsRequest;
use Omnipay\Przelewy24\Message\PurchaseInfoRequest;
use Omnipay\Przelewy24\Message\PurchaseOfflineRequest;
use Omnipay\Przelewy24\Message\PurchaseRequest;
use Omnipay\Przelewy24\Message\RefundsRequest;
use Omnipay\Przelewy24\Message\TestAccessRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @test
     */
    public function itShouldReturnTheName(): void
    {
        $this->assertSame('Przelewy24', $this->gateway->getName());
    }

    /**
     * @test
     */
    public function itShouldReturnDefaultParameters(): void
    {
        $defaultParameters = $this->gateway->getDefaultParameters();
        $this->assertSame('', $defaultParameters['merchantId']);
        $this->assertSame('', $defaultParameters['posId']);
        $this->assertSame('', $defaultParameters['crc']);
        $this->assertSame('', $defaultParameters['reportKey']);
        $this->assertSame('en', $defaultParameters['language']);
        $this->assertFalse($defaultParameters['testMode']);
    }

    /**
     * @test
     */
    public function itShouldSetAndGetMerchantId(): void
    {
        $merchantId = '42';

        $this->gateway->setMerchantId($merchantId);
        $this->assertSame($merchantId, $this->gateway->getMerchantId());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetPosId(): void
    {
        $posId = '13';

        $this->gateway->setPosId($posId);
        $this->assertSame($posId, $this->gateway->getPosId());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetCrc(): void
    {
        $crc = '1288348798';

        $this->gateway->setCrc($crc);
        $this->assertSame($crc, $this->gateway->getCrc());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetReportKey(): void
    {
        $crc = '6c2f6d86-3091-4291-b1e4-2568492605e4';

        $this->gateway->setReportKey($crc);
        $this->assertSame($crc, $this->gateway->getReportKey());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetChannel(): void
    {
        $channel = 32;

        $this->gateway->setChannel($channel);
        $this->assertSame($channel, $this->gateway->getChannel());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetLanguage(): void
    {
        $language = 'pl';
        $this->gateway->setLanguage($language);
        $this->assertSame($language, $this->gateway->getLanguage());
    }

    /**
     * @test
     */
    public function itShouldCreateATestAccess(): void
    {
        $request = $this->gateway->testAccess();
        $this->assertInstanceOf(TestAccessRequest::class, $request);
    }

    /**
     * @test
     */
    public function itShouldCreateAMethods(): void
    {
        $request = $this->gateway->methods();
        $this->assertInstanceOf(MethodsRequest::class, $request);
        $this->assertSame('en', $request->getLang());
    }

    /**
     * @test
     */
    public function itShouldCreateAPurchase(): void
    {
        $request = $this->gateway->purchase([
            'amount' => '10.00',
        ]);
        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetAmountOnPurchase(): void
    {
        $request = $this->gateway->purchase([
            'amount' => '1000',
        ]);
        $request->setAmount('10.00');
        $this->assertSame('10.00', $request->getAmount());
    }

    /**
     * @test
     */
    public function itShouldSetAndGetShippingOnPurchase(): void
    {
        $request = $this->gateway->purchase([
            'amount' => '1000',
        ]);
        $request->setShipping('12.34');
        $this->assertSame('12.34', $request->getShipping());
    }

    /**
     * @test
     */
    public function itShouldCreateACompletePurchase(): void
    {
        $request = $this->gateway->completePurchase([
            'amount' => '10.00',
        ]);
        $this->assertInstanceOf(CompletePurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    /**
     * @test
     * @dataProvider refund_data_provider
     */
    public function itShouldCreateARefund(
        string $requestId,
        array $refunds,
        string $refundsUuid,
        ?string $urlStatus
    ): void {
        $data = [
            'requestId' => $requestId,
            'refunds' => $refunds,
            'refundsUuid' => $refundsUuid,
            'urlStatus' => $urlStatus,
        ];

        $request = $this->gateway->refund($data);

        $this->assertInstanceOf(RefundsRequest::class, $request);

        $this->assertSame($requestId, $request->getRequestId());
        $this->assertSame($refunds, $request->getRefunds());
        $this->assertSame($refundsUuid, $request->getRefundsUuid());
        $this->assertSame($urlStatus, $request->getUrlStatus());
    }

    /**
     * @test
     */
    public function itShouldCreateAPurchaseInfo(): void
    {
        $request = $this->gateway->purchaseInfo([
            'sessionId' => 'session-id',
        ]);
        $this->assertInstanceOf(PurchaseInfoRequest::class, $request);
        $this->assertSame('session-id', $request->getSessionId());
    }

    /**
     * @test
     */
    public function itShouldCreateCardInfo(): void
    {
        $request = $this->gateway->cardInfo([
            'transactionId' => 'transaction-id',
        ]);
        $this->assertInstanceOf(CardInfoRequest::class, $request);
        $this->assertSame('transaction-id', $request->getTransactionId());
    }

    /**
     * @test
     */
    public function itShouldCreateCardPay(): void
    {
        $request = $this->gateway->cardPay([]);
        $this->assertInstanceOf(CardPayRequest::class, $request);
    }

    /**
     * @test
     */
    public function itShouldCreateCardCharge(): void
    {
        $request = $this->gateway->cardCharge([]);
        $this->assertInstanceOf(CardChargeRequest::class, $request);
    }

    /**
     * @test
     */
    public function itShouldCreatePurchaseOffline(): void
    {
        $request = $this->gateway->purchaseOffline([
            'token' => 'token-abc',
        ]);
        $this->assertInstanceOf(PurchaseOfflineRequest::class, $request);
        $this->assertSame('token-abc', $request->getToken());
    }

    /**
     * @test
     */
    public function itShouldCreateBlikAliasesByEmail(): void
    {
        $request = $this->gateway->blikGetAliasesByEmail([
            'email' => 'test',
        ]);
        $this->assertInstanceOf(BlikAliasesByEmailRequest::class, $request);
        $this->assertSame('test', $request->getEmail());
    }

    /**
     * @test
     */
    public function itShouldCreateBlikAliasesByEmailCustom(): void
    {
        $request = $this->gateway->blikGetAliasesByEmailCustom([
            'email' => 'test',
        ]);
        $this->assertInstanceOf(BlikAliasesByEmailCustomRequest::class, $request);
        $this->assertSame('test', $request->getEmail());
    }

    /**
     * @test
     */
    public function itShouldCreateBlikChargeByCode(): void
    {
        $request = $this->gateway->blikChargeByCode([
            'token' => 'test',
            'blikCode' => '123456',
        ]);
        $this->assertInstanceOf(BlikChargeByCodeRequest::class, $request);
        $this->assertSame('test', $request->getToken());
        $this->assertSame('123456', $request->getBlikCode());
    }

    /**
     * @test
     */
    public function itShouldCreateBlikChargeByAlias(): void
    {
        $request = $this->gateway->blikChargeByAlias([
            'token' => 'test',
            'type' => 'alias',
            'aliasLabel' => 'test3',
            'aliasValue' => 'test4',
            'alternativeKey' => 'test5',
        ]);
        $this->assertInstanceOf(BlikChargeByAliasRequest::class, $request);
        $this->assertSame('test', $request->getToken());
        $this->assertSame('alias', $request->getType());
        $this->assertSame('test3', $request->getAliasLabel());
        $this->assertSame('test4', $request->getAliasValue());
        $this->assertSame('test5', $request->getAlternativeKey());
    }

    public function refund_data_provider(): array
    {
        return [
            [
                'requestId' => '123',
                'refunds' => [
                    [
                        'orderId' => '123',
                        'sessionId' => '123',
                        'amount' => '123',
                    ],
                ],
                'refundsUuid' => '321',
                'urlStatus' => 'status',
            ],
            [
                'requestId' => 'gsa',
                'refunds' => [
                    [
                        'orderId' => 'dfsa',
                        'amount' => 'dsa',
                    ],
                ],
                'refundsUuid' => '512',
                'urlStatus' => '15215215',
            ],
        ];
    }
}
