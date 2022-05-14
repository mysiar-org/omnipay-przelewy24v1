<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

class PurchaseResponse extends AbstractResponse
{
    public function getToken()
    {
        return trim($this->data['data']['token']);
    }

    public function getRedirectUrl()
    {
        return $this->request->getRedirectEndpoint() . 'trnRequest/' . $this->getToken();
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }
}
