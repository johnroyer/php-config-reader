<?php

namespace Zeroplex\Tool;

class File
{
    public static function isAccessible(string $path): bool
    {
        return is_dir($path) && is_readable($path);
    }

    /**
     * Get file ext
     *
     * @param string $file
     * @return string file extension, empty string if not found
     * @throws \InvalidArgumentException if input data is not a file
     */
    public static function getFileExt(string $file): string
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException(' not a file');
        }

        $info = pathinfo($file);

        if (!isset($info['extension'])) {
            return '';
        }
        return $info['extension'];
    }


    /**
     * Scan and get all file from folder
     *
     * @param string $dir the folder you want to scan
     * @param bool $recursive also get files in subfolders
     * @return array a list of files with absolute path
     */
    public static function getFilesFromDir(string $dir, bool $recursive)
    {
        if (!is_readable($dir)) {
            return []; // @codeCoverageIgnore
        }

        $list = scandir($dir);
        if (false === $list) {
            return []; // @codeCoverageIgnore
        }

        $files = [];
        foreach ($list as $file) {
            if ('.' == $file || '..' == $file) {
                // not regular file, ignore it
                continue;
            }

            // 使用絕對路徑
            $file = $dir . '/' . $file;

            if (is_file($file)) {
                $files[] = $file;
            }

            if (is_dir($file) && $recursive) {
                // scan folder recursively if needed
                $files = array_merge($files, self::getFileFromDir($file));
            }
        }
        return $files;
    }
}
