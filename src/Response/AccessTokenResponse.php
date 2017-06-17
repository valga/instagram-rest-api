<?php

namespace InstagramRestApi\Response;

use InstagramRestApi\Response;

class AccessTokenResponse extends Response
{
    /**
     * @var string|null
     */
    protected $access_token;

    /**
     * @var Model\User|null
     */
    protected $user;

    /**
     * @return string|string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @return Model\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk()
    {
        return $this->getAccessToken() !== null && strlen($this->getAccessToken());
    }
}
