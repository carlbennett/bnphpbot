<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Profile;
use \bnphpbot\Libraries\Sockets\TCPSocket;

class BNLSSocket extends TCPSocket {

  protected $buffer;
  protected $profile;

  public function __construct(Profile &$profile) {
    parent::__construct();
    $this->buffer  = new BNLSBuffer();
    $this->profile = &$profile;
  }

  public function connect() {
    return parent::connect(
      $this->profile->getBNLSHostname(),
      $this->profile->getBNLSPort()
    );
  }

  public function poll() {
    $data = "";
    $this->recv($data, 1500, MSG_DONTWAIT);
    $this->buffer->writeRaw($data);
    $this->buffer->setPosition(0);
    $this->buffer->parsePacket();
  }

}
