<?php

namespace Noodlehaus\Writer\Test;

use Noodlehaus\Writer\Toml;
use PHPUnit\Framework\TestCase;

class TomlTest extends TestCase
{
    /**
     * @var Toml
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
        $this->writer = new Toml();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.toml');
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
    protected function tearDown()
    {
        unlink($this->temp_file);
    }

    /**
     * @covers Noodlehaus\Writer\Toml::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['toml'];
        $actual = $this->writer->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Toml::toString()
     */
    public function testEncodeToml()
    {
        $actual = $this->writer->toString($this->data);

        $expected = <<<'EOD'
host = "localhost"
port = 80
servers = ["host1", "host2", "host3"]

[application]
name = "configuration"
secret = "s3cr3t"

EOD;
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Toml::toString()
     * @covers Noodlehaus\Writer\Toml::toFile()
     */
    public function testWriteToml()
    {
        $this->writer->toFile($this->data, $this->temp_file);
        $this->assertFileExists($this->temp_file);
        $this->assertFileEquals($this->temp_file, __DIR__.'/../mocks/pass/config.toml');
    }

    /**
     * @covers Noodlehaus\Writer\Toml::toString()
     * @covers Noodlehaus\Writer\Toml::toFile()
     * @expectedException        Noodlehaus\Exception\WriteException
     * @expectedExceptionMessage There was an error writing the file
     */
    public function testUnwritableFile()
    {
        chmod($this->temp_file, 0444);

        $this->writer->toFile($this->data, $this->temp_file);
    }
}
