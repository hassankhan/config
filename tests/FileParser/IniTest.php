<?php
namespace mhndev\config\FileParser\Test;

use mhndev\config\FileParser\Ini;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-21 at 22:37:22.
 */
class IniTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ini
     */
    protected $ini;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->ini = new Ini();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Noodlehaus\FileParser\Ini::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = array('ini');
        $actual   = $this->ini->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers                   Noodlehaus\FileParser\Ini::parse()
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage No parsable content in file.
     * Tests the case where an .ini file contains no parsable data at all, resulting in parse_ini_file
     * returning NULL, but not setting an error retrievable by error_get_last()
     */
    public function testLoadInvalidIniGBH()
    {
        $this->ini->parse(__DIR__ . '/../mocks/fail/error2.ini');
    }

    /**
     * @covers                   Noodlehaus\FileParser\Ini::parse()
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage syntax error, unexpected $end, expecting ']'
     */
    public function testLoadInvalidIni()
    {
        $this->ini->parse(__DIR__ . '/../mocks/fail/error.ini');
    }

    /**
     * @covers Noodlehaus\FileParser\Ini::parse()
     */
    public function testLoadIni()
    {
        $actual = $this->ini->parse(__DIR__ . '/../mocks/pass/config.ini');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }

    /**
     * @covers Noodlehaus\FileParser\Ini::parse()
     * @covers Noodlehaus\FileParser\Ini::expandDottedKey()
     */
    public function testLoadIniWithDottedName()
    {
        $actual = $this->ini->parse(__DIR__ . '/../mocks/pass/config2.ini');
        $expected = array('host1', 'host2', 'host3');

        $this->assertEquals($expected, $actual['network']['group']['servers']);

        $this->assertEquals('localhost', $actual['network']['http']['host']);
        $this->assertEquals('80', $actual['network']['http']['port']);
    }
}
