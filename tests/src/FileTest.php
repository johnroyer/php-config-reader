<?php

use PHPUnit\Framework\TestCase;
use Zeroplex\Config\File;

class FileTest extends TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testAccessChecker()
    {
        $path = TEST_ROOT . '/fixtures';

        $this->assertTrue(File::isAccessible($path));
    }

    public function testAccessFailed()
    {
        $path = '/not/exists';

        $this->assertFalse(File::isAccessible($path));
    }

    public function testFileFinder()
    {
        $path = TEST_ROOT . '/fixtures/config';
        $files = File::getFilesFromDir($path);

        $this->assertEquals(2, count($files));
    }

    public function testFileFinderWithRecursive()
    {
        $path = TEST_ROOT . '/fixtures/config';
        $files = File::getFilesFromDir($path, true);

        $this->assertEquals(4, count($files));
    }

    public function testFileExtGetter()
    {
        $path = TEST_ROOT . '/fixtures/config/storage';
        $files = File::getFilesFromDir($path);

        $this->assertEquals('php', File::getFileExt($files[0]));
    }
}
