<?php

namespace Noodlehaus\Parser\Test;

use Noodlehaus\Parser\Properties;
use PHPUnit\Framework\TestCase;

class PropertiesTest extends TestCase
{
    /**
     * @var Properties
     */
    protected $properties;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->properties = new Properties();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Noodlehaus\Parser\Properties::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['properties'];
        $actual = $this->properties->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Parser\Properties::parseFile()
     * @covers Noodlehaus\Parser\Properties::parseString()
     * @covers Noodlehaus\Parser\Properties::parse()
     */
    public function testLoadProperties()
    {
        $config = $this->properties->parseFile(__DIR__.'/../mocks/pass/config.properties');

        $this->assertEquals('https://en.wikipedia.org/', @$config['website']);
        $this->assertEquals('English', @$config['language']);
        $this->assertEquals('Welcome to Wikipedia!', @$config['message']);
        $this->assertEquals('valueOverOneLine\\', @$config['key']);
        $this->assertEquals('This is the value that could be looked up with the key "key with spaces".', @$config['key with spaces']);
        $this->assertEquals('This is the value for the key "key:with=colonAndEqualsSign"', @$config['key:with=colonAndEqualsSign']);
        $this->assertEquals('c:\\wiki\\templates', @$config['path']);
    }

    /**
     * @covers Noodlehaus\Parser\Ini::parseFile()
     * @covers Noodlehaus\Parser\Ini::parse()
     */
    public function testLoadInvalidIniGBH()
    {
        $config = $this->properties->parseFile(__DIR__.'/../mocks/fail/error.properties');

        $this->assertEmpty($config);
    }
}
