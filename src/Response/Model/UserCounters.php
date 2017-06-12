<?php

namespace InstagramRestApi\Response\Model;

class UserCounters
{
    /**
     * @var int|null
     */
    protected $media;

    /**
     * @var int|null
     */
    protected $follows;

    /**
     * @var int|null
     */
    protected $followed_by;

    /**
     * @return int|null
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return int|null
     */
    public function getFollows()
    {
        return $this->follows;
    }

    /**
     * @return int|null
     */
    public function getFollowedBy()
    {
        return $this->followed_by;
    }
}
