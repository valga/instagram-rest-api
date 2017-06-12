<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\MediaListResponse;
use InstagramRestApi\Response\TagListResponse;
use InstagramRestApi\Response\TagResponse;

/**
 * Tag Endpoints.
 *
 * @see https://www.instagram.com/developer/endpoints/tags/
 */
class Tags extends Endpoint
{
    /**
     * Filters provided tag name.
     *
     * @param string $tag A valid tag name without a leading #.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function filterTag($tag)
    {
        $tag = trim(ltrim($tag, '#'));
        if (!strlen($tag)) {
            throw new \InvalidArgumentException('Tag must not be empty.');
        }
        if (preg_match('#\s#us', $tag)) {
            throw new \InvalidArgumentException('Tag must not contain whitespace characters.');
        }

        return urlencode($tag);
    }

    /**
     * Get information about a tag object.
     * Required scopes:
     *  - public_content.
     *
     * @param string $tag A valid tag name without a leading # (eg. snowy, nofilter).
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return TagResponse
     *
     * @see https://www.instagram.com/developer/endpoints/tags/#get_tags
     */
    public function get($tag)
    {
        $tag = $this->filterTag($tag);
        /** @var TagResponse $response */
        $response = $this->getClient()->request('GET', "/tags/{$tag}")
            ->getResponse(TagResponse::class);

        return $response;
    }

    /**
     * Get a list of recently tagged media.
     * Required scopes:
     *  - public_content.
     *
     * @param string      $tag      A valid tag name without a leading # (eg. snowy, nofilter).
     * @param int|null    $count    Count of media to return.
     * @param string|null $maxTagId Return media earlier than this id.
     * @param string|null $minTagId Return media later than this id.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return MediaListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/tags/#get_tags_media_recent
     */
    public function getRecentMedia($tag, $count = null, $maxTagId = null, $minTagId = null)
    {
        $tag = $this->filterTag($tag);
        $count = $this->filterCount($count);
        /** @var MediaListResponse $response */
        $response = $this->getClient()->request('GET', "/tags/{$tag}/media/recent")
            ->addParam('max_tag_id', $maxTagId)
            ->addParam('min_tag_id', $minTagId)
            ->addParam('count', $count)
            ->getResponse(MediaListResponse::class);

        return $response;
    }

    /**
     * Search for tags by name.
     * Required scopes:
     *  - public_content.
     *
     * @param string $query A valid tag name without a leading #.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return TagListResponse
     *
     * @see https://www.instagram.com/developer/endpoints/tags/#get_tags_search
     */
    public function search($query)
    {
        $query = $this->filterTag($query);
        /** @var TagListResponse $response */
        $response = $this->getClient()->request('GET', '/tags/search')
            ->addParam('q', $query)
            ->getResponse(TagListResponse::class);

        return $response;
    }
}
