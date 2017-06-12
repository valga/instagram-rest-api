<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\MediaListResponse;
use InstagramRestApi\Response\MediaResponse;

/**
 * Media Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/media/
 */
class Media extends Endpoint
{
    /**
     * Filters provided media shortcode.
     *
     * @param string $mediaShortcode Media shortcode to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function filterMediaShortcode($mediaShortcode)
    {
        if (base64_decode($mediaShortcode) === false) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid media shortcode.', $mediaShortcode));
        }

        return $mediaShortcode;
    }

    /**
     * Get information about a media object by its identifier.
     * Required scopes:
     *  - public_content permission scope is required to get a media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId A valid media identifier (e.g. "823251211150230726_25025320").
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaResponse
     *
     * @see https://www.instagram.com/developer/endpoints/media/#get_media
     */
    public function getById($mediaId)
    {
        $mediaId = $this->filterMediaId($mediaId);
        /** @var MediaResponse $response */
        $response = $this->getClient()->request('GET', "/media/{$mediaId}")
            ->getResponse(MediaResponse::class);

        return $response;
    }

    /**
     * Get information about a media object by its shortcode.
     * Required scopes:
     *  - public_content permission scope is required to get a media that does not belong to the owner of the access_token.
     *
     * @param string $mediaShortcode A valid media shortcode (e.g. "tsxp1hhQTG").
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaResponse
     *
     * @see https://www.instagram.com/developer/endpoints/media/#get_media_by_shortcode
     */
    public function getByShortcode($mediaShortcode)
    {
        $mediaId = $this->filterMediaShortcode($mediaShortcode);
        /** @var MediaResponse $response */
        $response = $this->getClient()->request('GET', "/media/shortcode/{$mediaId}")
            ->getResponse(MediaResponse::class);

        return $response;
    }

    /**
     * Search for recent media in a given area.
     * Required scopes:
     *  - public_content.
     *
     * @param float    $lat      Latitude of the center search coordinate. If used, lng is required.
     * @param float    $lng      Longitude of the center search coordinate. If used, lat is required.
     * @param int|null $distance Default is 1km (distance=1000), max distance is 5km.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/media/#get_media_search
     */
    public function search($lat, $lng, $distance = null)
    {
        $distance = $this->filterDistance($distance, 5000);
        /** @var MediaListResponse $response */
        $response = $this->getClient()->request('GET', '/media/search')
            ->addParam('lat', $lat)
            ->addParam('lng', $lng)
            ->addParam('distance', $distance)
            ->getResponse(MediaListResponse::class);

        return $response;
    }
}
