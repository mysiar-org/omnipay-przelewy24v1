<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getAmount(): int
    {
        return $this->getParameter('amount');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAmount($value): self
    {
        return $this->setParameter('amount', $value);
    }

    public function getSessionId(): string
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId(string $value): self
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getEmail(): string
    {
        return $this->getParameter('email');
    }

    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
    }

    public function getCountry(): string
    {
        return $this->getParameter('country');
    }

    public function setCountry(string $value): self
    {
        return $this->setParameter('country', $value);
    }

    public function getData()
    {
        $this->validate(
            'sessionId',
            'amount',
            'currency',
            'description',
            'email',
            'country',
            'returnUrl',
            'notifyUrl'
        );

        return [
            'merchantId' => $this->getMerchantId(),
            'posId' => $this->getPosId(),
            'sessionId' => $this->getSessionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'email' => $this->getEmail(),
            'country' => $this->getCountry(),
            'language' => $this->getLanguage(),
            'urlReturn' => $this->getReturnUrl(),
            'urlStatus' => $this->getNotifyUrl(),
            'sign' => $this->generateSignature(),
        ];
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('POST', 'transaction/register', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $responseData, $this->getEndpoint());
    }

    private function generateSignature(): string
    {
        return hash(
            self::SIGN_ALGO,
            json_encode(
                [
                    'sessionId' => $this->getSessionId(),
                    'merchantId' => (int) $this->getMerchantId(),
                    'amount' => (int) $this->getAmount(),
                    'currency' => $this->getCurrency(),
                    'crc' => $this->getCrc(),
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );
    }
}
