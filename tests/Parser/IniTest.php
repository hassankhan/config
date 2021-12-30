<?php
namespace Noodlehaus\Parser\Test;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use Noodlehaus\Parser\Ini;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-21 at 22:37:22.
 */
class IniTest extends TestCase
{
    /**
     * @var Ini
     */
    protected $ini;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function set_up()
    {
        $this->ini = new Ini();
    }

    /**
     * @covers Noodlehaus\Parser\Ini::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['ini'];
        $actual   = $this->ini->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers                   Noodlehaus\Parser\Ini::parseFile()
     * @covers                   Noodlehaus\Parser\Ini::parse()
     * Tests the case where an INI string contains no parsable data at all, resulting in parse_ini_string
     * returning NULL, but not setting an error retrievable by error_get_last()
     */
    public function testLoadInvalidIniGBH()
    {
        $this->expectException(\Noodlehaus\Exception\ParseException::class);
        $this->expectExceptionMessage('No parsable content');
        $this->ini->parseFile(__DIR__ . '/../mocks/fail/error2.ini');
    }

    /**
     * @covers                   Noodlehaus\Parser\Ini::parseString()
     * @covers                   Noodlehaus\Parser\Ini::parse()
     */
    public function testLoadInvalidIni()
    {
        if (PHP_VERSION_ID < 70400 && PHP_VERSION_ID >= 50500) {
            $exceptionMessage = "syntax error, unexpected \$end, expecting ']'";
        } else {
            $exceptionMessage = "syntax error, unexpected end of file, expecting ']' in Unknown on line 1";
        }

        $this->expectException(\Noodlehaus\Exception\ParseException::class);
        $this->expectExceptionMessage($exceptionMessage);
        
        $this->ini->parseString(file_get_contents(__DIR__ . '/../mocks/fail/error.ini'));
    }

    /**
     * @covers Noodlehaus\Parser\Ini::parseFile()
     * @covers Noodlehaus\Parser\Ini::parseString()
     * @covers Noodlehaus\Parser\Ini::parse()
     */
    public function testLoadIni()
    {
        $file = $this->ini->parseFile(__DIR__ . '/../mocks/pass/config.ini');
        $string = $this->ini->parseString(file_get_contents(__DIR__ . '/../mocks/pass/config.ini'));

        $this->assertEquals('localhost', $file['host']);
        $this->assertEquals('80', $file['port']);

        /*$this->assertEquals('localhost', $string['host']);
        $this->assertEquals('80', $string['port']);*/
    }

    /**
     * @covers Noodlehaus\Parser\Ini::parseFile()
     * @covers Noodlehaus\Parser\Ini::parseString()
     * @covers Noodlehaus\Parser\Ini::parse()
     * @covers Noodlehaus\Parser\Ini::expandDottedKey()
     */
    public function testLoadIniWithDottedName()
    {
        $file = $this->ini->parseFile(__DIR__ . '/../mocks/pass/config2.ini');
        $string = $this->ini->parseString(file_get_contents(__DIR__ . '/../mocks/pass/config2.ini'));

        $expected = ['host1', 'host2', 'host3'];

        $this->assertEquals($expected, $file['network']['group']['servers']);
        $this->assertEquals('localhost', $file['network']['http']['host']);
        $this->assertEquals('80', $file['network']['http']['port']);

        $this->assertEquals($expected, $string['network']['group']['servers']);
        $this->assertEquals('localhost', $string['network']['http']['host']);
        $this->assertEquals('80', $string['network']['http']['port']);
    }
}
