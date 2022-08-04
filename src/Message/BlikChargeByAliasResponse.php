<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class BlikChargeByAliasResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $chargeMessage;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        if (isset($data['data'])) {
            $this->orderId = $data['data']['orderId'];
            $this->chargeMessage = $data['data']['message'];
        }
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getChargeMessage(): string
    {
        return $this->chargeMessage;
    }
}
