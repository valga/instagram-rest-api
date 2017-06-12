<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\CommentResponse;
use InstagramRestApi\Response\CommentsListResponse;
use InstagramRestApi\Response\EndpointResponse;

/**
 * Comment Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/comments/
 */
class Comments extends Endpoint
{
    /**
     * Filters provided comment ID.
     *
     * @param int|string $commentId Comment ID to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function filterCommentId($commentId)
    {
        if (!is_int($commentId) && !ctype_digit($commentId)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid comment identifier.', $commentId));
        }

        return (string) $commentId;
    }

    /**
     * Get a list of recent comments on a media object.
     * Required scopes:
     *  - public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId A valid media identifier (e.g. "823251211150230726_25025320").
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return CommentsListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/comments/#get_media_comments
     */
    public function get($mediaId)
    {
        $mediaId = $this->filterMediaId($mediaId);
        /** @var CommentsListResponse $response */
        $response = $this->getClient()->request('GET', "/media/{$mediaId}/comments")
            ->getResponse(CommentsListResponse::class);

        return $response;
    }

    /**
     * Create a comment on a media object with the following rules:
     *  - The total length of the comment cannot exceed 300 characters.
     *  - The comment cannot contain more than 4 hashtags.
     *  - The comment cannot contain more than 1 URL.
     *  - The comment cannot consist of all capital letters.
     * Required scopes:
     *  - comments.
     *  - public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId A valid media identifier (e.g. "823251211150230726_25025320").
     * @param string $text    Text to post as a comment on the media object.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return CommentResponse
     *
     * @see https://www.instagram.com/developer/endpoints/comments/#post_media_comments
     */
    public function add($mediaId, $text)
    {
        $mediaId = $this->filterMediaId($mediaId);
        /** @var CommentResponse $response */
        $response = $this->getClient()->request('POST', "/media/{$mediaId}/comments")
            ->addParam('text', $text)
            ->getResponse(CommentResponse::class);

        return $response;
    }

    /**
     * Remove a comment either on the authenticated user's media object or authored by the authenticated user.
     * Required scopes:
     *  - comments.
     *  - public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param string $mediaId   A valid media identifier (e.g. "823251211150230726_25025320").
     * @param string $commentId A valid comment identifier.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return EndpointResponse
     *
     * @see https://www.instagram.com/developer/endpoints/comments/#delete_media_comments
     */
    public function delete($mediaId, $commentId)
    {
        $mediaId = $this->filterMediaId($mediaId);
        $commentId = $this->filterCommentId($commentId);
        /** @var EndpointResponse $response */
        $response = $this->getClient()->request('DELETE', "/media/{$mediaId}/comments/{$commentId}")
            ->getResponse(EndpointResponse::class);

        return $response;
    }
}
