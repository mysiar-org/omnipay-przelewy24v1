<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class CardPayRequest extends AbstractRequest
{
    public function getNumber(): string
    {
        return $this->getParameter('number');
    }

    public function setNumber(string $value): self
    {
        return $this->setParameter('number', $value);
    }

    public function getExpiry(): string
    {
        return $this->getParameter('expiry');
    }

    public function setExpiry(string $value): self
    {
        return $this->setParameter('expiry', $value);
    }

    public function getCvv(): string
    {
        return $this->getParameter('cvv');
    }

    public function setCvv(string $value): self
    {
        return $this->setParameter('cvv', $value);
    }

    public function getName(): string
    {
        return $this->getParameter('name');
    }

    public function setName(string $value): self
    {
        return $this->setParameter('name', $value);
    }

    public function getData(): array
    {
        $this->validate('transactionId', 'number', 'expiry', 'cvv', 'name');

        return [
            'transactionToken' => $this->getTransactionId(),
            'cardNumber' => $this->getNumber(),
            'cardDate' => $this->getExpiry(),
            'cvv' => $this->getCvv(),
            'clientName' => $this->getName(),
        ];
    }

    public function sendData($data): CardPayResponse
    {
        $httpResponse = $this->sendRequest('POST', 'card/pay', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new CardPayResponse($this, $responseData);
    }
}
