<?php

namespace Zeroplex\Config\Reader;

use Zeroplex\Config\Config;
use Zeroplex\Config\Reader\PhpArray;

class Factory
{
    public static function getReader(Config $config, $configFile)
    {
        if ('php' == pathinfo($configFile)['extension']) {
            return new PhpArray($config);
        }

        throw new \Exception('un-supported file type');
    }
}
