<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Sockets\Socket;

abstract class TCPSocket extends Socket {

  public function __construct() {
    // What about AF_INET6?
    parent::__construct(AF_INET, SOCK_STREAM, SOL_TCP);
  }

  public abstract function poll();

}
