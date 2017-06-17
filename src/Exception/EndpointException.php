<?php

namespace InstagramRestApi\Exception;

use InstagramRestApi\Response\EndpointResponse;
use InstagramRestApi\ResponseInterface;

class EndpointException extends RequestException
{
    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Constructor.
     *
     * @param string                 $message  The Exception message to throw.
     * @param int                    $code     The Exception code.
     * @param ResponseInterface|null $response The response from endpoint.
     * @param \Exception|null        $previous The previous throwable used for the exception chaining.
     */
    public function __construct($message = '', $code = 0, ResponseInterface $response = null, \Exception $previous = null)
    {
        $httpResponse = $response !== null ? $response->getHttpResponse() : null;
        $this->response = $response;
        parent::__construct($message, $code, $httpResponse, $previous);
    }

    /**
     * @param mixed $response
     */
    public static function throwFromResponse(ResponseInterface $response)
    {
        if ($response instanceof EndpointResponse && $response->getMeta() !== null) {
            $meta = $response->getMeta();
            $message = $meta->getErrorMessage() !== null ? $meta->getErrorMessage() : 'Endpoint error.';
            $code = $meta->getCode() !== null ? $meta->getCode() : 0;
            $errorType = $meta->getErrorType() !== null ? $meta->getErrorType() : null;
        } else {
            $meta = $response->getFullResponse();
            $message = isset($meta->error_message) ? $meta->error_message : 'Endpoint error.';
            $code = isset($meta->code) ? $meta->code : 0;
            $errorType = isset($meta->error_type) ? $meta->error_type : null;
        }
        switch ($errorType) {
            case 'APISubscriptionError':
                $exception = new SubscriptionException($message, $code, $response);
                break;
            case 'APICommentTooManyTagsError':
            case 'APICommentAllCapsError':
            case 'APICommentTooLongError':
            case 'APIInvalidParametersError':
                $exception = new InvalidParametersException($message, $code, $response);
                break;
            case 'APINotFoundError':
                $exception = new NotFoundException($message, $code, $response);
                break;
            case 'OAuthRateLimitException':
                $exception = new RateLimitException($message, $code, $response);
                break;
            case 'OAuthPermissionsException':
                $exception = new MissingPermissionException($message, $code, $response);
                break;
            case 'OAuthForbiddenException':
                $exception = new InvalidSignatureException($message, $code, $response);
                break;
            case 'OAuthAccessTokenException':
                $exception = new InvalidTokenException($message, $code, $response);
                break;
            case 'OAuthException':
            case 'OAuthParameterException':
            case 'OAuthClientException':
                $exception = new OAuthException($message, $code, $response);
                break;
            case 'APIError':
            default:
                $exception = new static($message, $code, $response);
        }

        throw $exception;
    }
}
