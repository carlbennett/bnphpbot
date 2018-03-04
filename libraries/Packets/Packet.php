<?php

namespace bnphpbot\Libraries\Packets;

abstract class Packet {

  public $socket;

  public function __construct( &$socket ) {
    $this->socket = $socket;
  }

  public abstract function &send();
  public abstract function receive( &$buffer );

}
