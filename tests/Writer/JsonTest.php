<?php

namespace Noodlehaus\Test\Writer;

use Noodlehaus\Writer\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @var Json
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
        $this->writer = new Json();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.json');
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
     * @covers \Noodlehaus\Writer\Json::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['json'];
        $actual = $this->writer->getSupportedExtensions();
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \Noodlehaus\Writer\Json::toString()
     */
    public function testEncodeJson()
    {
        $actual = $this->writer->toString($this->data, false);
        $expected = '{"application":{"name":"configuration","secret":"s3cr3t"},"host":"localhost","port":80,"servers":["host1","host2","host3"]}';

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \Noodlehaus\Writer\Json::toString()
     * @covers \Noodlehaus\Writer\Json::toFile()
     */
    public function testWriteJson()
    {
        $this->writer->toFile($this->data, $this->temp_file);

        $this->assertFileExists($this->temp_file);
        $this->assertFileEquals($this->temp_file, __DIR__.'/../mocks/pass/config4.json');
    }

    /**
     * @covers \Noodlehaus\Writer\Json::toString()
     * @covers \Noodlehaus\Writer\Json::toFile()
     */
    public function testUnwritableFile()
    {
        $this->expectException(\Noodlehaus\Exception\WriteException::class);
        $this->expectExceptionMessage('There was an error writing the file');
        chmod($this->temp_file, 0444);

        $this->writer->toFile($this->data, $this->temp_file);
    }
}
