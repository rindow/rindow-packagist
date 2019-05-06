<?php
use Rindow\Packagist\Command\Builder;

return [
    'console' => [
        'commands' => [
            $namespace => [
                'build' => [
                    'component' => Builder::class,
                    'method' => 'build',
                ],
            ],
        ],
    ],
    'container' => [
        'component_paths' => [
            __DIR__.'/../../Command' => true,
        ],
    ],
];
