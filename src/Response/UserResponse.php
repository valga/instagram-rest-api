<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\User|null getData()
 */
class UserResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\User|null
     */
    protected $data;
}
