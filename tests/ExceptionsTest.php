<?php

namespace InstagramRestApi\Tests;

use InstagramRestApi\Client\Http;
use InstagramRestApi\Exception\InvalidResponseException;
use InstagramRestApi\Exception\InvalidSignatureException;
use InstagramRestApi\Exception\InvalidTokenException;
use InstagramRestApi\Exception\MissingPermissionException;
use InstagramRestApi\Exception\NetworkException;
use InstagramRestApi\Exception\NotFoundException;
use InstagramRestApi\Exception\OAuthException;
use InstagramRestApi\Exception\RateLimitException;
use InstagramRestApi\Request;
use InstagramRestApi\Response\EndpointResponse;

class ExceptionsTest extends AbstractTestCase
{
    public function testNetworkException()
    {
        $this->guzzleClientMock->expects($this->once())->method('request')
            ->willThrowException(new \RuntimeException('Network error.'));

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Network error.');

        $httpClient = new Http($this->apiClientMock, $this->guzzleClientMock);
        $httpClient->request('GET', 'https://api.instagram.com/');
    }

    /**
     * @covers \Request::parseResponse
     */
    public function testInvalidResponseExceptionWithInvalidJson()
    {
        $this->setHttpResponse([], 'NOT JSON');
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('Failed to decode JSON (4): Syntax error.');
        $this->expectExceptionCode(4);

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    /**
     * @covers \Request::parseResponse
     */
    public function testInvalidResponseExceptionWithNonObjectJson()
    {
        $this->setHttpResponse([], '666');
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('Response is not an object.');

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    public function testInvalidTokenException()
    {
        $this->setHttpResponse([], '{"meta":{"code":400,"error_type":"OAuthAccessTokenException","error_message":"The access_token provided is invalid."}}');
        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('The access_token provided is invalid.');
        $this->expectExceptionCode(400);

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    public function testInvalidSignatureException()
    {
        $this->setHttpResponse([], '{"code": 403, "error_type": "OAuthForbiddenException", "error_message": "Signature does not match"}');
        $this->expectException(InvalidSignatureException::class);
        $this->expectExceptionMessage('Signature does not match');
        $this->expectExceptionCode(403);

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    public function testNotFoundException()
    {
        $this->setHttpResponse([], '{"meta":{"code":400,"error_type":"APINotFoundError","error_message":"this user does not exist"}}');
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('This user does not exist.');
        $this->expectExceptionCode(400);

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    public function testRateLimitException()
    {
        $this->setHttpResponse([], '{"meta":{"code":429,"error_type":"OAuthRateLimitException","error_message":"The maximum number of requests per hour has been exceeded."}}');
        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('The maximum number of requests per hour has been exceeded.');
        $this->expectExceptionCode(429);

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    public function testMissingPermissionException()
    {
        $this->setHttpResponse([], '{"meta":{"code":400,"error_type":"OAuthPermissionsException","error_message":"This request requires scope=public_content, but this access token is not authorized with this scope. The user must re-authorize your application with scope=public_content to be granted this permissions."}}');
        $this->expectException(MissingPermissionException::class);
        $this->expectExceptionMessage('This request requires scope=public_content, but this access token is not authorized with this scope. The user must re-authorize your application with scope=public_content to be granted this permissions.');
        $this->expectExceptionCode(400);

        $request = new Request($this->apiClientMock, 'GET', '/');
        $request->getResponse(EndpointResponse::class);
    }

    public function testOAuthExceptionFromRequest()
    {
        $request = [
            'error_reason'     => 'user_denied',
            'error'            => 'access_denied',
            'error_description'=> 'The user denied your request.',
        ];
        $this->expectException(OAuthException::class);
        $this->expectExceptionMessage('The user denied your request.');

        OAuthException::throwFromRequest($request);
    }

    public function testOAuthExceptionFromMissingState()
    {
        $this->expectException(OAuthException::class);
        $this->expectExceptionMessage('Required parameter "state" is missing.');

        OAuthException::throwFromState([]);
    }

    public function testOAuthExceptionFromState()
    {
        $request = [
            'state' => 'csrf',
        ];
        $this->expectException(OAuthException::class);
        $this->expectExceptionMessage('Invalid "state" parameter value: "csrf".');

        OAuthException::throwFromState($request);
    }
}
