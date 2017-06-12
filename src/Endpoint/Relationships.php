<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\RelationshipResponse;
use InstagramRestApi\Response\UserListResponse;

/**
 * Relationship Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/relationships/
 */
class Relationships extends Endpoint
{
    /**
     * @var string
     */
    const ACTION_FOLLOW = 'follow';

    /**
     * @var string
     */
    const ACTION_UNFOLLOW = 'unfollow';

    /**
     * @var string
     */
    const ACTION_APPROVE = 'approve';

    /**
     * @var string
     */
    const ACTION_IGNORE = 'ignore';

    /**
     * Get the list of users this user follows.
     * Required scopes:
     *  - follower_list.
     *
     * @throws RequestException
     *
     * @return UserListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#get_users_follows
     */
    public function getFollowing()
    {
        /** @var UserListResponse $response */
        $response = $this->getClient()->request('GET', '/users/self/follows')
            ->getResponse(UserListResponse::class);

        return $response;
    }

    /**
     * Get the list of users this user is followed by.
     * Required scopes:
     *  - follower_list.
     *
     * @throws RequestException
     *
     * @return UserListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#get_users_followed_by
     */
    public function getFollowers()
    {
        /** @var UserListResponse $response */
        $response = $this->getClient()->request('GET', '/users/self/followed-by')
            ->getResponse(UserListResponse::class);

        return $response;
    }

    /**
     * List the users who have requested this user's permission to follow.
     * Required scopes:
     *  - follower_list.
     *
     * @throws RequestException
     *
     * @return UserListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#get_incoming_requests
     */
    public function getPendingUsers()
    {
        /** @var UserListResponse $response */
        $response = $this->getClient()->request('GET', '/users/self/requested-by')
            ->getResponse(UserListResponse::class);

        return $response;
    }

    /**
     * Get information about a relationship to another user.
     * Required scopes:
     *  - follower_list.
     *
     * @param int|string $userId User's identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return RelationshipResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#get_relationship
     */
    public function getStatus($userId)
    {
        $userId = $this->filterUserId($userId);
        /** @var RelationshipResponse $response */
        $response = $this->getClient()->request('GET', "/users/{$userId}/relationship")
            ->getResponse(RelationshipResponse::class);

        return $response;
    }

    /**
     * Modify the relationship between the current user and the target user.
     * Required scopes:
     *  - relationships.
     *
     * @param string $userId User's identifier.
     * @param string $action The relationship action you want to perform.
     *                       Valid actions are: 'follow', 'unfollow' 'approve' or 'ignore'.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return RelationshipResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#post_relationship
     */
    private function update($userId, $action)
    {
        $userId = $this->filterUserId($userId);
        /** @var RelationshipResponse $response */
        $response = $this->getClient()->request('POST', "/users/{$userId}/relationship")
            ->addParam('action', $action)
            ->getResponse(RelationshipResponse::class);

        return $response;
    }

    /**
     * Follow user.
     * Required scopes:
     *  - relationships.
     *
     * @param string $userId User's identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return RelationshipResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#post_relationship
     */
    public function follow($userId)
    {
        return $this->update($userId, self::ACTION_FOLLOW);
    }

    /**
     * Unfollow user.
     * Required scopes:
     *  - relationships.
     *
     * @param string $userId User's identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return RelationshipResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#post_relationship
     */
    public function unfollow($userId)
    {
        return $this->update($userId, self::ACTION_UNFOLLOW);
    }

    /**
     * Approve user's request.
     * Required scopes:
     *  - relationships.
     *
     * @param string $userId User's identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return RelationshipResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#post_relationship
     */
    public function approve($userId)
    {
        return $this->update($userId, self::ACTION_APPROVE);
    }

    /**
     * Ignore user's request.
     * Required scopes:
     *  - relationships.
     *
     * @param string $userId User's identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return RelationshipResponse
     *
     * @see https://www.instagram.com/developer/endpoints/relationships/#post_relationship
     */
    public function ignore($userId)
    {
        return $this->update($userId, self::ACTION_IGNORE);
    }
}
