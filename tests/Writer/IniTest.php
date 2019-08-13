<?php

namespace Noodlehaus\Writer\Test;

use Noodlehaus\Writer\Ini;
use PHPUnit\Framework\TestCase;

class IniTest extends TestCase
{
    /**
     * @var Ini
     */
    protected $writer;

    /**
     * @var string
     */
    protected $temp_file;

    /**
     * @var array
     */
    protected $data;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->writer = new Ini();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.ini');
        $this->data = [
            'database' => [
                'host' => 'localhost',
                'port' => '3306',
            ],
            'app' => [
                'name' => 'config',
                'description' => 'Config Reader and Writer',
            ],
        ];
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unlink($this->temp_file);
    }

    /**
     * @covers Noodlehaus\Writer\Ini::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['ini', 'properties'];
        $actual = $this->writer->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Ini::toString()
     */
    public function testEncodeIni()
    {
        $actual = $this->writer->toString($this->data);
        $expected = <<< 'EOD'
[database]
host=localhost
port=3306
[app]
name=config
description=Config Reader and Writer

EOD;
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Ini::toString()
     * @covers Noodlehaus\Writer\Ini::toFile()
     */
    public function testWriteIni()
    {
        $this->writer->toFile($this->data, $this->temp_file);

        $this->assertFileExists($this->temp_file);
        $this->assertEquals(file_get_contents($this->temp_file), file_get_contents(__DIR__.'/../mocks/pass/config4.ini'));
    }

    /**
     * @covers Noodlehaus\Writer\Ini::toString()
     * @covers Noodlehaus\Writer\Ini::toFile()
     * @expectedException        Noodlehaus\Exception\WriteException
     * @expectedExceptionMessage There was an error writing the file
     */
    public function testUnwritableFile()
    {
        chmod($this->temp_file, 0444);

        $this->writer->toFile($this->data, $this->temp_file);
    }
}
