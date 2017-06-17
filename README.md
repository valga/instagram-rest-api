# instagram-rest-api 

A PHP wrapper for the Instagram REST and Search APIs.

## Installation

```sh
composer require valga/instagram-rest-api
```

## Basic Usage

To use Instagram API you need to [create an application](https://www.instagram.com/developer/clients/register/) (if you don't have one yet).

```php
$apiClient = new \InstagramRestApi\Client([
    'clientId' => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'accessToken' => 'YOUR_ACCESS_TOKEN', // or null, if you don't have it yet
    'enforceSignedRequests' => false, // or true, if you have enabled this feature
]);
```

### Obtaining an access token
 
```php
try {
    $result = $apiClient->getAccessToken();
} catch (\Exception $e) {
    die(sprintf('Failed to obtain access token: %s', $e->getMessage()));
}

if ($result === null) {
    header('Location: '.$apiClient->getLoginUrl());
} else {
    printf('Your access token is: %s', $result->getAccessToken());
}
```

### Changing access token on the fly

```php
$apiClient->getAuth()->setAccessToken($newAccessToken);
```

### Obtaining data from API endpoints

All endpoint responses have `getData()` method that returns needed data. 

```php
// Get a username of logged in user.
$user = $apiClient->users->getSelf()->getData();
print_r($user->getUsername());
```

### Available endpoints

```php
$comments = $apiClient->comments;
$comments->get($mediaId);
$comments->add($mediaId, $text);
$comments->delete($mediaId, $commentId);

$locations = $apiClient->locations;
$locations->get($locationId);
$locations->getRecentMedia($locationId);
$locations->search($lat, $lng);

$likes = $apiClient->likes;
$likes->get($mediaId);
$likes->add($mediaId);
$likes->delete($mediaId);

$media = $apiClient->media;
$media->getById($mediaId);
$media->getByShortcode($mediaShortcode);
$media->search($lat, $lng);

$relationships = $apiClient->relationships;
$relationships->approve($userId);
$relationships->ignore($userId);
$relationships->follow($userId);
$relationships->unfollow($userId);
$relationships->getStatus($userId);
$relationships->getFollowing();
$relationships->getFollowers();
$relationships->getPendingUsers();

$subscriptions = $apiClient->subscriptions;
$subscriptions->get();
$subscriptions->add($object, $aspect, $callbackUrl, $verifyToken);
$subscriptions->deleteById($subscriptionId);
$subscriptions->deleteByObject($object);

$tags = $apiClient->tags;
$tags->get($tag);
$tags->getRecentMedia($tag);
$tags->search($query);

$users = $apiClient->users;
$users->getSelf();
$users->getUser($userId);
$users->getSelfRecentMedia();
$users->getUserRecentMedia($userId);
$users->getSelfLikedMedia();
$users->search($query);
```

### Pagination

Just call `getNextPage()` method until it returns `null`.

```php
// Get all media of logged in user.
$userMedia = [];
$result = $apiClient->users->getSelfRecentMedia();
do {
    foreach ($result->getData() as $media) {
        $userMedia[] = $media;
    }
} while (($result = $result->getNextPage()) !== null);
print_r($userMedia);
```

## Advanced usage

### Obtaining an access token

```php
$scopes = ['public_content'];
$requestData = $_GET;
$redirectUrl = 'YOUR_CUSTOM_REDIRECT_URL';
$csrfToken = 'YOUR_CSRF_TOKEN';

try {
    $result = $apiClient->getAccessToken($request, $csrfToken, $redirectUrl);
} catch (\Exception $e) {
    die(sprintf('Failed to obtain access token: %s', $e->getMessage()));
}

if ($result === null) {
    header('Location: '.$apiClient->getLoginUrl($scopes, $csrfToken, $redirectUrl));
    die();
} else {
    printf('Your access token is: %s', $result->getAccessToken());
}
```

### Exceptions system

All high-level exceptions thrown by this library are inherited from `\InstagramRestApi\Exception\RequestException`.

```
RequestException
|- NetworkException - There was some error on network level.
|- InvalidResponseException - Response body is not a valid JSON object.
|- EndpointException
   |- OAuthException
      |- RateLimitException - You have exceeded rate limit.
      |- MissingPermissionException - You don't have required scope.
      |- InvalidSignatureException - Signature is missing or invalid.
      |- InvalidTokenException - Invalid or expired token.
   |- InvalidParametersException
   |- NotFoundException - Requested object does not exist.
   |- SubscriptionException
```

### Rate limits

```php
$result = $apiClient->users->getSelfRecentMedia();
printf('%d/%d', $result->getRateLimitRemaining(), $result->getRateLimit());
```

### Logging and proxy

We use `info` level to log all successful requests with their responses, and `error` level for failed requests (with their responses, if available).

```php
$logger = new Monolog\Logger('instagram');
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__.'/logs/info.log', Monolog\Logger::INFO, false));
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__.'/logs/error.log', Monolog\Logger::ERROR, false));

$guzzleClient = new GuzzleHttp\Client([
    'connect_timeout' => 10.0,
    'timeout'         => 60.0,
    // Use proxy.
    'proxy'           => 'username:password@127.0.0.1:3128',
    // Disable SSL certificate validation.
    'verify'          => false,
]);

$apiClient = new \InstagramRestApi\Client($config, $logger, $guzzleClient);
```