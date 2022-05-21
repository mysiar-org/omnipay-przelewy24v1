<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;

class PurchaseOfflineResponse extends AbstractResponse
{
    /**
     * @var string[]
     */
    private $info = [];

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (isset($this->data['data'])) {
            $this->info = $this->data['data'];
            $this->info = $this->replaceInfoKeys($this->info, 'orderId', 'transactionId');
        }
    }

    public function getInfo(): array
    {
        return $this->info;
    }
}
