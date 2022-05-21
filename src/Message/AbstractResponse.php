<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * @param string[] $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    public function getCode(): int
    {
        if (isset($this->data['code'])) {
            return (int) $this->data['code'];
        }

        if (
            ! isset($this->data['code'])
            && isset($this->data['responseCode'])
            && 0 !== (int) $this->data['responseCode']
        ) {
            return (int) $this->data['responseCode'];
        }

        return Response::HTTP_OK;
    }

    /**
     * @return null|array|string
     */
    public function getMessage()
    {
        if (isset($this->data['error'])) {
            return $this->data['error'];
        }

        if (
            Response::HTTP_NOT_FOUND === $this->getCode()
            && isset($this->data['data'])
            && ! is_array($this->data['data'])
            ) {
            return $this->data['data'];
        }

        return '';
    }

    public function isSuccessful(): bool
    {
        $code = $this->getCode();

        return in_array($code, [Response::HTTP_CREATED, Response::HTTP_OK], true);
    }

    protected function getAmountFromInternal(int $amount): string
    {
        return bcdiv((string) $amount, '100', 2);
    }

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    protected function replaceInfoKeys(array $data, string $oldKey, string $newKey): array
    {
        if (isset($data[$oldKey])) {
            $data[$newKey] = $data[$oldKey];
            unset($data[$oldKey]);
        }

        return $data;
    }
}
