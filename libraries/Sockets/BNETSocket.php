<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Profile;
use \bnphpbot\Libraries\Sockets\TCPSocket;

class BNETSocket extends TCPSocket {

  protected $buffer;
  protected $profile;

  public function __construct(Profile &$profile) {
    parent::__construct();
    $this->buffer  = new BNETBuffer();
    $this->profile = &$profile;
  }

  public function connect() {
    return parent::connect(
      $this->profile->getBattlenetHostname(),
      $this->profile->getBattlenetPort()
    );
  }

  public function poll() {
    $data = "";
    $this->recv($data, 1500, MSG_DONTWAIT);
    if (is_null($data)) return;
    $this->buffer->writeRaw($data);
    $this->buffer->setPosition(0);
    $this->buffer->parsePacket();
  }

}
