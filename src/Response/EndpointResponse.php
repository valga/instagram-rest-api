<?php

namespace InstagramRestApi\Response;

use InstagramRestApi\Response;

class EndpointResponse extends Response
{
    /**
     * @var int|false
     */
    private $rateLimit;

    /**
     * @var int|false
     */
    private $rateLimitRemaining;

    /**
     * @var Model\Meta|null
     */
    protected $meta;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @return Response\Model\Meta|null
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk()
    {
        return $this->getMeta() !== null && $this->getMeta()->getCode() === 200;
    }

    /**
     * Returns rate limit for an endpoint.
     *
     * @return int|false
     *
     * @see https://www.instagram.com/developer/limits/
     */
    public function getRateLimit()
    {
        if ($this->rateLimit === null) {
            if (!$this->getHttpResponse()->hasHeader('x-ratelimit-limit')) {
                $this->rateLimit = false;
            } else {
                $header = $this->getHttpResponse()->getHeader('x-ratelimit-limit');
                $this->rateLimit = (int) reset($header);
            }
        }

        return $this->rateLimit;
    }

    /**
     * Returns remaining rate limit for an endpoint.
     *
     * @return int|false
     *
     * @see https://www.instagram.com/developer/limits/
     */
    public function getRateLimitRemaining()
    {
        if ($this->rateLimitRemaining === null) {
            if (!$this->getHttpResponse()->hasHeader('x-ratelimit-remaining')) {
                $this->rateLimitRemaining = false;
            } else {
                $header = $this->getHttpResponse()->getHeader('x-ratelimit-remaining');
                $this->rateLimitRemaining = (int) reset($header);
            }
        }

        return $this->rateLimitRemaining;
    }
}
