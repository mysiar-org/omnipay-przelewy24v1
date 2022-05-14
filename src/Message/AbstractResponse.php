<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends BaseAbstractResponse
{
    public const HTTP_OK = 200;

    public const HTTP_BAD_REQUEST = 400;

    public const HTTP_UNAUTHORIZED = 401;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param string[] $data
     * @param string $endpoint
     */
    public function __construct(RequestInterface $request, $data, $endpoint)
    {
        parent::__construct($request, $data);
        $this->endpoint = $endpoint;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getCode(): int
    {
        if (isset($this->data['code'])) {
            return $this->data['code'];
        }

        return self::HTTP_OK;
    }

    public function getMessage(): string
    {
        if (isset($this->data['error'])) {
            return $this->data['error'];
        }

        return '';
    }

    public function isSuccessful(): bool
    {
        return self::HTTP_OK === $this->getCode();
    }
}
