<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Location|null getData()
 */
class LocationResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Location|null
     */
    protected $data;
}
