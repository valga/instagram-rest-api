<?php

namespace InstagramRestApi;

use InstagramRestApi\Exception\EndpointException;
use InstagramRestApi\Exception\InvalidResponseException;
use InstagramRestApi\Exception\RequestException;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

class Request
{
    /**
     * @var string
     */
    const API_ENDPOINT = 'https://api.instagram.com/v1';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var array
     */
    private $params;

    /**
     * @var bool
     */
    private $isSignedByUser;

    /**
     * Constructor.
     *
     * @param Client $client   Client instance.
     * @param string $method   HTTP method.
     * @param string $endpoint Target endpoint. You can use either absolute URL or URL relative to API_ENDPOINT.
     */
    public function __construct(Client $client, $method, $endpoint)
    {
        $this->client = $client;
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->params = [];
        $this->isSignedByUser = true;
    }

    /**
     * Returns Client instance.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns HTTP method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns target endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Returns request params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Adds param to the request.
     *
     * @param string $key   Param name.
     * @param mixed  $value Param value (will be casted to a string).
     *
     * @return Request
     */
    public function addParam($key, $value)
    {
        if ($value !== null) {
            $this->params[$key] = (string) $value;
        }

        return $this;
    }

    /**
     * Sets isSignedByUser flag.
     *
     * @param bool $value Flag.
     *
     * @return Request
     */
    public function setIsSignedByUser($value)
    {
        $this->isSignedByUser = (bool) $value;

        return $this;
    }

    /**
     * Parses JSON in response body into an object.
     *
     * @param HttpResponseInterface $httpResponse HTTP response.
     *
     * @throws InvalidResponseException
     *
     * @return object
     */
    protected function parseResponse(HttpResponseInterface $httpResponse)
    {
        $responseBody = (string) $httpResponse->getBody();
        $response = json_decode($responseBody);
        if (($errorCode = json_last_error()) !== JSON_ERROR_NONE) {
            $message = sprintf('Failed to decode JSON (%d): %s.', $errorCode, json_last_error_msg());
            $this->getClient()->getLogger()->error($message, [$responseBody]);
            throw new InvalidResponseException($message, $errorCode, $httpResponse);
        }
        if (!is_object($response)) {
            $message = 'Response is not an object.';
            $this->getClient()->getLogger()->error($message, [$responseBody]);
            throw new InvalidResponseException($message, 0, $httpResponse);
        }

        return $response;
    }

    /**
     * Maps object onto Response instance.
     *
     * @param object            $response Object to map.
     * @param ResponseInterface $target   Target Response instance.
     *
     * @return ResponseInterface
     */
    protected function mapResponse($response, ResponseInterface $target)
    {
        /** @var ResponseInterface $result */
        $result = $this->getClient()->getJsonMapper()->map($response, $target);

        return $result;
    }

    /**
     * Checks if provided class is a valid response class.
     *
     * @param string $responseClass Class name to validate.
     *
     * @throws \InvalidArgumentException
     */
    private function validateResponseClass($responseClass)
    {
        if (!class_exists($responseClass)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $responseClass));
        }
        $reflection = new \ReflectionClass($responseClass);
        if (!$reflection->implementsInterface(ResponseInterface::class)) {
            sprintf('Class "%s" must implement ResponseInterface.', $responseClass);
        }
        if ($reflection->isAbstract()) {
            throw new \InvalidArgumentException(sprintf('Class "%s" must not be abstract.', $responseClass));
        }
    }

    /**
     * Runs the request, parses and maps a response to desired class.
     *
     * @param string $responseClass Target class.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return ResponseInterface
     */
    public function getResponse($responseClass)
    {
        $this->validateResponseClass($responseClass);
        // Sign request with appropriate method.
        if ($this->isSignedByUser) {
            $this->getClient()->getAuth()->signUserRequest($this);
        } else {
            $this->getClient()->getAuth()->signAppRequest($this);
        }
        // Prepare params for HTTP client.
        $url = $this->endpoint;
        if (strpos($url, 'https://') !== 0) {
            $url = self::API_ENDPOINT.$url;
        }
        if (count($this->params)) {
            if (strcasecmp($this->method, 'POST') !== 0) {
                $url .= (strpos($url, '?') !== false ? '&' : '?').http_build_query($this->params);
                $body = null;
            } else {
                $body = http_build_query($this->params);
            }
        } else {
            $body = null;
        }
        // Run HTTP request and parse response.
        $httpResponse = $this->getClient()->getHttpClient()->request($this->method, $url, $body);
        $fullResponse = $this->parseResponse($httpResponse);
        $response = $this->mapResponse($fullResponse, new $responseClass($this->client, $httpResponse, $fullResponse));
        // Check whether response is ok.
        if (!$response->isOk()) {
            $context = [
                'request'  => [
                    'method' => $this->method,
                    'url'    => $url,
                    'body'   => $body,
                ],
                'response' => json_decode((string) $httpResponse->getBody(), true),
            ];
            $this->getClient()->getLogger()->error('Response is not ok', $context);
            EndpointException::throwFromResponse($response);
        }

        return $response;
    }
}
