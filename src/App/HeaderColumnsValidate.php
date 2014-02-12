<?php
namespace App;

class HeaderColumnsValidate {
  private $message;
  private $utils;

  public function __construct() {
    $this->message = new Message();
    $this->utils = new Utils();
  }

  public function validate($header_system, $header_data, $redirect_path, $spread_sheet_model_path) {
    // I couldn't use the function array_diff, to know THE because dd($header_system); dd($header_data);
    // Verify here if all headers are presents.
    // Rebuild header_system if number indexs
    $headers_system = array();
    foreach ($header_system as $key => $header_name) {
      $header_system[] = $header_name;
      unset($header_system[$key]);
    }

    // I need remove the accentuations
    foreach ($header_data as $key => $header_name) {
      $header_data[$key] = $this->utils->sanitizeAccents((string) $header_name);
    }

    $headers_not_found = array_udiff($header_system, $header_data, 'strcasecmp');

    if (!empty($headers_not_found)) {
      $download_link = NULL;

      if (!empty($spread_sheet_model_path)) {
        $this->message->set(sprintf('You can do download of the%s.', ' <a href="'. $spread_sheet_model_path . '">model spreadsheet</a>'));
      }

      if (isset($headers_not_found) && count($headers_not_found) > 0) {
        $this->message->set(sprintf('Following columns must exist: %s.', implode(', ', $headers_not_found), 'warning', $redirect_path));
        die;
      }
    }
  }
}
