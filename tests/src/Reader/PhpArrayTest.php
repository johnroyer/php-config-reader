<?php

namespace Zeroplex\Config\Test\Reader;

use PHPUnit\Framework\TestCase;
use Zeroplex\Config\Reader\PhpArray;

class PhpArrayTest extends TestCase
{
    private $config = null;
    private $reader = null;

    public function setUp()
    {
        $this->config = $this->mockConfig();

        $this->reader = new PhpArray($this->config);
    }

    public function tearDown()
    {
        $this->config = null;
    }

    public function mockConfig()
    {
        $config = $this->createMock(\Zeroplex\Config\Config::class);
        $config->method('getBasePath')
            ->willReturn(TEST_ROOT . '/fixtures/config');
        $config->method('set');

        return $config;
    }

    /**
     * @dataProvider filePathProvider
     */
    public function testKeySpaceGetter($file, $expected)
    {
        $space = $this->reader->getKeyspace($file);

        $this->assertEquals($expected, $space);
    }

    public function filePathProvider()
    {
        return [
            [
                TEST_ROOT . '/fixtures/config/config.php',
                'config',
            ],
            [
                TEST_ROOT . '/fixtures/config/storage/log.php',
                'storage.log',
            ],
            [
                TEST_ROOT . '/fixtures/config/storage/s3.php',
                'storage.s3',
            ],
        ];
    }

    public function testSettingLoader()
    {
        $config = \Mockery::mock(\Zeroplex\Config\Config::class);
        $config->shouldReceive('getBasePath')
            ->andReturn(TEST_ROOT . '/fixtures/config');
    }
}
