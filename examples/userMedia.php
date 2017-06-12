<?php

require __DIR__.'/../vendor/autoload.php';

$configFile = is_file(__DIR__.'/config-local.php') ? __DIR__.'/config-local.php' : __DIR__.'/config.php';
$apiClient = new \InstagramRestApi\Client(include $configFile);

?>
<ul>
    <?php $page = 1; $medias = $apiClient->users->getSelfRecentMedia(10); do {  ?>
        <?php foreach ($medias->getData() as $media) : ?>
            <li><a href="<?= htmlspecialchars($media->getLink()) ?>"><?= htmlspecialchars($media->getLink()) ?></a></li>
        <?php endforeach ?>
    <?php } while (++$page <= 5 && ($medias = $medias->getNextPage()) !== null) ?>
</ul>
