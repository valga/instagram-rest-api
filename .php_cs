<?php

return InstagramRestApi\CodeStyle\Config::create()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude('examples')
            ->exclude('vendor')
    );