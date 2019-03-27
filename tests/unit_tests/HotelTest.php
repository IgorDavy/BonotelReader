<?php
use PHPUnit\Framework\TestCase;
require_once('./src/Hotel.php');

final class HotelTest extends TestCase {
  public function testCanBeCreatedFromValidXmlObject(): Hotel {
    $xml_str = "<hotel>
        <hotelCode>1</hotelCode>
        <latitude>36.112400</latitude>
        <longitude>-115.174716</longitude>
        <starRating>5.5star</starRating>
        <images>
          <image>http://legacy.bonotel.com/photos/22791.jpg</image>
          <image>http://legacy.bonotel.com/photos/22792.jpg</image>
        </images>
        <description><![CDATA[The hotel rooms and suites at the Bellagio offer the perfect blend of beauty and elegance.]]></description>
        <facilities>
          <![CDATA[<ul>
          <li>Fitness center access for guests 18+</li>
          <li>Self-Parking</li>
          <li>Valet Parking:</li>
          <li>Mobility accessible rooms</li>
          </ul>]]>
        </facilities>
        <recreation>
          <![CDATA[<ul>
          <li>Casino</li>
          <li>Five outdoor pools, 4 spas and 52 private cabanas in a Mediterranean courtyard setting (seasonal)</li>
          <li>Spa Bellagio: massage therapies, hydrotherapy services and new unique body treatments, steam rooms, saunas, and an exercise room with Cybex equipment</li>
          </ul>]]>
        </recreation>
      </hotel>";
    $xml_object = new SimpleXMLElement($xml_str);
    $hotel = new Hotel($xml_object, false);
    $this->assertInstanceOf(
      Hotel::class,
      $hotel
    );
    return $hotel;
  }

  /**
   * @depends testCanBeCreatedFromValidXmlObject
  */
  public function testParseHotel($hotel): void {
    $hotel_array = $hotel->parse();
    $this->assertTrue($hotel_array['spa']);
    $this->assertTrue($hotel_array['parking']);
    $this->assertFalse($hotel_array['charm']);
    $this->assertTrue($hotel_array['pmr']);
    $this->assertSame($hotel_array['latitude'], 36.1124);
    $this->assertSame($hotel_array['longitude'], -115.174716);
    $this->assertSame($hotel_array['language'], 'EN');
    $this->assertSame($hotel_array['rating_level'], 5.5);
  }
}
