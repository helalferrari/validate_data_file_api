<?php
namespace App;

class CNPJ {

  /**
   * Validate cnpj.
   */
  public function validate($value) {
    // Check if this value contains 14 digits
    if (!$this->cnpjValidator($value)) {
      return array(
        'status' => FALSE,
        'type' => 'cpf',
        'type_error' => 'invalid cnpj',
        'msg' => sprintf('Invalid CNPJ.'),
      );
    }

    return array(
      'status' => TRUE,
    );
  }

  private function cnpjValidator($cnpj) {
    $cnpj     = preg_replace('/[^0-9]/', '', $cnpj);
    if(strlen($cnpj) <> 14){
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

