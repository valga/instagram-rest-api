<?php

namespace InstagramRestApi\Response\Model;

class TaggedUser
{
    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var TaggedPosition|null
     */
    protected $position;

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return TaggedPosition|null
     */
    public function getPosition()
    {
        return $this->position;
    }
}
