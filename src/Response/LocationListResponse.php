<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Location[]|null getData()
 * @method LocationListResponse|null getNextPage()
 */
class LocationListResponse extends ListResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Location[]|null
     */
    protected $data;
}
