<?php

if (php_sapi_name() === 'cli') {
    echo 'This script is not made for console. Please open it in your browser.', PHP_EOL;
    die(1);
}

require __DIR__.'/../vendor/autoload.php';

session_start();

$configFile = is_file(__DIR__.'/config-local.php') ? __DIR__.'/config-local.php' : __DIR__.'/config.php';
$apiClient = new \InstagramRestApi\Client(require $configFile);

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = md5(mt_rand(0, PHP_INT_MAX).uniqid());
}
$csrf = $_SESSION['csrf'];

try {
    $accessToken = $apiClient->getAccessToken($_GET, $csrf);
} catch (\InstagramRestApi\Exception\OAuthException $e) {
    printf('Failed to obtain access token: %s', $e->getMessage());
    die();
} catch (\Exception $e) {
    printf('Got an error while obtaining access token: %s', $e->getMessage());
    die();
}

if ($accessToken === null) {
    $loginUrl = $apiClient->getLoginUrl($apiClient->getAuth()->getAllScopes(), $csrf);
    header('Location: ' . $loginUrl);
    die();
}
?>
<dl>
    <dt>Your access token is:</dt>
    <dd><?= htmlspecialchars($accessToken->getAccessToken()) ?></dd>
    <?php if (($user = $accessToken->getUser()) !== null) : ?>
        <dt>User information:</dt>
        <dd>
            <dl>
                <dt>ID:</dt>
                <dd><?= htmlspecialchars($user->getId()) ?></dd>
                <dt>Username:</dt>
                <dd><?= htmlspecialchars($user->getUsername()) ?></dd>
                <dt>Full Name:</dt>
                <dd><?= htmlspecialchars($user->getFullName()) ?></dd>
                <dt>Photo:</dt>
                <dd><img src="<?= htmlspecialchars($user->getProfilePicture()) ?>" alt="Photo" /></dd>
            </dl>
        </dd>
    <?php endif ?>
</dl>
