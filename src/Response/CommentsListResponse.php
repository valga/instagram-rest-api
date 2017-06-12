<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Comment[]|null getData()
 * @method CommentsListResponse|null getNextPage()
 */
class CommentsListResponse extends ListResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Comment[]|null
     */
    protected $data;
}
