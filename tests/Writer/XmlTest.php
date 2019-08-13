<?php

namespace Noodlehaus\Writer\Test;

use Noodlehaus\Writer\Xml;
use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    /**
     * @var Xml
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
        $this->writer = new Xml();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.xml');
        $this->data = [
            'application' => [
                'name' => 'configuration',
                'secret' => 's3cr3t',
            ],
            'host' => 'localhost',
            'port' => 80,
            'servers' => [
                'server1' => 'host1',
                'server2' => 'host2',
                'server3' => 'host3',
            ],
        ];
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        // unlink($this->temp_file);
    }

    /**
     * @covers Noodlehaus\Writer\Xml::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['xml'];
        $actual = $this->writer->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Xml::toFile()
     */
    public function testWriteXml()
    {
        $this->writer->toFile($this->data, $this->temp_file);

        $this->assertFileExists($this->temp_file);
        $this->assertEquals(sha1_file($this->temp_file), sha1_file(__DIR__.'/../mocks/pass/config2.xml'));
    }

    /**
     * @covers Noodlehaus\Writer\Xml::toString()
     */
    public function testEncodeXml()
    {
        $actual = $this->writer->toString($this->data);
        $expected = file_get_contents(__DIR__.'/../mocks/pass/config2.xml');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Xml::toFile()
     * @expectedException        Noodlehaus\Exception\WriteException
     * @expectedExceptionMessage There was an error writing the file
     */
    public function testUnwritableFile()
    {
        chmod($this->temp_file, 0444);

        $this->writer->toFile($this->data, $this->temp_file);
    }
}
