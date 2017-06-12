<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Subscription|null getData()
 */
class SubscriptionResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Subscription|null
     */
    protected $data;
}
