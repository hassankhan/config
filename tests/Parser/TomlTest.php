<?php

namespace Noodlehaus\Parser\Test;

use PHPUnit\Framework\TestCase;
use Noodlehaus\Parser\Toml;

class TomlTest extends TestCase
{
    /**
     * @var Toml
     */
    protected $toml;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->toml = new Toml();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Noodlehaus\Parser\Toml::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['toml'];
        $actual   = $this->toml->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers                   Noodlehaus\Parser\Toml::parseFile()
     * @covers                   Noodlehaus\Parser\Toml::parse()
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage Error parsing TOML file
     */
    public function testLoadInvalidTomlFile()
    {
        $this->toml->parseFile(__DIR__ . '/../mocks/fail/error.toml');
    }

    /**
     * @covers                   Noodlehaus\Parser\Toml::parseString()
     * @covers                   Noodlehaus\Parser\Toml::parse()
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage Error parsing TOML string
     */
    public function testLoadInvalidTomlString()
    {
        $this->toml->parseString(file_get_contents(__DIR__ . '/../mocks/fail/error.toml'));
    }

    /**
     * @covers Noodlehaus\Parser\Toml::parseFile()
     * @covers Noodlehaus\Parser\Toml::parse()
     */
    public function testLoadToml()
    {
        $actual = $this->toml->parseFile(__DIR__ . '/../mocks/pass/config.toml');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }

    /**
     * @covers Noodlehaus\Parser\Toml::parseString()
     */
    public function testLoadTomlString()
    {
        $actual = $this->toml->parseString(file_get_contents(__DIR__ . '/../mocks/pass/config.toml'));
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}
