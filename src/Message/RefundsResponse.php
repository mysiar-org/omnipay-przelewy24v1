<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class RefundsResponse extends AbstractResponse
{
    private $refunds;

    private $responseCode;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->refunds = $data['data'] ?? null;
        if (isset($data['responseCode'])) {
            $this->responseCode = (int) $data['responseCode'];
        }
    }

    public function getRefunds(): array
    {
        return $this->refunds;
    }

    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    public function getCode(): int
    {
        if (! is_null($this->getResponseCode())) {
            return $this->getResponseCode();
        }

        return parent::getCode();
    }
}
