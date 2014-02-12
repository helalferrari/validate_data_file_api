<?php
namespace App;

class NotNullValidate {

  public function validate($value) {
    if (empty($value)) {
      return array(
        'status' => FALSE,
        'type' => 'notnull',
        'type_error' => 'not null',
        'msg' => sprintf('This field is mandatory.'),
      );
    }
    return array(
      'status' => TRUE,
    );
  }
}
