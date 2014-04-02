<?php
namespace App;

class Validator {

  private $invalid_lines;
  // This attribute will serve to save something you need to persist in the conext
  private $container = array();
  public $message;
  public $utils;

  public function __construct() {
    $this->message = new Message();
    $this->utils = new Utils();
  }

  public function startValidation(array $header, array &$data, array $map_validations, $redirect_path, $spread_sheet_model_path = NULL) {
    $this->headerColumnsValidate($header, $data[0], $redirect_path, $spread_sheet_model_path);
    $this->invalid_lines = $this->validate($data, $map_validations);
    // return format errors
    $fixed_lines = $this->formatInvalidLinesToSimpleArray();
    return $fixed_lines;
  }

  public function getInvalidLines() {
    return $this->invalid_lines;
  }

  public function setInvalidLines($invalid_lines) {
    $this->invalid_lines = $invalid_lines;
  }

  /**
   * [validate description]
   * @param  array  $data [description]
   * @param  array  $map  [description]
   * @return [type]       [description]
   */
  protected function validate(array &$data, array $map_validations) {
    // I find in this foreach which keys represents my validations types in the csv.
    foreach ($map_validations as $validate_name => $headers) {
      // Execute this regex to delete all special chars of string, I will use this string to call a method of the class
      $method_name = preg_replace('/[^a-zA-Z0-9]/s', '', $validate_name) . 'Validate';
      $methods_validate[$method_name] = $headers;
    }

    // Delete the Header information
    $first_line = array_shift($data);
    $invalid_lines = array();
    foreach ($data as $key => $line) {
      foreach ($methods_validate as $method_validate => $headers) {
        foreach ($headers as $header) {
          $result = $this->{$method_validate}($line[$header], $header);
          if (!$result['status']) {
            unset($data[$key]);
            // To correct position error $invalid_lines[$i][strtoupper($result['type'])]['line'] = $key;
            $invalid_lines[$key][$header][strtoupper($result['type'])]['type error'] = $result['type_error'];
            $invalid_lines[$key][$header][strtoupper($result['type'])]['msg'] = $result['msg'];
          }
          else {
            // Case needs to fix a problem I send again the value to the $data
            $data[$key][$header] = $result['value'];
          }
        }
      }
    }
    array_unshift($data, $first_line);
    return $invalid_lines;
  }

  public function formatInvalidLinesToSimpleArray() {
    $errors = array();
    foreach ($this->invalid_lines as $line_error => $columns_error) {
      foreach ($columns_error as $column_error => $validators) {
        foreach ($validators as $key => $result) {
          // Added plus one in the line error because we need consider the header
          $errors[] = sprintf('%u, %s, %s', $line_error + 2, $column_error, $result['msg']);
        }
      }
    }
    if (count($errors) > 0) {
      array_unshift($errors, sprintf('Line error on, Column, Message'));
    }
    return $errors;
  }

  private function headerColumnsValidate($header_system, $header_data, $redirect_path, $spread_sheet_model_path = NULL) {
    $header = new HeaderColumnsValidate();
    $header->validate($header_system, $header_data, $redirect_path, $spread_sheet_model_path);
  }

  private function cpfValidate($value) {
    $cpf = new CPF();
    return $cpf->validate($value);
  }

  private function cnpjValidate($value) {
    $cnpj = new CNPJ();
    return $cnpj->validate($value);
  }

  private function notnullValidate($value) {
    $nnv = new NotNullValidate();
    return $nnv->validate($value);
  }

  private function notrepeatValidate($value, $header_name) {
    $nrv = new NotRepeatValidate();
    return $nrv->validate($value, $header_name, $this->container);
  }
}
