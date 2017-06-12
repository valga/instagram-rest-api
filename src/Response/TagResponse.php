<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Tag|null getData()
 */
class TagResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Tag|null
     */
    protected $data;
}
