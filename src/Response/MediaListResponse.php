<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Media[]|null getData()
 * @method MediaListResponse|null getNextPage()
 */
class MediaListResponse extends ListResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Media[]|null
     */
    protected $data;
}
