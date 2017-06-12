<?php

namespace InstagramRestApi\Exception;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class RequestException extends \RuntimeException
{
    /**
     * @var null|HttpResponseInterface
     */
    private $httpResponse;

    /**
     * @return null|HttpResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Checks whether we have a HTTP response.
     *
     * @return bool
     */
    public function hasHttpResponse()
    {
        return $this->httpResponse !== null;
    }

    /**
     * @param HttpResponseInterface $httpResponse
     *
     * @return RequestException
     */
    public function setHttpResponse(HttpResponseInterface $httpResponse)
    {
        $this->httpResponse = $httpResponse;

        return $this;
    }
}
