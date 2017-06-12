<?php

namespace InstagramRestApi\Response\Model;

class Subscription
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $object_id;

    /**
     * @var int|null
     */
    protected $subscription_id;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $object;

    /**
     * @var string|null
     */
    protected $aspect;

    /**
     * @var string|null
     */
    protected $callback_url;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return string|null
     */
    public function getAspect()
    {
        return $this->aspect;
    }

    /**
     * @return string|null
     */
    public function getCallbackUrl()
    {
        return $this->callback_url;
    }

    /**
     * @return string|null
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * @return int|null
     */
    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }
}
