<?php

namespace InstagramRestApi\Endpoint;

use InstagramRestApi\Endpoint;
use InstagramRestApi\Exception\RequestException;
use InstagramRestApi\Response\EndpointResponse;
use InstagramRestApi\Response\SubscriptionListResponse;
use InstagramRestApi\Response\SubscriptionResponse;

/**
 * User Subscriptions.
 *
 * @see https://www.instagram.com/developer/subscriptions/
 */
class Subscriptions extends Endpoint
{
    /**
     * @var string
     */
    const OBJECT_USER = 'user';

    /**
     * @var string
     */
    const ASPECT_MEDIA = 'media';

    /**
     * Validates given callback URL.
     *
     * @param string $callbackUrl URL to validate.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function filterCallbackUrl($callbackUrl)
    {
        $result = filter_var($callbackUrl, FILTER_VALIDATE_URL, [
            'flags' => FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED,
        ]);
        if ($result === false) {
            throw new \InvalidArgumentException('Please provide a valid callback URL.');
        }

        return $result;
    }

    /**
     * List your current subscriptions.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return SubscriptionListResponse
     *
     * @see https://www.instagram.com/developer/subscriptions/#list-your-subscriptions
     */
    public function get()
    {
        /** @var SubscriptionListResponse $response */
        $response = $this->getClient()->request('GET', '/subscriptions')
            ->setIsSignedByUser(false)
            ->getResponse(SubscriptionListResponse::class);

        return $response;
    }

    /**
     * Create a subscription.
     *
     * @param string $object      The object you'd like to subscribe to (only "user" is supported for now).
     * @param string $aspect      The aspect of the object you'd like to subscribe to (only "media" is supported for now).
     * @param string $callbackUrl URL to call when new data is available.
     * @param string $verifyToken Text to post as a comment on the media object as specified in media-id
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return SubscriptionResponse
     *
     * @see https://www.instagram.com/developer/subscriptions/#create-a-subscription
     */
    public function add($object, $aspect, $callbackUrl, $verifyToken)
    {
        $callbackUrl = $this->filterCallbackUrl($callbackUrl);
        /** @var SubscriptionResponse $response */
        $response = $this->getClient()->request('POST', '/subscriptions')
            ->setIsSignedByUser(false)
            ->addParam('object', $object)
            ->addParam('aspect', $aspect)
            ->addParam('callback_url', $callbackUrl)
            ->addParam('verify_token', $verifyToken)
            ->getResponse(SubscriptionResponse::class);

        return $response;
    }

    /**
     * Remove subscriptions by given object type.
     *
     * @param string $object Either specific object type or "all" to remove all subscriptions.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return EndpointResponse
     *
     * @see https://www.instagram.com/developer/subscriptions/#delete-subscriptions
     */
    public function deleteByObject($object)
    {
        /** @var EndpointResponse $response */
        $response = $this->getClient()->request('DELETE', '/subscriptions')
            ->setIsSignedByUser(false)
            ->addParam('object', $object)
            ->getResponse(EndpointResponse::class);

        return $response;
    }

    /**
     * Remove subscription by given ID.
     *
     * @param int|string $subscriptionId Subscription ID.
     *
     * @throws \InvalidArgumentException
     * @throws RequestException
     *
     * @return EndpointResponse
     *
     * @see https://www.instagram.com/developer/subscriptions/#delete-subscriptions
     */
    public function deleteById($subscriptionId)
    {
        /** @var EndpointResponse $response */
        $response = $this->getClient()->request('DELETE', '/subscriptions')
            ->setIsSignedByUser(false)
            ->addParam('id', $subscriptionId)
            ->getResponse(EndpointResponse::class);

        return $response;
    }
}
