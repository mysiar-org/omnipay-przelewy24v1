<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getSessionId(): string
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId(string $value): self
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getData()
    {
        $this->validate('sessionId', 'amount', 'currency', 'transactionId');

        return [
            'merchantId' => $this->getMerchantId(),
            'posId' => $this->getPosId(),
            'sessionId' => $this->getSessionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'orderId' => $this->getTransactionId(),
            'sign' => $this->generateSignature(),
        ];
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('PUT', 'transaction/verify', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new CompletePurchaseResponse($this, $responseData, $this->getEndpoint());
    }

    private function generateSignature(): string
    {
        return hash(
            self::SIGN_ALGO,
            json_encode(
                [
                    'sessionId' => $this->getSessionId(),
                    'orderId' => (int) $this->getTransactionId(),
                    'amount' => $this->getAmount(),
                    'currency' => $this->getCurrency(),
                    'crc' => $this->getCrc(),
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );
    }
}
