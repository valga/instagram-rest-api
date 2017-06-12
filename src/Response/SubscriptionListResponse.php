<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Subscription[]|null getData()
 * @method SubscriptionListResponse|null getNextPage()
 */
class SubscriptionListResponse extends ListResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Subscription[]|null
     */
    protected $data;
}
