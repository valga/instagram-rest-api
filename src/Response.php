<?php

namespace InstagramRestApi;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class Response implements ResponseInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var HttpResponseInterface
     */
    private $httpResponse;

    /**
     * @var mixed
     */
    private $fullResponse;

    /**
     * {@inheritdoc}
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullResponse()
    {
        return $this->fullResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function __construct(Client $client, HttpResponseInterface $httpResponse, $fullResponse = null)
    {
        $this->client = $client;
        $this->httpResponse = $httpResponse;
        $this->fullResponse = $fullResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk()
    {
        return $this->getHttpResponse()->getStatusCode() === 200;
    }
}
