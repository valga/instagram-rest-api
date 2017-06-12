<?php

namespace InstagramRestApi\Response\Model;

class MediaLikes
{
    /**
     * @var int|null
     */
    protected $count;

    /**
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }
}
