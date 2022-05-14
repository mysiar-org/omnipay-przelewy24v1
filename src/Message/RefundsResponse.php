<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class RefundsResponse extends AbstractResponse
{
    private $refunds;

    private $responseCode;

    public function __construct(RequestInterface $request, $data, $endpoint)
    {
        parent::__construct($request, $data, $endpoint);
        $this->refunds = $data['data'] ?? null;
        $this->responseCode = $data['responseCode'] ?? null;
    }

    public function getRefunds(): array
    {
        return $this->refunds;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

}
