<?php

namespace Noodlehaus\Writer\Test;

use Noodlehaus\Writer\Yaml;
use PHPUnit\Framework\TestCase;

class YamlTest extends TestCase
{
    /**
     * @var Yaml
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
        $this->writer = new Yaml();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.yaml');
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
     * @covers Noodlehaus\Writer\Yaml::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['yaml'];
        $actual = $this->writer->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Yaml::toString()
     */
    public function testEncodeYaml()
    {
        $actual = $this->writer->toString($this->data);
        $expected = <<<'EOD'
application:
    name: configuration
    secret: s3cr3t
host: localhost
port: 80
servers:
    - host1
    - host2
    - host3

EOD;
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Yaml::toString()
     * @covers Noodlehaus\Writer\Yaml::toFile()
     */
    public function testWriteYaml()
    {
        $this->writer->toFile($this->data, $this->temp_file);
        $this->assertFileExists($this->temp_file);
        $this->assertEquals(file_get_contents($this->temp_file), file_get_contents(__DIR__.'/../mocks/pass/config4.yaml'));
    }

    /**
     * @covers Noodlehaus\Writer\Yaml::toString()
     * @covers Noodlehaus\Writer\Yaml::toFile()
     * @expectedException        Noodlehaus\Exception\WriteException
     * @expectedExceptionMessage There was an error writing the file
     */
    public function testUnwritableFile()
    {
        chmod($this->temp_file, 0444);

        $this->writer->toFile($this->data, $this->temp_file);
    }
}
