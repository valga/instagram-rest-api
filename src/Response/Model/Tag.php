<?php

namespace InstagramRestApi\Response\Model;

class Tag
{
    /**
     * @var int|null
     */
    protected $media_count;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @return int|null
     */
    public function getMediaCount()
    {
        return $this->media_count;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
