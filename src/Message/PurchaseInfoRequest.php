<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\ResponseInterface;

class PurchaseInfoRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('transactionId');

        return [
            'transactionId' => $this->getTransactionId(),
        ];
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', sprintf('transaction/by/sessionId/%s', $data['transactionId']), []);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseInfoResponse($this, $responseData);
    }
}
