<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class PurchaseInfoResponse extends AbstractResponse
{
    /**
     * @var string[]
     */
    private $info = [];

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        if (isset($data['data'])) {
            $this->info = $data['data'];
        }
    }

    /**
     * @return string[]
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    public function getCode(): int
    {
        if (isset($this->data['responseCode']) && isset($this->data['error'])) {
            return Response::HTTP_NOT_FOUND;
        }

        if (isset($this->data['code'])) {
            return $this->data['code'];
        }

        return self::HTTP_OK;
    }
}
