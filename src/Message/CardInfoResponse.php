<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class CardInfoResponse extends AbstractResponse
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
}
