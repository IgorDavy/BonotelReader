<?php
use voku\helper\HtmlDomParser;;

class Hotel {
  private $m_xml_hotel;
  private $m_code;
  private $m_recreation;
  private $m_facilities;
  private $m_facilities_dom;
  private $m_facilities_li;
  private $m_images;
  private $m_get_image_size;

  public function __construct($_xml_hotel, $_get_image_size){
    $this->m_xml_hotel = $_xml_hotel;
    $this->m_code = $_xml_hotel->hotelCode;
    $this->m_recreation = $this->m_xml_hotel->recreation;
    $this->m_facilities = $this->m_xml_hotel->facilities;
    $this->m_images = $this->m_xml_hotel->images;

    // We can use DOM to browse li tags and check text inside,
    // But it seems better to search directly in plain text
    $this->m_facilities_dom = HtmlDomParser::str_get_html($this->m_xml_hotel->facilities->__toString());
    $this->m_facilities_li = $this->m_facilities_dom->find('li');

    // This parameter allow to choose if we want get image size
    // If true parsing big files will be very long
    $this->m_get_image_size = $_get_image_size;
  }

  public function get_code(){
    return $this->m_code;
  }

  public function parse(){
    $hotel_array = array(
      'latitude' => floatval($this->m_xml_hotel->latitude),
      'longitude' => floatval($this->m_xml_hotel->longitude),
      'language' => 'EN',
      'rating_level' => floatval($this->m_xml_hotel->starRating->__toString()),
      'swimming_pool' => $this->has_swimming_pool(),
      'parking' => $this->has_parking(),
      'fitness' => $this->has_fitness(),
      'golf' => $this->has_golf(),
      'seaside' => $this->has_seaside(),
      'spa' => $this->has_spa(),
      'charm' => $this->has_charm(),
      'ecotourism' => $this->has_ecotourism(),
      'exceptional' => $this->has_exceptional(),
      'family_friendly' => $this->has_family_friendly(),
      'pmr' => $this->has_pmr(),
      'preferred' => $this->has_preferred(),
      'wedding' => $this->has_wedding(),
      'distribution'=> array(
        'BONOTEL'=> $this->m_code->__toString()
      ),
      'introduction_text' => array(
        'language'=> 'EN',
        'type_code'=> 'Description',
        'title'=> 'Description',
        'text'=> $this->m_xml_hotel->description->__toString()
      ),
      'introduction_media' => $this->parse_images()
    );
    return $hotel_array;
  }

  private function parse_images(){
    $introduction_media =  array();
    foreach($this->m_images->image as $image){
      if ($this->m_get_image_size){
        $image_size = getimagesize($image->__toString());
        $width = $image_size[0];
        $height = $image_size[1];

        $array_image = array(
          'url' => $image->__toString(),
          'size' => array(
            'width' => $width,
            'height' => $height,
            'unit' => 'px'
          )
        );
      }
      else {
        $array_image = array(
          'url' => $image->__toString(),
          'size' => array(
            'width' => 'disabled',
            'height' => 'disabled',
            'unit' => 'px'
          )
        );
      }
      array_push($introduction_media, $array_image);
    }
    return $introduction_media;
  }

  private function has_pmr(){
    // For Pmr property we test contents of li tags
    foreach($this->m_facilities_li as $element){
      if(stripos($element->innertext, 'mobility accessible rooms') !== false) {
        return true;
      }
    }
    return false;
  }

  private function has_swimming_pool(){
    return stripos($this->m_recreation, 'swimming pool') !== false;
  }

  private function has_fitness(){
    return stripos($this->m_recreation, 'fitness') !== false;
  }

  private function has_golf(){
    return stripos($this->m_recreation, 'golf') !== false;
  }

  private function has_spa(){
    // spa can be in other world like "spaces",
    // so we search for exact world with regular expression
    // spa or spas (case insensitive)
    $_search = 'spas';
    return preg_match("/\b$_search\b/i", $this->m_recreation) == 1;
  }

  private function has_charm(){
    return stripos($this->m_recreation, 'charm') !== false;
  }

  private function has_ecotourism(){
    return stripos($this->m_recreation, 'ecotourism') !== false;
  }

  private function has_wedding(){
    return stripos($this->m_recreation, 'wedding') !== false;
  }

  private function has_parking(){
    foreach($this->m_facilities_li as $_element){
      $_text = $_element->innertext;
      if((stripos($_text, 'self parking')) || (stripos($_text, 'self-parking')) !== false) {
        return true;
      }
    }
    return false;
  }

  private function has_seaside(){
    return stripos($this->m_facilities, 'seaside') !== false;
  }

  private function has_family_friendly(){
    return stripos($this->m_facilities, 'family friendly') !== false;
  }

  private function has_preferred(){
    return stripos($this->m_facilities, 'preferred') !== false;
  }

  private function has_exceptional(){
    return stripos($this->m_facilities, 'exceptional') !== false;
  }
}
?>
