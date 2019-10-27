<?php

namespace Zeroplex\Config;

use Zeroplex\Config\Reader\Factory;
use Zeroplex\Config\File;

class Config
{
    private $config = [];

    private $isloaded = false;

    private $basePath = '';

    public function __construct(string $path)
    {
        if (!File::isAccessible($path)) {
            throw new \InvalidArgumentException('path is not found or not readable');
        }

        $this->config = [];
        $this->filePath = $path;
        $this->isloaded = false;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function load(): void
    {
        $reader = null;
        if (is_dir($this->filePath)) {
            $configs = File::getFilesFromDir($this->filePath, true);
            $reader = Factory::getReader($this, $configs[0]);

            $reader->setIncludePath($this->filePath);
            foreach ($configs as $file) {
                $reader->loadSettings($file);
            }
            return;
        }
        $reader = Factory::getReader($this, $this->filePath);
        $reader->loadSettings($this->filePath);
        return;
    }

    public function get(string $key, $default = null)
    {
        if (!$this->isloaded) {
            $this->load();
        }

        $config = $this->config;
        foreach (explode('.', $key) as $segment) {
            if (is_array($config) && array_key_exists($segment, $config)) {
                // 若 segment name 存在且底下是 array，表示可以繼續往下一層搜尋
                $config = $config[$segment];
                continue;
            }

            // 不是 array 沒辦法繼續往下查詢，表示沒有該項設定
            return $default;
        }

        return $config;
    }

    public function set(string $key, $value): void
    {
        if (empty($key)) {
            return;
        }

        // use array reference
        $config = &$this->config;
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($config[$key]) || !is_array($config[$key])) {
                // 這個 key 不存在，先設定為空陣列，供下一層設定使用
                $config[$key] = [];
            }
            // 這個 key 存在，繼續往下一層檢查
            $config = &$config[$key];
        }

        $key = array_shift($keys);
        $config[$key] = $value;
    }

    public function toArray(): array
    {
        return $this->config;
    }
}
