<?php

use PHPUnit\Framework\TestCase;
use Zeroplex\Config\Config;

class ConfigTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function tearDown(): void
    {
    }

    public function testConstructorWithUnsupportType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $config = new Config(
            TEST_ROOT . '/fixtures/config/not-exists'
        );
    }

    public function testUsingSingleValidConfig()
    {
        $config = new Config(
            TEST_ROOT . '/fixtures/config/config.php'
        );

        $this->assertEquals(Config::class, get_class($config));

        return $config;
    }

    /**
     * @depends testUsingSingleValidConfig
     */
    public function testConfigGetSimple($config)
    {
        $this->assertEquals('hello', $config->get('config.say'));
    }

    /**
     * @depends testUsingSingleValidConfig
     */
    public function testConfigGetNested($config)
    {
        $this->assertEquals(
            'secret',
            $config->get('config.db.mysql.password')
        );
    }

    public function testUsingConfigDir()
    {
        $config = new Config(
            TEST_ROOT . '/fixtures/config/storage'
        );

        $this->assertEquals(
           'www',
           $config->get('s3.bucket')
        );
    }
}
