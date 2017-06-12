<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\MediaListResponse;
use InstagramRestApi\Response\UserListResponse;
use InstagramRestApi\Response\UserResponse;

/**
 * User Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/users/
 */
class Users extends Endpoint
{
    /**
     * Get information about the owner of the access_token.
     *
     * @throws RequestException
     *
     * @return UserResponse
     *
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_self
     */
    public function getSelf()
    {
        /** @var UserResponse $response */
        $response = $this->getClient()->request('GET', '/users/self')
            ->getResponse(UserResponse::class);

        return $response;
    }

    /**
     * Get information about a user.
     * Required scopes:
     *  - public_content scope is required if the user is not the owner of the access_token.
     *
     * @param int|string $userId User's identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return UserResponse
     *
     * @see https://www.instagram.com/developer/endpoints/users/#get_users
     */
    public function getUser($userId)
    {
        $userId = $this->filterUserId($userId);
        /** @var UserResponse $response */
        $response = $this->getClient()->request('GET', "/users/{$userId}")
            ->getResponse(UserResponse::class);

        return $response;
    }

    /**
     * Get the most recent media published by the owner of the access_token.
     *
     * @param int|null    $count Count of media to return.
     * @param string|null $maxId Return media earlier than this max_id.
     * @param string|null $minId Return media later than this min_id.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
     */
    public function getSelfRecentMedia($count = null, $maxId = null, $minId = null)
    {
        $count = $this->filterCount($count);
        /** @var MediaListResponse $response */
        $response = $this->getClient()->request('GET', '/users/self/media/recent')
            ->addParam('max_id', $maxId)
            ->addParam('min_id', $minId)
            ->addParam('count', $count)
            ->getResponse(MediaListResponse::class);

        return $response;
    }

    /**
     * Get the most recent media published by a user.
     * Required scopes:
     *  - public_content scope is required if the user is not the owner of the access_token.
     *
     * @param int|string  $userId User's identifier.
     * @param int|null    $count  Count of media to return.
     * @param string|null $maxId  Return media earlier than this id.
     * @param string|null $minId  Return media later than this id.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent
     */
    public function getUserRecentMedia($userId, $count = null, $maxId = null, $minId = null)
    {
        $userId = $this->filterUserId($userId);
        $count = $this->filterCount($count);
        /** @var MediaListResponse $response */
        $response = $this->getClient()->request('GET', "/users/{$userId}/media/recent")
            ->addParam('max_id', $maxId)
            ->addParam('min_id', $minId)
            ->addParam('count', $count)
            ->getResponse(MediaListResponse::class);

        return $response;
    }

    /**
     * Get the list of recent media liked by the owner of the access_token.
     *
     * @param int|null    $count     Count of media to return.
     * @param string|null $maxLikeId Return media liked before this id.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_feed_liked
     */
    public function getSelfLikedMedia($count = null, $maxLikeId = null)
    {
        $count = $this->filterCount($count);
        /** @var MediaListResponse $response */
        $response = $this->getClient()->request('GET', '/users/self/media/liked')
            ->addParam('max_like_id', $maxLikeId)
            ->addParam('count', $count)
            ->getResponse(MediaListResponse::class);

        return $response;
    }

    /**
     * Get a list of users matching the query.
     * Required scopes:
     *  - public_content.
     *
     * @param string   $query A query string.
     * @param int|null $count Number of users to return.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return UserListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_search
     */
    public function search($query, $count = null)
    {
        $count = $this->filterCount($count);
        /** @var UserListResponse $response */
        $response = $this->getClient()->request('GET', '/users/search')
            ->addParam('q', $query)
            ->addParam('count', $count)
            ->getResponse(UserListResponse::class);

        return $response;
    }
}
