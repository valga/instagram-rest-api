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
     * @param string $message
     *
     * @return string
     */
    private function prettifyMessage($message)
    {
        if ($length = strlen($message)) {
            $message[0] = strtoupper($message[0]);
            $lastChar = $message[$length - 1];
            if ($lastChar != '.' && $lastChar != '!' && $lastChar != '?') {
                $message .= '.';
            }
        }

        return $message;
    }

    /**
     * Constructor.
     *
     * @param string                     $message      The Exception message to throw.
     * @param int                        $code         The Exception code.
     * @param HttpResponseInterface|null $httpResponse The HTTP response instance.
     * @param \Exception|null            $previous     The previous throwable used for the exception chaining.
     */
    public function __construct($message = '', $code = 0, HttpResponseInterface $httpResponse = null, \Exception $previous = null)
    {
        $this->httpResponse = $httpResponse;
        parent::__construct($this->prettifyMessage($message), $code, $previous);
    }
}
