<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class PurchaseOfflineRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('token');

        return [
            'token' => $this->getToken(),
        ];
    }

    public function sendData($data): PurchaseOfflineResponse
    {
        $httpResponse = $this->sendRequest('POST', 'transaction/registerOffline', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseOfflineResponse($this, $responseData);
    }
}
