<?php

namespace Zeroplex\Config\Reader;

use Zeroplex\Config\Config;
use Zeroplex\File;

class PhpArray
{
    private $config = null;
    private $includePath = '';

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function setIncludePath(string $path)
    {
        $this->includePath = $path;
    }

    public function getKeyspace(string $file): string
    {
        $nodes = explode('/', $file);
        $output = array_slice($nodes, -1);
        $key = pathinfo($output[0])['filename'];

        $prefix = '';
        if (!empty($this->includePath)) {
            $prefix = array_slice(
                explode('/', $this->includePath),
                -1
            )[0];
            return "$prefix.$key";
        }
        return $key;
    }

    public function loadSettings(string $file): void
    {
        $key = $this->getKeyspace($file);

        $settings = (function () use ($file) {
            return require $file;
        })();

        if (false !== $settings) {
            $this->config->set($key, $settings);
        }
    }
}
