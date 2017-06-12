<?php

namespace InstagramRestApi\Response\Model;

class Pagination
{
    /**
     * @var string|null
     */
    protected $next_url;

    /**
     * @var string|null
     */
    protected $next_max_id;

    /**
     * @var string|null
     */
    protected $next_min_id;

    /**
     * @var string|null
     */
    protected $next_max_like_id;

    /**
     * @var string|null
     */
    protected $next_max_tag_id;

    /**
     * @var string|null
     */
    protected $min_tag_id;

    /**
     * @var string|null
     */
    protected $deprecation_warning;

    /**
     * @return string|null
     */
    public function getNextUrl()
    {
        return $this->next_url;
    }

    /**
     * @return string|null
     */
    public function getNextMaxId()
    {
        return $this->next_max_id;
    }

    /**
     * @return string|null
     */
    public function getNextMinId()
    {
        return $this->next_min_id;
    }

    /**
     * @return string|null
     */
    public function getNextMaxLikeId()
    {
        return $this->next_max_like_id;
    }

    /**
     * @return string|null
     */
    public function getNextMaxTagId()
    {
        return $this->next_max_tag_id;
    }

    /**
     * @return string|null
     */
    public function getMinTagId()
    {
        return $this->min_tag_id;
    }

    /**
     * @return string|null
     */
    public function getDeprecationWarning()
    {
        return $this->deprecation_warning;
    }
}
