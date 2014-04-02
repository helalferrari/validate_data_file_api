<?php
namespace App;

class CNPJ {
  private $utils;

  public function __construct() {
    $this->utils = new Utils();
  }

  /**
   * Validate cnpj.
   */
  public function validate($value) {

    $value = preg_replace("/[^0-9]/", "", $value);

    // Check if this value contains 14 digits
    if (strlen((string) $value) < 14) {
      $value = $this->utils->completeWithZero($value, 14);
    }

    if (!$this->cnpjValidator($value)) {
      return array(
        'status' => FALSE,
        'type' => 'cnpj',
        'type_error' => 'invalid cnpj',
        'msg' => sprintf('Invalid CNPJ.'),
      );
    }

    return array(
      'status' => TRUE,
      'value' => $value,
    );
  }

  private function cnpjValidator($cnpj) {

    $ignore_list = array(
      '00000000000000',
    );

    if(strlen($cnpj) != 14 || in_array($cnpj, $ignore_list)){
        return false;
    }

    $calcular = 0;
    $calcularDois = 0;
    for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
        $x = ($x < 2) ? 9 : $x;
        $number = substr($cnpj, $i, 1);
        $calcular += $number * $x;
    }
    for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
        $x = ($x < 2) ? 9 : $x;
        $numberDois = substr($cnpj, $i, 1);
        $calcularDois += $numberDois * $x;
    }

    $digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
    $digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);

    if ($digitoUm <> substr($cnpj, 12, 1) || $digitoDois <> substr($cnpj, 13, 1)) {
        return false;
    }
    return true;
  }
}

