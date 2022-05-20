<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class CardChargeRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('token');

        return [
            'token' => $this->getToken(),
        ];
    }

    public function sendData($data): CardChargeResponse
    {
        $httpResponse = $this->sendRequest('POST', 'card/charge', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new CardChargeResponse($this, $responseData);
    }
}
