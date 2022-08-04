<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class BlikChargeByAliasRequest extends AbstractRequest
{
    public function getAlternativeKey(): string
    {
        return $this->getParameter('alternativeKey');
    }

    public function setAlternativeKey(string $type): self
    {
        return $this->setParameter('alternativeKey', $type);
    }

    public function getType(): string
    {
        return $this->getParameter('type');
    }

    public function setType(string $type): self
    {
        return $this->setParameter('type', $type);
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
        $this->validate('token', 'type');

        if (! in_array($this->getType(), ['alias', 'alternativeKey'], true)) {
            throw new InvalidRequestException(
                "The `type` parameter is required, and allows only 'alias', 'alternativeKey' values"
            );
        }

        if ($this->getType() === 'alternativeKey') {
            $this->validate('alternativeKey');
        }

        return [
            'token' => $this->getToken(),
            'type' => $this->getType(),
            'aliasLabel' => $this->getAliasLabel(),
            'aliasValue' => $this->getAliasValue(),
            'alternativeKey' => $this->getAlternativeKey(),
        ];
    }

    public function sendData($data): BlikChargeByAliasResponse
    {
        $httpResponse = $this->sendRequest('POST', 'paymentMethod/blik/chargeByAlias', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new BlikChargeByAliasResponse($this, $responseData);
    }
}
