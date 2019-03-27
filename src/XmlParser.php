<?php
require_once('Hotel.php');
class XmlParser {
  private $m_xml_object;

  public function __construct($_file, $_get_image_size){
    $this->m_xml_object =  simplexml_load_string($_file) or die('Error: Cannot create object');
    $this->m_get_image_size = $_get_image_size;
  }

  public function parse() {
    $hotels = array();
    foreach ($this->m_xml_object as $xml_hotel) {
      $hotel = new Hotel($xml_hotel, $this->m_get_image_size);
      $hotel_code = $hotel->get_code();
      $hotels["$hotel_code"] = $hotel->parse();
    }
    return $hotels;
  }
}
