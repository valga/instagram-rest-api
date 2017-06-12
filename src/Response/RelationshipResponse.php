<?php

namespace InstagramRestApi\Response;

/**
 * @method Model\Relationship|null getData()
 */
class RelationshipResponse extends EndpointResponse
{
    /**
     * This override is required for JsonMapper. Don't remove it.
     *
     * @var Model\Relationship|null
     */
    protected $data;
}
