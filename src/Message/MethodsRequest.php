<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\ResponseInterface;

class MethodsRequest extends AbstractRequest
{
    public function getLang(): string
    {
        return $this->getParameter('lang');
    }

    public function setLang(string $value): self
    {
        return $this->setParameter('lang', $value);
    }

    public function getData(): array
    {
        $this->validate('lang');

        return [
            'lang' => $this->getLang(),
        ];
    }

    /**
     * @param string[] $data
     * @return ResponseInterface|MethodsResponse
     */
    public function sendData($data): ResponseInterface
    {
        $httpResponse = $this->sendRequest('GET', sprintf('payment/methods/%s', $data['lang']), []);

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new MethodsResponse($this, $responseData);
    }
}
