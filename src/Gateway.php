<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
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

class Gateway extends AbstractGateway
{
    public const P24_CHANNEL_CC = 1;

    public const P24_CHANNEL_BANK_TRANSFERS = 2;

    public const P24_CHANNEL_MANUAL_TRANSFER = 4;

    public const P24_CHANNEL_ALL_METHODS_24_7 = 16;

    public const P24_CHANNEL_USE_PREPAYMENT = 32;

    public const P24_CHANNEL_ALL = 63;

    public function getName(): string
    {
        return 'Przelewy24';
    }

    /**
     * @return string[]
     */
    public function getDefaultParameters(): array
    {
        return [
            'merchantId' => '',
            'posId' => '',
            'crc' => '',
            'reportKey' => '',
            'language' => 'en',
            'testMode' => false,
        ];
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value): self
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPosId()
    {
        return $this->getParameter('posId');
    }

    public function setPosId($value): self
    {
        return $this->setParameter('posId', $value);
    }

    public function getCrc()
    {
        return $this->getParameter('crc');
    }

    public function setCrc($value): self
    {
        return $this->setParameter('crc', $value);
    }

    public function getReportKey()
    {
        return $this->getParameter('reportKey');
    }

    public function setReportKey($value): self
    {
        return $this->setParameter('reportKey', $value);
    }

    public function getChannel()
    {
        return $this->getParameter('channel');
    }

    public function setChannel($value): self
    {
        return $this->setParameter('channel', $value);
    }

    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }

    public function setLanguage(string $value): self
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return AbstractRequest|TestAccessRequest
     */
    public function testAccess(): TestAccessRequest
    {
        return $this->createRequest(TestAccessRequest::class, []);
    }

    /**
     * @return AbstractRequest|MethodsRequest
     */
    public function methods(array $options = [
        'lang' => 'en',
    ]): MethodsRequest
    {
        return $this->createRequest(MethodsRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|PurchaseRequest
     */
    public function purchase(array $options = []): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|CompletePurchaseRequest
     */
    public function completePurchase(array $options = []): CompletePurchaseRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    /**
     * @return AbstractRequest|PurchaseInfoRequest
     */
    public function purchaseInfo(array $options = []): PurchaseInfoRequest
    {
        return $this->createRequest(PurchaseInfoRequest::class, $options);
    }

    /**
     * @return AbstractRequest|CardInfoRequest
     */
    public function cardInfo(array $options = []): CardInfoRequest
    {
        return $this->createRequest(CardInfoRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|RefundsRequest
     */
    public function refund(array $options = []): RefundsRequest
    {
        return $this->createRequest(RefundsRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|CardPayRequest
     */
    public function cardPay(array $options = []): CardPayRequest
    {
        return $this->createRequest(CardPayRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|CardChargeRequest
     */
    public function cardCharge(array $options = []): CardChargeRequest
    {
        return $this->createRequest(CardChargeRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|PurchaseOfflineRequest
     */
    public function purchaseOffline(array $options = []): PurchaseOfflineRequest
    {
        return $this->createRequest(PurchaseOfflineRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|BlikAliasesByEmailRequest
     */
    public function blikGetAliasesByEmail(array $options = []): BlikAliasesByEmailRequest
    {
        return $this->createRequest(BlikAliasesByEmailRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|BlikAliasesByEmailCustomRequest
     */
    public function blikGetAliasesByEmailCustom(array $options = []): BlikAliasesByEmailCustomRequest
    {
        return $this->createRequest(BlikAliasesByEmailCustomRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|BlikChargeByCodeRequest
     */
    public function blikChargeByCode(array $options = []): BlikChargeByCodeRequest
    {
        return $this->createRequest(BlikChargeByCodeRequest::class, $options);
    }

    /**
     * @param string[] $options
     *
     * @return AbstractRequest|BlikChargeByAliasRequest
     */
    public function blikChargeByAlias(array $options = []): BlikChargeByAliasRequest
    {
        return $this->createRequest(BlikChargeByAliasRequest::class, $options);
    }
}
