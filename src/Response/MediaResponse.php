<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Media|null getData()
 */
class MediaResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Media|null
     */
    protected $data;
}
