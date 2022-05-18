<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class PurchaseInfoRequest extends AbstractRequest
{
    public function getSessionId(): string
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId(string $value): self
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getData(): array
    {
        $this->validate('sessionId');

        return [
            'sessionId' => $this->getSessionId(),
        ];
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', sprintf('transaction/by/sessionId/%s', $data['sessionId']), []);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseInfoResponse($this, $responseData);
    }
}
