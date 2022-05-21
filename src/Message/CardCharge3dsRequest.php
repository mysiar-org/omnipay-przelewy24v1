<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class CardCharge3dsRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('token');

        return [
            'token' => $this->getToken(),
        ];
    }

    public function sendData($data): CardCharge3dsResponse
    {
        $httpResponse = $this->sendRequest('POST', 'card/chargeWith3ds', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new CardCharge3dsResponse($this, $responseData);
    }
}
