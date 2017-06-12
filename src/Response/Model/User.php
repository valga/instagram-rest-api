<?php

namespace InstagramRestApi\Response\Model;

class User
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $profile_picture;

    /**
     * @var string|null
     */
    protected $full_name;

    /**
     * @var string|null
     */
    protected $bio;

    /**
     * @var string|null
     */
    protected $website;

    /**
     * @var UserCounters|null
     */
    protected $counts;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getProfilePicture()
    {
        return $this->profile_picture;
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @return string|null
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return UserCounters|null
     */
    public function getCounts()
    {
        return $this->counts;
    }
}
