<?php

namespace InstagramRestApi\CodeStyle;

use PhpCsFixer\Config as BaseConfig;

class Config extends BaseConfig
{
    /**
     * {@inheritdoc}
     */
    public function __construct($name = 'instapi-cs-config')
    {
        parent::__construct($name);
        $this->setRiskyAllowed(true);
        $this->setRules([
            '@Symfony'                      => true,
            'phpdoc_annotation_without_dot' => false,
            'binary_operator_spaces'        => [
                'align_double_arrow' => true,
                'align_equals'       => false,
            ],
        ]);
    }
}
