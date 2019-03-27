<?php
use PHPUnit\Framework\TestCase;
require_once('./src/XmlParser.php');

final class XmlParserTest extends TestCase {
  public function testCanBeCreatedFromValidXmlFile(): XmlParser {
    $bonotel_file = file_get_contents('./tests/test.xml');
    $xml_parser = new XmlParser($bonotel_file, false);
    $this->assertInstanceOf(
      XmlParser::class,
      $xml_parser
    );
    return $xml_parser;
  }

  /**
   * @depends testCanBeCreatedFromValidXmlFile
  */
  public function testCanParseValidXml($xml_parser): void {
    $hotels = $xml_parser->parse();
    $this->assertSame($hotels[1]['latitude'], 36.1124);
    $this->assertSame($hotels[1]['longitude'], -115.174716);
    $this->assertSame($hotels[1]['language'], 'EN');
    $this->assertSame($hotels[1]['rating_level'], 5.5);
    $this->assertSame($hotels[5]['latitude'], 36.101517);
    $this->assertSame($hotels[6]['latitude'], 36.120973);
    $this->assertSame($hotels[8]['latitude'], 37.743324);
  }
}
