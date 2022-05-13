<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24;

use Omnipay\Common\AbstractGateway;
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

    public function testAccess()
    {
        return $this->createRequest(TestAccessRequest::class, []);
    }
}
