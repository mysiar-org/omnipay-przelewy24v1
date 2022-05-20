<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CardPayResponse extends AbstractResponse
{
    /**
     * @var null|string
     */
    private $transactionId = null;

    /**
     * @var null
     */
    private $redirectUrl = null;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        if (isset($data['data'])) {
            if (isset($data['data']['orderId'])) {
                $this->transactionId = (string) $data['data']['orderId'];
            }
            if (isset($data['data']['redirectUrl'])) {
                $this->redirectUrl = $data['data']['redirectUrl'];
            }
        }
    }

    public function getCode(): int
    {
        if (
            isset($this->data['code'])
            && 0 === $this->data['code']
            && isset($this->data['error'])
            && strlen($this->data['error']) > 0
        ) {
            return Response::HTTP_CONFLICT;
        }

        if (isset($this->data['code'])) {
            return $this->data['code'];
        }

        return Response::HTTP_OK;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function isRedirect(): bool
    {
        return $this->isSuccessful() && null !== $this->getRedirectUrl();
    }
}
