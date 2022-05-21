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

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    private function formatInfo(array $data): array
    {
        $formatted = $data;

        // replace keys
        $formatted = $this->replaceInfoKeys($formatted, 'cardType', 'brand');

        return $this->replaceInfoKeys($formatted, 'cardDate', 'expiry');
    }
}
