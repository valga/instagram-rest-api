<?php

namespace InstagramRestApi\Response\Model;

class Comment
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var User|null
     */
    protected $from;

    /**
     * @var string|null
     */
    protected $text;

    /**
     * @var string|null
     */
    protected $created_time;

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getFrom()
    {
        return $this->from;
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
}
