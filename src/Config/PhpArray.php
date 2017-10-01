<?php

namespace Zeroplex\Tool\Config;

use Zeroplex\Tool\Config;
use Zeroplex\Tool\File;

class PhpArray
{
    private $config = null;

    public function __construct(\Zeroplex\Tool\Config $config)
    {
        $this->config = $config;
    }

    public function getKeyspace(string $file): string
    {
    }

    public function loadSettings(string $file): void
    {
        $key = self::getKeyspace($file);

        $settings = (function () use ($file) {
            return require $file;
        })();

        if (false !== $settings) {
            $this->config->set($key, $settings);
        }
    }

    public function isPhpExt(string $file): bool
    {
        return 'php' == File::getFileExt($file);
    }
}
