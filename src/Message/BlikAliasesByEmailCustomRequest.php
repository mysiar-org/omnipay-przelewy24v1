<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class BlikAliasesByEmailCustomRequest extends AbstractRequest
{
    public function getEmail(): string
    {
        return $this->getParameter('email');
    }

    public function setEmail(string $email): self
    {
        return $this->setParameter('email', $email);
    }

    public function getData(): array
    {
        $this->validate('email');

        return [
            'email' => $this->getEmail(),
        ];
    }

    public function sendData($data): BlikAliasesByEmailCustomResponse
    {
        $email = $data['email'];
        $httpResponse = $this->sendRequest('GET', sprintf('paymentMethod/blik/getAliasesByEmail/%s/custom', $email), []);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new BlikAliasesByEmailCustomResponse($this, $responseData);
    }
}
