<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class TestAccessResponse extends AbstractResponse
{
    /**
     * @var bool
     */
    private $test = false;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        if (isset($data['data'])) {
            $this->test = $data['data'];
        }
    }

    public function getTest(): bool
    {
        return $this->test;
    }
}
