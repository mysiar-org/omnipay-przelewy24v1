<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class BlikAliasesByEmailCustomResponse extends AbstractResponse
{
    /**
     * @var string[]
     */
    private $aliases = [];

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        if (isset($data['data'])) {
            $this->aliases = $data['data'];
        }
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }
}
