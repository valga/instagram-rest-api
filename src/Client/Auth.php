<?php

namespace InstagramRestApi\Client;

use InstagramRestApi\Request;

/**
 * Auth service.
 *
 * @see https://www.instagram.com/developer/authentication/
 */
class Auth
{
    /**
     * @var string To read a user’s profile info and media.
     */
    const SCOPE_BASIC = 'basic';

    /**
     * @var string To read any public profile info and media on a user’s behalf.
     */
    const SCOPE_PUBLIC_CONTENT = 'public_content';

    /**
     * @var string To read the list of followers and followed-by users.
     */
    const SCOPE_FOLLOWER_LIST = 'follower_list';

    /**
     * @var string To post and delete comments on a user’s behalf.
     */
    const SCOPE_COMMENTS = 'comments';

    /**
     * @var string To follow and unfollow accounts on a user’s behalf.
     */
    const SCOPE_RELATIONSHIPS = 'relationships';

    /**
     * @var string To like and unlike media on a user’s behalf.
     */
    const SCOPE_LIKE = 'likes';

    /**
     * @var string
     */
    const AUTHORIZE_ENDPOINT = 'https://api.instagram.com/oauth/authorize/';

    /**
     * @var string
     */
    const TOKEN_ENDPOINT = 'https://api.instagram.com/oauth/access_token/';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var bool
     */
    private $enforceSignedRequests;

    /**
     * @var null|string
     */
    private $accessToken;

    /**
     * Returns Client ID.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Returns Client Secret.
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Returns enforce signed requests flag.
     *
     * @return bool
     */
    public function getEnforceSignedRequests()
    {
        return $this->enforceSignedRequests;
    }

    /**
     * Returns access token (if available).
     *
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Sets access token.
     *
     * @param string $accessToken New access token.
     *
     * @throws \InvalidArgumentException
     *
     * @return Auth
     */
    public function setAccessToken($accessToken)
    {
        if (!is_string($accessToken)) {
            throw new \InvalidArgumentException('Please provide valid access token.');
        }
        $accessToken = trim($accessToken);
        if (!strlen($accessToken)) {
            throw new \InvalidArgumentException('Access token can not be empty.');
        }
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Constructor.
     *
     * @param array $options An associative array with following keys:
     *                       "clientId" (required) - Client ID;
     *                       "clientSecret" (required) - Client Secret;
     *                       "accessToken" (optional) - OAuth token;
     *                       "enforceSignedRequests" (optional, false by default) - Whether to use signed requests.
     *
     * @throws \InvalidArgumentException
     *
     * @see https://www.instagram.com/developer/authentication/
     */
    public function __construct(array $options)
    {
        // Client ID.
        if (!isset($options['clientId']) || !is_string($options['clientId'])) {
            throw new \InvalidArgumentException('Please provide Client ID.');
        }
        $this->clientId = trim($options['clientId']);
        if (!strlen($this->clientId)) {
            throw new \InvalidArgumentException('Please provide valid Client ID.');
        }
        // Client Secret.
        if (!isset($options['clientSecret']) || !is_string($options['clientSecret'])) {
            throw new \InvalidArgumentException('Please provide Client Secret.');
        }
        $this->clientSecret = trim($options['clientSecret']);
        if (!strlen($this->clientSecret)) {
            throw new \InvalidArgumentException('Please provide valid Client Secret.');
        }
        // Access Token.
        if (isset($options['accessToken'])) {
            $this->setAccessToken($options['accessToken']);
        }
        // Enforce signed requests.
        if (!isset($options['enforceSignedRequests'])) {
            $this->enforceSignedRequests = false;
        } else {
            $this->enforceSignedRequests = $options['enforceSignedRequests'];
        }
    }

    /**
     * Returns all available scopes.
     *
     * @return array
     *
     * @see https://www.instagram.com/developer/authorization/
     */
    public function getAllScopes()
    {
        return [
            self::SCOPE_BASIC,
            self::SCOPE_COMMENTS,
            self::SCOPE_FOLLOWER_LIST,
            self::SCOPE_LIKE,
            self::SCOPE_PUBLIC_CONTENT,
            self::SCOPE_RELATIONSHIPS,
        ];
    }

    /**
     * Signs request with application credentials.
     *
     * @param Request $request
     */
    public function signAppRequest(Request $request)
    {
        $request->addParam('client_id', $this->clientId);
        $request->addParam('client_secret', $this->clientSecret);
    }

    /**
     * Secures request by signing it with client secret.
     *
     * @param Request $request
     *
     * @see https://www.instagram.com/developer/secure-api-requests/#enforce-signed-requests
     */
    private function secureRequest(Request $request)
    {
        $params = array_filter($request->getParams(), function ($value) {
            return $value !== null;
        });
        $sig = urldecode($request->getEndpoint());
        ksort($params);
        foreach ($params as $key => $val) {
            $sig .= "|{$key}={$val}";
        }
        $request->addParam('sig', hash_hmac('sha256', $sig, $this->clientSecret, false));
    }

    /**
     * Signs request with user credentials.
     *
     * @param Request $request
     *
     * @throws \RuntimeException
     */
    public function signUserRequest(Request $request)
    {
        if ($this->accessToken === null) {
            throw new \RuntimeException('Access token is missing.');
        }
        $request->addParam('access_token', $this->accessToken);
        if ($this->enforceSignedRequests) {
            $this->secureRequest($request);
        }
    }
}
