<?php
namespace App;

class Message {
  private $message;

  public function __construct() {
    $this->message = array();
  }

  public function set($msg, $status = 'status') {
    $this->message = array(
      'text' => $msg,
      'status' => $status,
    );

    print $this->show();
  }

  public function show() {
    $message = '<div class="message ' . $this->message['status'] . ' ">';
    $message .= $this->message['text'];
    $message .= '</div>';

    $this->__construct();
    print $message;
  }
}
