<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;

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
            $this->info = $this->formatInfo($data['data']);
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
        if (isset($this->data['responseCode'], $this->data['error']) && strlen($this->data['error']) > 0) {
            return Response::HTTP_NOT_FOUND;
        }

        if (isset($this->data['code'])) {
            return $this->data['code'];
        }

        return Response::HTTP_OK;
    }

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    private function formatInfo(array $data): array
    {
        $formatted = $data;

        // format
        if (isset($formatted['amount'])) {
            $formatted['amount'] = $this->getAmountFromInternal((int) $formatted['amount']);
        }

        // replace keys
        $formatted = $this->replaceInfoKeys($formatted, 'clientEmail', 'email');
        $formatted = $this->replaceInfoKeys($formatted, 'clientName', 'name');
        $formatted = $this->replaceInfoKeys($formatted, 'clientAddress', 'address');
        $formatted = $this->replaceInfoKeys($formatted, 'clientCity', 'city');

        return $this->replaceInfoKeys($formatted, 'clientPostcode', 'postcode');
    }
}
