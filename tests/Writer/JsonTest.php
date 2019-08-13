<?php

namespace Noodlehaus\Writer\Test;

use Noodlehaus\Writer\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @var Json
     */
    protected $json;

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
        $this->json = new Json();
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
    protected function tearDown()
    {
        unlink($this->temp_file);
    }

    /**
     * @covers Noodlehaus\Writer\Json::getSupportedExtensions()
     */
    public function testGetSupportedExtensions()
    {
        $expected = ['json'];
        $actual = $this->json->getSupportedExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Noodlehaus\Writer\Json::toString()
     * @covers Noodlehaus\Writer\Json::toFile()
     */
    public function testWriteJson()
    {
        $this->json->toFile($this->data, $this->temp_file);

        $file_a = json_decode(file_get_contents(__DIR__.'/../mocks/pass/config.json'), true);
        $file_b = json_decode(file_get_contents($this->temp_file), true);

        $this->assertFileExists($this->temp_file);
        $this->assertEqualsCanonicalizing($file_a, $file_b);
        $this->assertEquals(sha1_file($this->temp_file), sha1_file(__DIR__.'/../mocks/pass/config4.json'));
    }

    /**
     * @covers Noodlehaus\Writer\Json::toString()
     * @covers Noodlehaus\Writer\Json::toFile()
     * @expectedException        Noodlehaus\Exception\WriteException
     * @expectedExceptionMessage There was an error writing the file
     */
    public function testUnwritableFile()
    {
        chmod($this->temp_file, 0444);

        $this->json->toFile($this->data, $this->temp_file);
    }
}
