<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class CardInfoRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionId');

        return [
            'transactionId' => $this->getTransactionId(),
        ];
    }

    public function sendData($data): CardInfoResponse
    {
        $httpResponse = $this->sendRequest('GET', sprintf('/api/v1/card/info/%s', $data['transactionId']), []);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new CardInfoResponse($this, $responseData);
    }
}
