<?php
namespace Rindow\Packagist;

class Module
{
    public function getConfig()
    {
        $namespace = __NAMESPACE__;
        return require __DIR__ . '/Resources/config/module.config.php';
    }
}
