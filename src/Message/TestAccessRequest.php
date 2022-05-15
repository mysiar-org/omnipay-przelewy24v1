<?php

declare(strict_types=1);

namespace Omnipay\Przelewy24\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\VarDumper\VarDumper;

class TestAccessRequest extends AbstractRequest
{
    /**
     * @return string[]
     */
    public function getData(): array
    {
        return [];
    }

    /**
     * @param string[] $data
     * @return ResponseInterface|TestAccessResponse
     */
    public function sendData($data): ResponseInterface
    {
        $httpResponse = $this->sendRequest('GET', 'testAccess', $data);
        VarDumper::dump($httpResponse);
        VarDumper::dump($httpResponse->getBody()->getContents());
        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new TestAccessResponse($this, $responseData);
    }
}
