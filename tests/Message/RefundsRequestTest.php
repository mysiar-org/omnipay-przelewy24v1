<?php

namespace Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Przelewy24\Message\RefundsRequest;
use Omnipay\Tests\TestCase;

/**
 * @group unit
 */
class RefundsRequestTest extends TestCase
{
    /**
     * @var RefundsRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new RefundsRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @dataProvider refundDataProvider
     */
    public function testGetData(
        ?string $requestId,
        array $refunds,
        ?string $refundsUuid,
        ?string $urlStatus,
        ?string $expectedExceptionMessage
    ) {
        $data = [];

        if (! is_null($requestId)) {
            $data['requestId'] = $requestId;
        }

        $data['refunds'] = $refunds;

        if (! is_null($refundsUuid)) {
            $data['refundsUuid'] = $refundsUuid;
        }

        if (! is_null($urlStatus)) {
            $data['urlStatus'] = $urlStatus;
        }

        $this->request->initialize($data);

        if (! is_null($expectedExceptionMessage)) {
            $this->expectException(InvalidRequestException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $data = $this->request->getData();
        $this->assertSame($requestId, $data['requestId']);
        $this->assertSame($refunds, $data['refunds']);
        $this->assertSame($refundsUuid, $data['refundsUuid']);
        $this->assertSame($urlStatus, $data['urlStatus']);
    }

    public function refundDataProvider(): array
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
                'expectedExceptionMessage' => null,
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
                'expectedExceptionMessage' => 'The sessionId parameter is required in index 0 of `refunds` array',
            ],
            [
                'requestId' => 'gsa',
                'refunds' => [
                    [
                        'amount' => 'dsa',
                    ],
                ],
                'refundsUuid' => '512',
                'urlStatus' => '15215215',
                'expectedExceptionMessage' => 'The orderId parameter is required in index 0 of `refunds` array',
            ],
            [
                'requestId' => 'gsa',
                'refunds' => [],
                'refundsUuid' => '512',
                'urlStatus' => '15215215',
                'expectedExceptionMessage' => 'The parameter `refunds` can not be empty',
            ],
            [
                'requestId' => 'gsa',
                'refunds' => [1, 23],
                'refundsUuid' => '512',
                'urlStatus' => '15215215',
                'expectedExceptionMessage' => 'Values in `refunds` need to be an array',
            ],
            [
                'requestId' => null,
                'refunds' => [1, 23],
                'refundsUuid' => null,
                'urlStatus' => '',
                'expectedExceptionMessage' => 'The requestId parameter is required',
            ],
            [
                'requestId' => '123',
                'refunds' => [
                    [
                        'orderId' => '123',
                        'sessionId' => '123',
                        'amount' => '123',
                    ],
                ],
                'refundsUuid' => null,
                'urlStatus' => '',
                'expectedExceptionMessage' => 'The refundsUuid parameter is required',
            ],
            [
                'requestId' => '123',
                'refunds' => [
                    [
                        'orderId' => '123',
                        'sessionId' => '123',
                        'amount' => '123',
                    ],
                    [
                        'sessionId' => '123',
                        'amount' => '123',
                    ],
                ],
                'refundsUuid' => '123',
                'urlStatus' => null,
                'expectedExceptionMessage' => 'The orderId parameter is required in index 1 of `refunds` array',
            ],
        ];
    }
}
