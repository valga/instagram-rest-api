<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\EndpointResponse;
use InstagramRestApi\Response\UserListResponse;

/**
 * Like Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/likes/
 */
class Likes extends Endpoint
{
    /**
     * Get a list of users who have liked this media.
     * Required scopes:
     *  - public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId A valid media identifier (e.g. "823251211150230726_25025320").
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return UserListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/likes/#get_media_likes
     */
    public function get($mediaId)
    {
        $mediaId = $this->filterMediaId($mediaId);
        /** @var UserListResponse $response */
        $response = $this->getClient()->request('GET', "/media/{$mediaId}/likes")
            ->getResponse(UserListResponse::class);

        return $response;
    }

    /**
     * Set a like on this media by the currently authenticated user.
     * Required scopes:
     *  - likes.
     *  - public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId A valid media identifier (e.g. "823251211150230726_25025320").
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return EndpointResponse
     *
     * @see https://www.instagram.com/developer/endpoints/likes/#post_likes
     */
    public function add($mediaId)
    {
        $mediaId = $this->filterMediaId($mediaId);
        /** @var EndpointResponse $response */
        $response = $this->getClient()->request('POST', "/media/{$mediaId}/likes")
            ->getResponse(EndpointResponse::class);

        return $response;
    }

    /**
     * Remove a like on this media by the currently authenticated user.
     * Required scopes:
     *  - likes.
     *  - public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId A valid media identifier (e.g. "823251211150230726_25025320").
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return EndpointResponse
     *
     * @see https://www.instagram.com/developer/endpoints/likes/#delete_likes
     */
    public function delete($mediaId)
    {
        $mediaId = $this->filterMediaId($mediaId);
        /** @var EndpointResponse $response */
        $response = $this->getClient()->request('DELETE', "/media/{$mediaId}/likes")
            ->getResponse(EndpointResponse::class);

        return $response;
    }
}
