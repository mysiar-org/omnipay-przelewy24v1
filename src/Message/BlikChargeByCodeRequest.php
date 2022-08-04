<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class BlikChargeByCodeRequest extends AbstractRequest
{
    public function getBlikCode(): string
    {
        return $this->getParameter('blikCode');
    }

    public function setBlikCode(string $code): self
    {
        return $this->setParameter('blikCode', $code);
    }

    public function getAliasValue(): string
    {
        return $this->getParameter('aliasValue');
    }

    public function setAliasValue(string $aliasValue): self
    {
        return $this->setParameter('aliasValue', $aliasValue);
    }

    public function getAliasLabel(): string
    {
        return $this->getParameter('aliasLabel');
    }

    public function setAliasLabel(string $label): self
    {
        return $this->setParameter('aliasLabel', $label);
    }

    public function getData(): array
    {
        $this->validate('token', 'blikCode');

        return [
            'token' => $this->getToken(),
            'blikCode' => $this->getBlikCode(),
            'aliasLabel' => $this->getAliasLabel(),
            'aliasValue' => $this->getAliasValue(),
        ];
    }

    public function sendData($data): BlikChargeByCodeResponse
    {
        $httpResponse = $this->sendRequest('POST', 'paymentMethod/blik/chargeByCode', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new BlikChargeByCodeResponse($this, $responseData);
    }
}
