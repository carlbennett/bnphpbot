<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Sockets\Socket;

class TCPSocket extends Socket {

  public function __construct() {
    parent::__construct(AF_INET, SOCK_STREAM, SOL_TCP);
  }

}
