<?php

namespace InstagramRestApi\Response\Model;

class Relationship
{
    /**
     * @var string
     */
    const STATUS_FOLLOWS = 'follows';

    /**
     * @var string
     */
    const STATUS_REQUESTED = 'requested';

    /**
     * @var string
     */
    const STATUS_FOLLOWED_BY = 'followed_by';

    /**
     * @var string
     */
    const STATUS_REQUESTED_BY = 'requested_by';

    /**
     * @var string
     */
    const STATUS_BLOCKED_BY = 'blocked_by_you';

    /**
     * @var string
     */
    const STATUS_NONE = 'none';

    /**
     * @var string|null
     */
    protected $outgoing_status;

    /**
     * @var string|null
     */
    protected $incoming_status;

    /**
     * Returns your relationship to the user. Can be 'follows', 'requested', 'none'.
     *
     * @return string|null
     */
    public function getOutgoingStatus()
    {
        return $this->outgoing_status;
    }

    /**
     * Returns a user's relationship to you. Can be 'followed_by', 'requested_by', 'blocked_by_you', 'none'.
     *
     * @return string|null
     */
    public function getIncomingStatus()
    {
        return $this->incoming_status;
    }
}
