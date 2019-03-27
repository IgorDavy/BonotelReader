<?php
class FileCreator {
  private $hotels;
  private $directory;
  private $filenamestart;

  public function __construct($_hotels, $_directory, $_filenamestart) {
    $this->hotels = $_hotels;
    $this->directory = $_directory;
    $this->filenamestart = $_filenamestart;
  }

  public function handleHotels() {
    if (!is_dir($this->directory)) {
      mkdir($this->directory, 0777, true);
    }

    foreach ($this->hotels as $hotel_code => $hotel){
      $filename = "hotels/{$this->filenamestart}{$hotel_code}.json";
      echo $filename, PHP_EOL;
      $hotel_json = json_encode($hotel, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      file_put_contents($filename, $hotel_json);
    }
  }
}
?>
