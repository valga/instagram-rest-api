<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\User[]|null getData()
 * @method UserListResponse|null getNextPage()
 */
class UserListResponse extends ListResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\User[]|null
     */
    protected $data;
}
