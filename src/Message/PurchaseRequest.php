<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class PurchaseRequest extends AbstractRequest
{
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

    public function getCardNotifyUrl(): ?string
    {
        return $this->getParameter('cardNotifyUrl');
    }

    public function setCardNotifyUrl(string $value): self
    {
        return $this->setParameter('cardNotifyUrl', $value);
    }

    public function getName(): ?string
    {
        return $this->getParameter('name');
    }

    public function setName(string $value): self
    {
        return $this->setParameter('name', $value);
    }

    public function getAddress(): ?string
    {
        return $this->getParameter('address');
    }

    public function setAddress(string $value): self
    {
        return $this->setParameter('address', $value);
    }

    public function getPostcode(): ?string
    {
        return $this->getParameter('postcode');
    }

    public function setPostcode(string $value): self
    {
        return $this->setParameter('postcode', $value);
    }

    public function getCity(): ?string
    {
        return $this->getParameter('city');
    }

    public function setCity(string $value): self
    {
        return $this->setParameter('city', $value);
    }

    public function getPhone(): ?string
    {
        return $this->getParameter('phone');
    }

    public function setPhone(string $value): self
    {
        return $this->setParameter('phone', $value);
    }

    public function getTimeLimit(): ?int
    {
        return $this->getParameter('timeLimit');
    }

    public function setTimeLimit(int $value): self
    {
        return $this->setParameter('timeLimit', $value);
    }

    public function getWaitForResult(): ?bool
    {
        return $this->getParameter('waitForResult');
    }

    public function setWaitForResult(bool $value): self
    {
        return $this->setParameter('waitForResult', $value);
    }

    public function getRegulationAccept(): ?bool
    {
        return $this->getParameter('regulationAccept');
    }

    public function setRegulationAccept(bool $value): self
    {
        return $this->setParameter('regulationAccept', $value);
    }

    public function getShipping()
    {
        return $this->getParameter('shipping');
    }

    public function setShipping($value): self
    {
        return $this->setParameter('shipping', $value);
    }

    public function getTransactionLabel(): ?string
    {
        return $this->getParameter('transactionLabel');
    }

    public function setTransactionLabel(string $value): self
    {
        return $this->setParameter('transactionLabel', $value);
    }

    public function getEncoding(): ?string
    {
        return $this->getParameter('encoding');
    }

    public function setEncoding(string $value): self
    {
        return $this->setParameter('encoding', $value);
    }

    public function getMethodRefId(): ?string
    {
        return $this->getParameter('methodRefId');
    }

    public function setMethodRefId(string $value): self
    {
        return $this->setParameter('methodRefId', $value);
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

        $data = [
            'merchantId' => $this->getMerchantId(),
            'posId' => $this->getPosId(),
            'sessionId' => $this->getSessionId(),
            'amount' => $this->internalAmountValue(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'email' => $this->getEmail(),
            'client' => $this->getName(),
            'address' => $this->getAddress(),
            'zip' => $this->getPostcode(),
            'city' => $this->getCity(),
            'country' => $this->getCountry(),
            'phone' => $this->getPhone(),
            'language' => $this->getLanguage(),
            'method' => $this->getPaymentMethod() ? (int) $this->getPaymentMethod() : null,
            'urlReturn' => $this->getReturnUrl(),
            'urlStatus' => $this->getNotifyUrl(),
            'urlCardPaymentNotification' => $this->getCardNotifyUrl(),
            'timeLimit' => $this->getTimeLimit(),
            'channel' => $this->getChannel(),
            'waitForResult' => $this->getWaitForResult(),
            'regulationAccept' => $this->getRegulationAccept(),
            'shipping' => $this->getShipping() ? $this->internalAmountValue($this->getShipping()) : null,
            'transferLabel' => $this->getTransactionLabel(),
            'encoding' => $this->getEncoding(),
            'methodRefId' => $this->getMethodRefId(),
            // alias for credit card
            // TODO
            // 'cart' => ???
            // 'additional' => ???
            'sign' => $this->generateSignature(),
        ];

        return array_filter($data, function ($val) {
            return null !== $val;
        });
    }

    public function sendData($data): PurchaseResponse
    {
        $httpResponse = $this->sendRequest('POST', 'transaction/register', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $responseData);
    }

    private function generateSignature(): string
    {
        return hash(
            self::SIGN_ALGO,
            json_encode(
                [
                    'sessionId' => $this->getSessionId(),
                    'merchantId' => (int) $this->getMerchantId(),
                    'amount' => $this->internalAmountValue(),
                    'currency' => $this->getCurrency(),
                    'crc' => $this->getCrc(),
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );
    }
}
