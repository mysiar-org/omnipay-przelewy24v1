<?php

declare(strict_types=1);

namespace Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Przelewy24\Message\RefundsRequest;
use Omnipay\Przelewy24\Message\RefundsResponse;
use Omnipay\Tests\TestCase;

class RefundsRequestTest extends TestCase
{
    private const REQUEST_DATA = [
        'requestId' => '123',
        'refunds' => [
            [
                'orderId' => '123',
                'sessionId' => '123',
                'amount' => '123',
            ],
        ],
        'refundsUuid' => '321',
    ];

    /**
     * @var RefundsRequest
     */
    private $request;

    public function setUp(): void
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
    ): void {
        $data = [];

        if (null !== $requestId) {
            $data['requestId'] = $requestId;
        }

        $data['refunds'] = $refunds;

        if (null !== $refundsUuid) {
            $data['refundsUuid'] = $refundsUuid;
        }

        if (null !== $urlStatus) {
            $data['urlStatus'] = $urlStatus;
        }

        $this->request->initialize($data);

        if (null !== $expectedExceptionMessage) {
            $this->expectException(InvalidRequestException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $data = $this->request->getData();
        $this->assertSame($requestId, $data['requestId']);
        foreach ($refunds as $key => $refund) {
            $this->assertSame($refund['orderId'], $data['refunds'][$key]['orderId']);
            $this->assertSame($refund['sessionId'], $data['refunds'][$key]['sessionId']);
            $this->assertNotSame($refund['amount'], $data['refunds'][$key]['amount']);
        }
        $this->assertSame($refundsUuid, $data['refundsUuid']);
        $this->assertSame($urlStatus, $data['urlStatus']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RefundsRequestSuccess.txt');
        $this->request->initialize(self::REQUEST_DATA);
        $response = $this->request->send();

        $this->assertInstanceOf(RefundsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getRefunds());
        $this->assertNotNull($response->getResponseCode());
        $this->assertSame(1, count($response->getRefunds()));
    }

    /**
     * @dataProvider failureResponseDataProvider
     */
    public function testSendFailure(string $fileName): void
    {
        $this->setMockHttpResponse($fileName);
        $this->request->initialize(self::REQUEST_DATA);
        $response = $this->request->send();

        $this->assertInstanceOf(RefundsResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getMessage());
        $this->assertNull($response->getResponseCode());
    }

    public function failureResponseDataProvider(): array
    {
        return [
            ['RefundsRequestFailure.txt'],
            ['RefundsRequestFailedConflict.txt'],
            ['RefundsRequestFailedAuthorization.txt'],
        ];
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
