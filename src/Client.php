<?php

namespace InstagramRestApi;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use InstagramRestApi\Client\Auth;
use InstagramRestApi\Client\Http as HttpClient;
use JsonMapper;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @property Endpoint\Comments $comments
 * @property Endpoint\Likes $likes
 * @property Endpoint\Locations $locations
 * @property Endpoint\Media $media
 * @property Endpoint\Relationships $relationships
 * @property Endpoint\Subscriptions $subscriptions
 * @property Endpoint\Tags $tags
 * @property Endpoint\Users $users
 *
 * @see https://www.instagram.com/developer/ For Instagram API documentation.
 * @see https://www.instagram.com/developer/clients/manage/ For your API Clients management.
 * @see http://docs.guzzlephp.org/en/stable/ For Guzzle's client configuration.
 */
class Client
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var JsonMapper
     */
    private $jsonMapper;

    /**
     * @var Endpoint\Comments
     */
    private $commentsEndpoint;

    /**
     * @var Endpoint\Likes
     */
    private $likesEndpoint;

    /**
     * @var Endpoint\Locations
     */
    private $locationsEndpoint;

    /**
     * @var Endpoint\Media
     */
    private $mediaEndpoint;

    /**
     * @var Endpoint\Relationships
     */
    private $relationshipsEndpoint;

    /**
     * @var Endpoint\Subscriptions
     */
    private $subscriptionsEndpoint;

    /**
     * @var Endpoint\Users
     */
    private $usersEndpoint;

    /**
     * @var Endpoint\Tags
     */
    private $tagsEndpoint;

    /**
     * Returns Auth instance.
     *
     * @return Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Returns Logger instance.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Returns HttpClient instance.
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Returns JsonMapper instance.
     *
     * @return JsonMapper
     */
    public function getJsonMapper()
    {
        return $this->jsonMapper;
    }

    /**
     * Returns Users endpoint.
     *
     * @return Endpoint\Users
     */
    public function getUsersEndpoint()
    {
        if ($this->usersEndpoint === null) {
            $this->usersEndpoint = new Endpoint\Users($this);
        }

        return $this->usersEndpoint;
    }

    /**
     * Returns Tags endpoint.
     *
     * @return Endpoint\Tags
     */
    public function getTagsEndpoint()
    {
        if ($this->tagsEndpoint === null) {
            $this->tagsEndpoint = new Endpoint\Tags($this);
        }

        return $this->tagsEndpoint;
    }

    /**
     * Returns Media endpoint.
     *
     * @return Endpoint\Media
     */
    public function getMediaEndpoint()
    {
        if ($this->mediaEndpoint === null) {
            $this->mediaEndpoint = new Endpoint\Media($this);
        }

        return $this->mediaEndpoint;
    }

    /**
     * Returns Comments endpoint.
     *
     * @return Endpoint\Comments
     */
    public function getCommentsEndpoint()
    {
        if ($this->commentsEndpoint === null) {
            $this->commentsEndpoint = new Endpoint\Comments($this);
        }

        return $this->commentsEndpoint;
    }

    /**
     * Returns Locations endpoint.
     *
     * @return Endpoint\Locations
     */
    public function getLocationsEndpoint()
    {
        if ($this->locationsEndpoint === null) {
            $this->locationsEndpoint = new Endpoint\Locations($this);
        }

        return $this->locationsEndpoint;
    }

    /**
     * Returns Likes endpoint.
     *
     * @return Endpoint\Likes
     */
    public function getLikesEndpoint()
    {
        if ($this->likesEndpoint === null) {
            $this->likesEndpoint = new Endpoint\Likes($this);
        }

        return $this->likesEndpoint;
    }

    /**
     * Returns Relationships endpoint.
     *
     * @return Endpoint\Relationships
     */
    public function getRelationshipsEndpoint()
    {
        if ($this->relationshipsEndpoint === null) {
            $this->relationshipsEndpoint = new Endpoint\Relationships($this);
        }

        return $this->relationshipsEndpoint;
    }

    /**
     * Returns Subscriptions endpoint.
     *
     * @return Endpoint\Subscriptions
     */
    public function getSubscriptionsEndpoint()
    {
        if ($this->subscriptionsEndpoint === null) {
            $this->subscriptionsEndpoint = new Endpoint\Subscriptions($this);
        }

        return $this->subscriptionsEndpoint;
    }

    /**
     * Constructor.
     *
     * @param array                    $config     An associative array with following keys:
     *                                             "clientId" (required) - Client ID.
     *                                             "clientSecret" (required) - Client Secret.
     *                                             "accessToken" (optional) - OAuth token.
     *                                             "enforceSignedRequests" (optional, false by default)
     *                                             - Whether to use signed requests.
     * @param null|LoggerInterface     $logger     Custom logger instance.
     * @param null|HttpClientInterface $httpClient Custom Guzzle client.
     *
     * @throws \InvalidArgumentException
     *
     * @see https://www.instagram.com/developer/clients/manage/
     */
    public function __construct(array $config, LoggerInterface $logger = null, HttpClientInterface $httpClient = null)
    {
        $this->auth = new Auth($config);

        if ($logger !== null) {
            $this->logger = $logger;
        } else {
            $this->logger = new NullLogger();
        }

        $this->httpClient = new HttpClient($this, $httpClient);

        $this->jsonMapper = new JsonMapper();
        $this->jsonMapper->bStrictNullTypes = false;
        $this->jsonMapper->bIgnoreVisibility = true;
    }

    /**
     * Returns current URL (if available).
     *
     * @return null|string
     */
    private function getCurrentUrl()
    {
        if (php_sapi_name() === 'cli') {
            return null;
        }

        return sprintf(
            '%s://%s%s',
            isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http',
            isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'],
            isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/'
        );
    }

    /**
     * Filters given redirect URL.
     *
     * @param string|null $redirectUrl Redirect URL to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function filterRedirectUrl($redirectUrl = null)
    {
        if ($redirectUrl === null) {
            $redirectUrl = $this->getCurrentUrl();
        }
        $result = filter_var($redirectUrl, FILTER_VALIDATE_URL, [
            'flags' => FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED,
        ]);
        if ($result === false) {
            throw new \InvalidArgumentException('Please provide a valid redirect URL.');
        }

        return $redirectUrl;
    }

    /**
     * Filters given scopes.
     *
     * @param array|null $scopes Scopes to filter.
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    private function filterScopes(array $scopes = null)
    {
        if ($scopes === null) {
            return $scopes;
        }
        if (!count($scopes)) {
            throw new \InvalidArgumentException('Please provide at least one scope.');
        }
        $invalidScopes = array_diff($scopes, $this->getAuth()->getAllScopes());
        if (count($invalidScopes)) {
            throw new \InvalidArgumentException(sprintf(
                'Following scope(s) is not supported: %s.',
                implode(', ', $invalidScopes)
            ));
        }

        return $scopes;
    }

    /**
     * Returns an Instagram authorization URL to redirect your user to.
     *
     * @param array|null  $scopes      A list of additional permissions outside of the “basic” scope to request.
     * @param string|null $state       A server-specific state to carry through.
     * @param string|null $redirectUrl Custom redirect URI.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     *
     * @see https://www.instagram.com/developer/authentication/
     * @see https://www.instagram.com/developer/authorization/
     */
    public function getLoginUrl(array $scopes = null, $state = null, $redirectUrl = null)
    {
        $redirectUrl = $this->filterRedirectUrl($redirectUrl);
        $scopes = $this->filterScopes($scopes);
        // Prepare query string.
        $query = [
            'client_id'     => $this->getAuth()->getClientId(),
            'redirect_uri'  => $redirectUrl,
            'response_type' => 'code',
        ];
        if ($scopes !== null) {
            $query['scope'] = implode(' ', $scopes);
        }
        if ($state !== null) {
            $query['state'] = $state;
        }

        return sprintf('%s?%s', Auth::AUTHORIZE_ENDPOINT, http_build_query($query));
    }

    /**
     * Request the access token from Instagram using given request data.
     *
     * @param array|null  $request     Request data.
     * @param string|null $state       A server-specific state to validate against.
     * @param string|null $redirectUrl The redirect_uri you used in the authorization request.
     *                                 Note: this has to be the same value as in the authorization request.
     *
     * @return Response\AccessTokenResponse|null
     *
     * @see @see https://www.instagram.com/developer/authentication/
     */
    public function getAccessToken(array $request = null, $state = null, $redirectUrl = null)
    {
        if ($request === null) {
            $request = $_GET;
        }
        // Got OAuth error (in most cases, user has denied out request).
        if (isset($request['error'])) {
            Exception\OAuthException::throwFromRequest($request);
        }
        // We have no code yet.
        if (!isset($request['code'])) {
            return null;
        }
        // Check against given state.
        if ($state !== null && (!isset($request['state']) || $request['state'] !== $state)) {
            Exception\OAuthException::throwFromState($request);
        }
        // Prepare redirect URL.
        $redirectUrl = $this->filterRedirectUrl($redirectUrl);
        // Request access token.
        /** @var Response\AccessTokenResponse $response */
        $response = $this->request('POST', Auth::TOKEN_ENDPOINT)
            ->setIsSignedByUser(false)
            ->addParam('grant_type', 'authorization_code')
            ->addParam('redirect_uri', $redirectUrl)
            ->addParam('code', $request['code'])
            ->getResponse(Response\AccessTokenResponse::class);
        $this->getAuth()->setAccessToken($response->getAccessToken());

        return $response;
    }

    /**
     * Builds new request instance.
     *
     * @param string $method   Desired HTTP method.
     * @param string $endpoint Target endpoint.
     *
     * @return Request
     */
    public function request($method, $endpoint)
    {
        return new Request($this, $method, $endpoint);
    }

    /**
     * Endpoints getter.
     *
     * @param $name
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'comments':
                return $this->getCommentsEndpoint();
            case 'likes':
                return $this->getLikesEndpoint();
            case 'locations':
                return $this->getLocationsEndpoint();
            case 'media':
                return $this->getMediaEndpoint();
            case 'relationships':
                return $this->getRelationshipsEndpoint();
            case 'subscriptions':
                return $this->getSubscriptionsEndpoint();
            case 'tags':
                return $this->getTagsEndpoint();
            case 'users':
                return $this->getUsersEndpoint();
            default:
                throw new \RuntimeException(sprintf('Undefined property: "%s"', $name));
        }
    }
}
