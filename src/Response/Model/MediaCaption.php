<?php

namespace InstagramRestApi\Response\Model;

class MediaCaption
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $text;

    /**
     * @var string|null
     */
    protected $created_time;

    /**
     * @var User|null
     */
    protected $from;

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }

    /**
     * @return User|null
     */
    public function getFrom()
    {
        return $this->from;
    }
}
