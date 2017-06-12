<?php

namespace InstagramRestApi\Response\Model;

class Attribution
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
