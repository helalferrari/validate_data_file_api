<?php
namespace App;

class Utils {

  public function completeWithZero($digits, $amount_digits) {
    $amount_missing_zeros = $amount_digits - strlen((string) $digits);

    $zeros = '';
    for ($i=0; $i < $amount_missing_zeros; $i++) {
      $zeros .= '0';
    }
    return $zeros . $digits;
  }

  public function sanitizeAccents($str) {
    $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
    $to = "aaaaeeiooouucAAAAEEIOOOUUC";

    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    return strtr($str, $mapping);
  }

  public function array_find($needle, array $haystack) {
    foreach ($haystack as $key => $value) {
        if (false !== stripos($needle, $value)) {
            return $key;
        }
    }
    return false;
  }
}
