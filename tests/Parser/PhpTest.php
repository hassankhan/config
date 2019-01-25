<?php
namespace Noodlehaus\Parser\Test;

use PHPUnit\Framework\TestCase;
use Noodlehaus\Parser\Php;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-21 at 22:37:22.
 */
class PhpTest extends TestCase
{
    /**
     * @var Php
     */
    protected $php;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->php = new Php();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Noodlehaus\Parser\Php::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['php'];
        $actual   = $this->php->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers                   Noodlehaus\Parser\Php::parseFile()
     * @covers                   Noodlehaus\Parser\Php::parse()
     * @expectedException        Noodlehaus\Exception\UnsupportedFormatException
     * @expectedExceptionMessage PHP data does not return an array
     */
    public function testLoadInvalidPhp()
    {
        $this->php->parseFile(__DIR__ . '/../mocks/fail/error.php');
    }

    /**
     * @covers                   Noodlehaus\Parser\Php::parseFile()
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage PHP file threw an exception
     */
    public function testLoadExceptionalPhpFile()
    {
        $this->php->parseFile(__DIR__ . '/../mocks/fail/error-exception.php');
    }

    /**
     * @covers                   Noodlehaus\Parser\Php::parseString()
     * @covers                   Noodlehaus\Parser\Php::isolate()
     * @expectedException        Noodlehaus\Exception\ParseException
     * @expectedExceptionMessage PHP string threw an exception
     */
    public function testLoadExceptionalPhpString()
    {
        $this->php->parseString(file_get_contents(__DIR__ . '/../mocks/fail/error-exception.php'));
    }

    /**
     * @covers Noodlehaus\Parser\Php::parseFile()
     * @covers Noodlehaus\Parser\Php::parseString()
     * @covers Noodlehaus\Parser\Php::isolate()
     * @covers Noodlehaus\Parser\Php::parse()
     */
    public function testLoadPhpArray()
    {
        $file = $this->php->parseFile(__DIR__ . '/../mocks/pass/config.php');
        $string = $this->php->parseString(file_get_contents(__DIR__ . '/../mocks/pass/config.php'));

        $this->assertEquals('localhost', $file['host']);
        $this->assertEquals('80', $file['port']);

        $this->assertEquals('localhost', $string['host']);
        $this->assertEquals('80', $string['port']);
    }

    /**
     * @covers Noodlehaus\Parser\Php::parseFile()
     * @covers Noodlehaus\Parser\Php::parseString()
     * @covers Noodlehaus\Parser\Php::isolate()
     * @covers Noodlehaus\Parser\Php::parse()
     */
    public function testLoadPhpCallable()
    {
        $file = $this->php->parseFile(__DIR__ . '/../mocks/pass/config-exec.php');
        $string = $this->php->parseString(file_get_contents(__DIR__ . '/../mocks/pass/config-exec.php'));

        $this->assertEquals('localhost', $file['host']);
        $this->assertEquals('80', $file['port']);

        $this->assertEquals('localhost', $string['host']);
        $this->assertEquals('80', $string['port']);
    }

    /**
     * @covers Noodlehaus\Parser\Php::parseFile()
     * @covers Noodlehaus\Parser\Php::parseString()
     * @covers Noodlehaus\Parser\Php::isolate()
     * @covers Noodlehaus\Parser\Php::parse()
     */
    public function testLoadPhpVariable()
    {
        $file = $this->php->parseFile(__DIR__ . '/../mocks/pass/config-var.php');
        $string = $this->php->parseString(file_get_contents(__DIR__ . '/../mocks/pass/config-var.php'));

        $this->assertEquals('localhost', $file['host']);
        $this->assertEquals('80', $file['port']);

        $this->assertEquals('localhost', $string['host']);
        $this->assertEquals('80', $string['port']);
    }
}
