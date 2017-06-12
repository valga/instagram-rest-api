<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Tag[]|null getData()
 * @method TagListResponse|null getNextPage()
 */
class TagListResponse extends ListResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Tag[]|null
     */
    protected $data;
}
