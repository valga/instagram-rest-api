<?php

namespace InstagramRestApi;

class Endpoint
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Returns Client instance.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Filters provided user ID.
     *
     * @param int|string $userId User ID to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function filterUserId($userId)
    {
        if (!is_int($userId) && !ctype_digit($userId)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid user identifier.', $userId));
        }

        return (string) $userId;
    }

    /**
     * Filters provided media ID.
     *
     * @param string $mediaId Media ID to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function filterMediaId($mediaId)
    {
        if (!preg_match('#^\d+_\d+$#D', $mediaId)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid media identifier.', $mediaId));
        }

        return $mediaId;
    }

    /**
     * Filters provided count.
     *
     * @param int|null $count Count to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return int|null
     */
    protected function filterCount($count = null)
    {
        if ($count !== null && (!is_int($count) || $count < 1)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid count.', $count));
        }

        return $count;
    }

    /**
     * Filters provided distance.
     *
     * @param int|null $distance    Distance to filter.
     * @param int|null $maxDistance Upper limit for a distance.
     *
     * @throws \InvalidArgumentException
     *
     * @return int|null
     */
    protected function filterDistance($distance = null, $maxDistance = null)
    {
        if ($distance !== null && (!is_int($distance) || $distance < 1)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid distance.', $distance));
        }
        if ($distance !== null && $maxDistance !== null && $distance > $maxDistance) {
            throw new \InvalidArgumentException(sprintf('Distance can not be greater than %d.', $maxDistance));
        }

        return $distance;
    }
}
