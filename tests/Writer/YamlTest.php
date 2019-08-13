<?php

namespace Noodlehaus\Writer\Test;

use Noodlehaus\Writer\Yaml;
use PHPUnit\Framework\TestCase;

class YamlTest extends TestCase
{
    /**
     * @var Yaml
     */
    protected $Yaml;

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
        $this->Yaml = new Yaml();
        $this->temp_file = tempnam(sys_get_temp_dir(), 'config.yaml');
        // $this->temp_file = __DIR__.'/../mocks/temp/config.yaml';
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
        $actual = $this->Yaml->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Yaml::toString()
     * @covers Noodlehaus\Writer\Yaml::toFile()
     */
    public function testWriteYaml()
    {
        $this->Yaml->toFile($this->data, $this->temp_file);
        $this->assertFileExists($this->temp_file);
        $this->assertEquals(sha1_file($this->temp_file), sha1_file(__DIR__.'/../mocks/pass/config2.yaml'));
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

        $this->Yaml->toFile($this->data, $this->temp_file);
    }
}
