<?php
return [
    'module_manager' => [
        'version' => 4,
        'modules' => [
            Rindow\Console\Module::class       => true,
            Rindow\Container\Module::class     => true,
            Rindow\Packagist\Module::class     => true,
        ],
        'annotation_manager' => true,
    ],
    'cache' => [
        'filePath' => __DIR__.'/../cache',
    ],
];
