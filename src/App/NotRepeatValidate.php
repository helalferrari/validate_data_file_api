<?php
namespace App;

class NotRepeatValidate {
  private $utils;

  public function __construct() {
    $this->utils = new Utils();
  }

  public function validate($value, $header_name, &$container) {
    if (empty($container['notrepeat'][$header_name])) {
      $container['notrepeat'][$header_name] = array();
    }

    $result = $this->utils->array_find($value, $container['notrepeat'][$header_name]);
    if (is_numeric($result)) {
      // $result + 2: represent real line in the document
      return array(
        'status' => FALSE,
        'type' => 'not repeat',
        'type_error' => 'cannot be repeat',
        'msg' => sprintf('There was a repetition of this value: %s. On line %u.', $value, $result + 2),
      );
    }

    $container['notrepeat'][$header_name][] = $value;
    return array(
      'status' => TRUE,
    );
  }
}
