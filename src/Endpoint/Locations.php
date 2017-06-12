<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\LocationListResponse;
use InstagramRestApi\Response\LocationResponse;
use InstagramRestApi\Response\MediaListResponse;

/**
 * Location Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/locations/
 */
class Locations extends Endpoint
{
    /**
     * Filters provided location ID.
     *
     * @param int|string $locationId Location ID to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function filterLocationId($locationId)
    {
        if (!is_int($locationId) && !ctype_digit($locationId)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid location identifier.', $locationId));
        }

        return (string) $locationId;
    }

    /**
     * Get information about a location.
     * Required scopes:
     *  - public_content.
     *
     * @param int|string $locationId A valid location identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return LocationResponse
     *
     * @see https://www.instagram.com/developer/endpoints/locations/#get_locations
     */
    public function get($locationId)
    {
        $locationId = $this->filterLocationId($locationId);
        /** @var LocationResponse $response */
        $response = $this->getClient()->request('GET', "/locations/{$locationId}")
            ->getResponse(LocationResponse::class);

        return $response;
    }

    /**
     * Get a list of recent media objects from a given location.
     * Required scopes:
     *  - public_content.
     *
     * @param int|string  $locationId A valid location identifier.
     * @param int|null    $count      Count of media to return.
     * @param string|null $maxId      Return media after this id.
     * @param string|null $minId      Return media before this id.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/locations/#get_locations_media_recent
     */
    public function getRecentMedia($locationId, $count = null, $maxId = null, $minId = null)
    {
        $locationId = $this->filterLocationId($locationId);
        $count = $this->filterCount($count);
        /** @var MediaListResponse $response */
        $response = $this->getClient()->request('GET', "/locations/{$locationId}/media/recent")
            ->addParam('max_id', $maxId)
            ->addParam('min_id', $minId)
            ->addParam('count', $count)
            ->getResponse(MediaListResponse::class);

        return $response;
    }

    /**
     * Search for a location by geographic coordinate.
     * Required scopes:
     *  - public_content.
     *
     * @param float       $lat              Latitude of the center search coordinate. If used, lng is required.
     * @param float       $lng              Longitude of the center search coordinate. If used, lat is required.
     * @param int|null    $distance         Default is 500m (distance=500), max distance is 750.
     * @param string|null $facebookPlacesId Returns a location mapped off of a Facebook places id.
     *                                      If used, lat and lng are not required.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return LocationListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/locations/#get_locations_search
     */
    public function search($lat, $lng, $distance = null, $facebookPlacesId = null)
    {
        $distance = $this->filterDistance($distance, 750);
        /** @var LocationListResponse $response */
        $response = $this->getClient()->request('GET', '/locations/search')
            ->addParam('lat', $lat)
            ->addParam('lng', $lng)
            ->addParam('distance', $distance)
            ->addParam('facebook_places_id', $facebookPlacesId)
            ->getResponse(LocationListResponse::class);

        return $response;
    }
}
