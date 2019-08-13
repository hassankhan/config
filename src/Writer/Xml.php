<?php

namespace Noodlehaus\Writer;

use DOMDocument;
use SimpleXMLElement;
use Noodlehaus\Exception\WriteException;

/**
 * Xml Writer.
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @author     Filip Š <projects@filips.si>
 * @author     Mark de Groot <mail@markdegroot.nl>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Xml implements WriterInterface
{
    /**
     * {@inheritdoc}
     * Writes an array to a Xml file.
     */
    public function toFile($config, $filename)
    {
        $document = $this->toDocument($config);
        $success = @$document->save($filename);
        if ($success === false) {
            throw new WriteException(['file' => $filename]);
        }

        return $document->saveXML();
    }

    /**
     * {@inheritdoc}
     * Writes an array to a Xml string.
     */
    public function toString($config)
    {
        return $this->toDocument($config)->saveXml();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedExtensions()
    {
        return ['xml'];
    }

    /**
     * Converts array to DOM Document.
     * @param array             $arr       Array to be converted
     *
     * @return DOMDocument
     */
    protected function toDocument(array $xml)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->toXML($xml));

        return $dom;
    }

    /**
     * Converts array to XML string.
     * @param array             $arr       Array to be converted
     * @param string            $rootElement I specified will be taken as root element
     * @param SimpleXMLElement  $xml         If specified content will be appended
     *
     * @return string Converted array as XML
     *
     * @see https://www.kerstner.at/2011/12/php-array-to-xml-conversion/
     */
    protected function toXML(array $arr, $rootElement = '<config/>', $xml = null)
    {
        if ($xml === null) {
            $xml = new SimpleXMLElement($rootElement);
        }
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $this->toXML($v, $k, $xml->addChild($k));
            } else {
                $xml->addChild($k, $v);
            }
        }

        return $xml->asXML();
    }
}
