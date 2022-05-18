<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class PurchaseResponse extends AbstractResponse
{
    public function getToken(): string
    {
        if (isset($this->data['data'])) {
            return trim($this->data['data']['token']);
        }

        return '';
    }

    public function getRedirectUrl(): string
    {
        return $this->request->getRedirectEndpoint() . 'trnRequest/' . $this->getToken();
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function isRedirect(): bool
    {
        return $this->isSuccessful() && null !== $this->getToken();
    }

    public function getRedirectData()
    {
        return null;
    }
}
