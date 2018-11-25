<?php
namespace Rindow\Packagist;

use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\Inject;
use Rindow\Stdlib\Entity\EntityTrait;
use Rindow\Console\Command\Arguments;
use Rindow\Console\Exception\InvalidCommaindLineOptionException;
use Rindow\Stdlib\FileUtil\Dir;

/**
 * @Named
 */
class Builder
{
    use EntityTrait;

    /**
    * @Inject({@Named("Rindow\Console\Display\DefaultOutput")})
    */
    protected $output;
    protected $baseUrl = 'https://github.com';
    protected $version = '0.9.x-dev';
    
    public function build($argv)
    {
        $args = new Arguments($argv);
        $arguments = $args->getArguments();
        $packages = [];
        foreach ($arguments as $path) {
            $tmp = $this->exploring($path);
            $packages = array_merge($packages,$tmp);
        }
        $packages = ["packages" => $packages];
        $this->output->writeln(json_encode($packages,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
    }

    protected function exploring(string $path) : array
    {
        $dir = new Dir();
        $pattern = '/composer.json$/';
        $filenames = $dir->glob($path,$pattern);
        $packages = [];
        foreach ($filenames as $filename) {
            $filename = str_replace('\\', '/', $filename);
            if(strpos($filename,'/vendor/')===false) {
                $start = strlen($path)+1;
                $len = strlen($filename) - $start-1 - strlen('composer.json');
                $packageName = substr($filename, $start ,$len);
                $package= $this->makePackage($filename,$packageName);
                $packages = array_merge($packages,$package);
            }
        }
        return $packages;
    }

    protected function makePackage($filename,$packageName) : array
    {
        $composerJson = file_get_contents($filename);
        $composerBson = json_decode($composerJson,$assoc=true);
        $name = $composerBson['name'];
        if(!isset($composerBson['version']))
            $composerBson['version'] = $this->version;
        $composerBson['dist'] = [
            'url' => $this->baseUrl.'/'.$name.'/archive/master.zip',
            'type'=> 'zip',
        ];
        $package = [
            $packageName => [
                'dev-master' => $composerBson,
            ],
        ];
        return $package;
    }
}
