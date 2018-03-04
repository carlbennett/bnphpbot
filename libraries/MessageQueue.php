<?php

namespace bnphpbot\Libraries;

use \bnphpbot\Libraries\MessageItem;

abstract class MessageQueue {

  protected $queue = null;

  public function __construct() {
    $this->clear();
  }

  public function clear() {
    $this->queue = array();
  }

  public function count() {
    return count( $this->queue );
  }

  public function pop() {
    return array_shift( $this->queue );
  }

  abstract public function process( MessageItem &$message );

  public function push( MessageItem &$message ) {
    return array_push( $this->queue, $message );
  }

}
