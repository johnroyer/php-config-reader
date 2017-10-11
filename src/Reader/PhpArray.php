<?php

namespace Zeroplex\Config\Reader;

use Zeroplex\Config;
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
        $info = pathinfo($file);
        $filePath = $info['dirname'];
        $name = $info['filename'];

        $node = [];
        $cutFrom = strlen($this->config->getBasePath()) + 1;
        $path = substr($filePath, $cutFrom);

        if (!empty($path)) {
            $node = explode('/', $path);
        }

        array_push($node, $name);
        $namespace = implode('.', $node);

        return $namespace;
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
