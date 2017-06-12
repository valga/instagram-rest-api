<?php

namespace InstagramRestApi\Response\Model;

class TaggedPosition
{
    /**
     * @var float|null
     */
    protected $x;

    /**
     * @var float|null
     */
    protected $y;

    /**
     * @return float|null
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return float|null
     */
    public function getY()
    {
        return $this->y;
    }
}
