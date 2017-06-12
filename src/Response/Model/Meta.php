<?php

namespace InstagramRestApi\Response\Model;

class Meta
{
    /**
     * @var string|null
     */
    protected $error_type;

    /**
     * @var int|null
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $error_message;

    /**
     * @return string|null
     */
    public function getErrorType()
    {
        return $this->error_type;
    }

    /**
     * @return int|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }
}
