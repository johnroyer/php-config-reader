<?php

namespace Zeroplex\Config\Reader;

use Zeroplex\Config\Config;
use Zeroplex\File;

class PhpArray
{
    private $config = null;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getKeyspace(string $file): string
    {
        $nodes = explode('/', $file);
        $output = array_slice($nodes, -1);
        $key = pathinfo($output[0])['filename'];

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
