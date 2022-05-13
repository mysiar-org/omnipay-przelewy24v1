<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $liveEndpoint = 'https://secure.przelewy24.pl/api/v1/';

    protected $testEndpoint = 'https://sandbox.przelewy24.pl/api/v1/';

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

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @param mixed $data
     */
    protected function sendRequest(string $method, string $endpoint, $data): ResponseInterface
    {
        return $this->httpClient->request(
            $method,
            $this->getEndpoint() . $endpoint,
            [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf(
                    'Basic %s',
                    base64_encode(sprintf('%s:%s', $this->getMerchantId(), $this->getReportKey()))
                ),
            ],
            empty($data) ? null : json_encode($data)
        );
    }
}
