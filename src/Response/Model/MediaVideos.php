<?php

namespace InstagramRestApi\Response\Model;

class MediaVideos
{
    /**
     * @var MediaSrc|null
     */
    protected $low_resolution;

    /**
     * @var MediaSrc|null
     */
    protected $low_bandwidth;

    /**
     * @var MediaSrc|null
     */
    protected $standard_resolution;

    /**
     * @return MediaSrc|null
     */
    public function getLowResolution()
    {
        return $this->low_resolution;
    }

    /**
     * @return MediaSrc|null
     */
    public function getLowBandwidth()
    {
        return $this->low_bandwidth;
    }

    /**
     * @return MediaSrc|null
     */
    public function getStandardResolution()
    {
        return $this->standard_resolution;
    }
}
