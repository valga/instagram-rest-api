<?php

require __DIR__.'/../vendor/autoload.php';

$configFile = is_file(__DIR__.'/config-local.php') ? __DIR__.'/config-local.php' : __DIR__.'/config.php';

// Create a logger.
$logger = new Monolog\Logger('instagram');
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__.'/logs/info.log', Monolog\Logger::INFO, false));
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__.'/logs/error.log', Monolog\Logger::ERROR, false));

// Create a Guzzle client with custom options.
$guzzleClient = new GuzzleHttp\Client([
    // Adjust default timeouts.
    'connect_timeout' => 10.0,
    'timeout'         => 60.0,
    // Use proxy.
    'proxy'           => 'username:password@127.0.0.1:3128',
    // Disable SSL certificate validation.
    'verify'          => false,
]);

$apiClient = new \InstagramRestApi\Client(require $configFile, $logger, $guzzleClient);

print_r($apiClient->users->getSelf()->getData());
