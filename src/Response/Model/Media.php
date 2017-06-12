<?php

namespace InstagramRestApi\Response\Model;

class Media
{
    /**
     * @var string
     */
    const TYPE_IMAGE = 'image';

    /**
     * @var string
     */
    const TYPE_VIDEO = 'video';

    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var float|null
     */
    protected $distance;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var TaggedUser[]|null
     */
    protected $users_in_photo;

    /**
     * @var string|null
     */
    protected $filter;

    /**
     * @var array|null
     */
    protected $tags;

    /**
     * @var MediaComments|null
     */
    protected $comments;

    /**
     * @var bool|null
     */
    protected $user_has_liked;

    /**
     * @var MediaCaption|null
     */
    protected $caption;

    /**
     * @var MediaLikes|null
     */
    protected $likes;

    /**
     * @var string|null
     */
    protected $link;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var string|null
     */
    protected $created_time;

    /**
     * @var MediaImages|null
     */
    protected $images;

    /**
     * @var MediaVideos|null
     */
    protected $videos;

    /**
     * @var Media[]|null
     */
    protected $carousel_media;

    /**
     * @var Location|null
     */
    protected $location;

    /**
     * @var Attribution|null
     */
    protected $attribution;

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return TaggedUser[]|null
     */
    public function getUsersInPhoto()
    {
        return $this->users_in_photo;
    }

    /**
     * @return string|null
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return array|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return MediaComments|null
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return bool|null
     */
    public function getUserHasLiked()
    {
        return $this->user_has_liked;
    }

    /**
     * @return MediaCaption|null
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return MediaLikes|null
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }

    /**
     * @return MediaImages|null
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return MediaVideos|null
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @return Media[]|null
     */
    public function getCarouselMedia()
    {
        return $this->carousel_media;
    }

    /**
     * @return Location|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return Attribution|null
     */
    public function getAttribution()
    {
        return $this->attribution;
    }
}
