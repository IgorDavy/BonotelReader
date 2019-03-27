<?php
require_once('src/XmlParser.php');
require_once('src/FileCreator.php');
require_once('vendor/autoload.php');

$BONOTEL_URL = 'http://api.bonotel.com/index.cfm/user/voyagrs_xml/action/hotel';
$HOTELS_DIRECTORY = './hotels';
$FILENAME_START =  'HS_BNO_H_';
$GET_IMAGE_SIZE = false;

echo 'Loading file...', PHP_EOL;
$bonotel_file = file_get_contents($BONOTEL_URL);
// $bonotel_file = file_get_contents('tests/test.xml');

echo 'Parsing xml...', PHP_EOL;
$xml_parser = new XmlParser($bonotel_file, $GET_IMAGE_SIZE);
$hotels = $xml_parser->parse();

echo 'JSON files creation...', PHP_EOL;
$file_creator = new FileCreator($hotels, $HOTELS_DIRECTORY, $FILENAME_START);
$file_creator->handleHotels();
?>
