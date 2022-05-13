<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class MethodsResponse extends AbstractResponse
{
    /**
     * @var string[]
     */
    private $methods = [];

    public function __construct(RequestInterface $request, $data, $endpoint)
    {
        parent::__construct($request, $data, $endpoint);
        if (isset($data['data'])) {
            $this->methods = $data['data'];
        }
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}
