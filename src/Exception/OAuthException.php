<?php

namespace InstagramRestApi\Exception;

class OAuthException extends EndpointException
{
    /**
     * @param array $request
     */
    public static function throwFromRequest(array $request)
    {
        if (isset($request['error_description'])) {
            throw new static($request['error_description']);
        } elseif (isset($request['error_reason'])) {
            throw new static($request['error_reason']);
        } elseif (isset($request['error'])) {
            throw new static($request['error']);
        } else {
            throw new static('Invalid request data.');
        }
    }

    /**
     * @param array $request
     */
    public static function throwFromState(array $request)
    {
        if (!isset($request['state'])) {
            throw new static('Required parameter "state" is missing.');
        } else {
            throw new static(sprintf('Invalid "state" parameter value: "%s".', $request['state']));
        }
    }
}
