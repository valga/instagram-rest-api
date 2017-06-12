<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Comment|null getData()
 */
class CommentResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Comment|null
     */
    protected $data;
}
