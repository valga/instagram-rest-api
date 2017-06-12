<?php

namespace InstagramRestApi\Exception;

class NetworkException extends RequestException
{
    /**
     * @var null|\Exception
     */
    private $networkException;

    /**
     * @return null|\Exception
     */
    public function getNetworkException()
    {
        return $this->networkException;
    }

    /**
     * Constructor.
     *
     * @param \Exception $networkException
     */
    public function __construct(\Exception $networkException)
    {
        $this->networkException = $networkException;
        parent::__construct($networkException->getMessage(), $networkException->getCode());
    }
}
