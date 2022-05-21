<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class RefundsRequest extends AbstractRequest
{
    public function setRequestId(string $requestId): self
    {
        return $this->setParameter('requestId', $requestId);
    }

    public function getRequestId(): string
    {
        return $this->getParameter('requestId');
    }

    public function setRefunds(array $refunds): self
    {
        return $this->setParameter('refunds', $refunds);
    }

    public function getRefunds(): array
    {
        return $this->getParameter('refunds');
    }

    public function setRefundsUuid(string $refundsUuid): self
    {
        return $this->setParameter('refundsUuid', $refundsUuid);
    }

    public function getRefundsUuid(): string
    {
        return $this->getParameter('refundsUuid');
    }

    public function setUrlStatus(?string $urlStatus): self
    {
        return $this->setParameter('urlStatus', $urlStatus);
    }

    public function getUrlStatus(): ?string
    {
        return $this->getParameter('urlStatus');
    }

    public function getData(): array
    {
        $this->validate('requestId', 'refunds', 'refundsUuid');
        $this->validateRefundsArray();
        $this->transformRefundsAmount();

        $data = [
            'requestId' => $this->getRequestId(),
            'refunds' => $this->getRefunds(),
            'refundsUuid' => $this->getRefundsUuid(),
            'urlStatus' => $this->getUrlStatus(),
        ];

        return array_filter($data);
    }

    /**
     * @param array $data
     *
     * @return MethodsResponse|ResponseInterface
     */
    public function sendData($data): ResponseInterface
    {
        $httpResponse = $this->sendRequest('GET', 'transaction/refund', $data);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new RefundsResponse($this, $responseData);
    }

    /**
     * @throws InvalidRequestException
     */
    private function validateRefundsArray(): void
    {
        $refunds = $this->getParameter('refunds');
        if (null === $refunds || empty($refunds)) {
            throw new InvalidRequestException('The parameter `refunds` can not be empty');
        }

        $requiredFields = ['orderId', 'sessionId', 'amount'];

        foreach ($refunds as $key => $refund) {
            if (! is_array($refund)) {
                throw new InvalidRequestException('Values in `refunds` need to be an array');
            }
            foreach ($requiredFields as $requiredField) {
                if (! array_key_exists($requiredField, $refund)) {
                    throw new InvalidRequestException("The {$requiredField} parameter is required in index {$key} of `refunds` array");
                }
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    private function transformRefundsAmount(): void
    {
        $refunds = $this->getParameter('refunds');
        foreach ($refunds as $key => $refund) {
            $refunds[$key]['amount'] = $this->internalAmountValue($refund['amount']);
        }

        $this->setParameter('refunds', $refunds);
    }
}
