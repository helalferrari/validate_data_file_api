<?php
namespace App;

class CSV {

  private $data;
  private $file_csv_name;
  private $destination_path;
  private $delimiter;
  private $enclosure;

  public function __construct() {

    $this->setFileCSVName('result');
    $this->setData(array());
    $this->setDelimiter(';');
    $this->setEnclosure('"');
  }

  public function setDelimiter($delimiter) {
    $this->delimiter = $delimiter;
  }

  public function setEnclosure($enclosure) {
    $this->enclosure = $enclosure;
  }

  public function setDestinationPath($path) {
    $this->destination_path = $path;
  }

  public function getDelimiter($delimiter) {
    return $this->delimiter;
  }

  public function getEnclosure($enclosure) {
    return $this->enclosure;
  }

  public function getDestinationPath() {
    return $this->destination_path;
  }

  public function setFileCSVName($file_name) {
    $this->file_csv_name = sprintf('%s%s', $file_name, '.csv');
  }

  public function getFileCSVName() {
    return $this->file_csv_name;
  }

  private function setData($data) {
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }

  /**
   * Save a CSV File with fopen.
   */
  public function writeCSVFile($data, $file_name = NULL) {
    if (empty($data)) {
      return NULL;
    }

    // Formatting to csv
    if (count($data) > 1) {
      foreach($data as $line) {
        if (is_array($line)) {
          $lines[] = utf8_decode(implode(';', $line));
        }
        // For $data errors msg doesn't need imploded
        else {
          $lines[] = utf8_decode($line);
        }
      }
      // Last formatting
      $csv_content = implode("\n", $lines);
    }
    else {
      $csv_content = utf8_decode(current($data));
    }

    if ($file_name == NULL) {
      $file_path = $this->getDestinationPath() . $this->file_csv_name;
    }
    else {
      $file_path = $this->getDestinationPath() . $file_name . $this->file_csv_name;
    }
    $fp = fopen($file_path, "w");
    fwrite($fp, $csv_content);
    fclose($fp);
    return $file_path;
  }

  /**
   * Open CSV File with fopen.
   * @param  object $file object file
   */
  public function openCSVFile($uri) {
    $stream = fopen(realpath($uri), 'r');

    if (!$stream) {
      return FALSE;
    }
    return $stream;
  }

  /**
   * Get CSV File content.
   */
  public function getCSVFile($handle) {
    $data = array();
    $header = array();
    $counter = 0;
    while (($line = fgetcsv($handle, 0, $this->getDelimiter(), $this->getEnclosure())) !== FALSE) {
      foreach ($line as $key => $column_value) {
        if ($counter == 0) {
          $header[] = $column_value;
          $data[$counter] = $header;
        }
        else {
          $data[$counter][$header[$key]] = $column_value;
        }
      }
      $counter++;
    }
    $this->setData($data);
  }

  public function createFileCSVName($file_name) {
    global $user;
    $this->setFileCSVName($file_name);
    return $this->getFileCSVName();
  }
}
