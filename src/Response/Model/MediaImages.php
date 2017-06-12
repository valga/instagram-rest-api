<?php

namespace InstagramRestApi\Response\Model;

class MediaImages
{
    /**
     * @var MediaSrc|null
     */
    protected $low_resolution;

    /**
     * @var MediaSrc|null
     */
    protected $thumbnail;

    /**
     * @var MediaSrc|null
     */
    protected $standard_resolution;

    /**
     * @return MediaSrc
     */
    public function getLowResolution()
    {
        return $this->low_resolution;
    }

    /**
     * @return MediaSrc
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return MediaSrc
     */
    public function getStandardResolution()
    {
        return $this->standard_resolution;
    }
}
