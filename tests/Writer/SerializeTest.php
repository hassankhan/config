<?php

namespace Noodlehaus\Test\Writer;

use Noodlehaus\Writer\Serialize;
use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase
{
    /**
     * @var Serialize
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
    protected function setUp(): void
    {
        $this->writer = new Serialize();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.txt');
        $this->data = [
            'application' => [
                'name' => 'configuration',
                'secret' => 's3cr3t',
            ],
            'host' => 'localhost',
            'port' => 80,
            'servers' => [
                'host1',
                'host2',
                'host3',
            ],
        ];
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tear_down()
    {
        unlink($this->temp_file);
    }

    /**
     * @covers \Noodlehaus\Writer\Serialize::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['txt'];
        $actual = $this->writer->getSupportedExtensions();
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \Noodlehaus\Writer\Serialize::toString()
     */
    public function testSerialize()
    {
        $actual = $this->writer->toString($this->data, false);
        $expected = 'a:4:{s:11:"application";a:2:{s:4:"name";s:13:"configuration";s:6:"secret";s:6:"s3cr3t";}s:4:"host";s:9:"localhost";s:4:"port";i:80;s:7:"servers";a:3:{i:0;s:5:"host1";i:1;s:5:"host2";i:2;s:5:"host3";}}';

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \Noodlehaus\Writer\Serialize::toString()
     * @covers \Noodlehaus\Writer\Serialize::toFile()
     */
    public function testWriteSerialize()
    {
        $this->writer->toFile($this->data, $this->temp_file);

        $this->assertFileExists($this->temp_file);
        $this->assertFileEquals($this->temp_file, __DIR__.'/../mocks/pass/config4.txt');
    }

    /**
     * @covers \Noodlehaus\Writer\Serialize::toString()
     * @covers \Noodlehaus\Writer\Serialize::toFile()
     */
    public function testUnwritableFile()
    {
        $this->expectException(\Noodlehaus\Exception\WriteException::class);
        $this->expectExceptionMessage('There was an error writing the file');
        chmod($this->temp_file, 0444);

        $this->writer->toFile($this->data, $this->temp_file);
    }
}
