<?php

namespace InstagramRestApi;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface
{
    /**
     * Constructor.
     *
     * @param Client                $client
     * @param HttpResponseInterface $httpResponse
     * @param mixed                 $fullResponse
     */
    public function __construct(Client $client, HttpResponseInterface $httpResponse, $fullResponse = null);

    /**
     * Returns Client instance.
     *
     * @return Client
     */
    public function getClient();

    /**
     * Returns HttpResponse instance.
     *
     * @return HttpResponseInterface
     */
    public function getHttpResponse();

    /**
     * Returns full response.
     *
     * @return mixed
     */
    public function getFullResponse();

    /**
     * Checks whether this response is successful.
     *
     * @return bool
     */
    public function isOk();
}
