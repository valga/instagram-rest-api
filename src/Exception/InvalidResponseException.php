<?php

namespace InstagramRestApi\Exception;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class InvalidResponseException extends RequestException
{
    /**
     * InvalidResponseException constructor.
     *
     * @param string                $message
     * @param HttpResponseInterface $httpResponse
     */
    public function __construct($message, HttpResponseInterface $httpResponse)
    {
        $this->setHttpResponse($httpResponse);
        parent::__construct($message);
    }
}
