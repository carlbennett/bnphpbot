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
    $this->set_nonblock();
    $this->buffer  = new BNLSBuffer();
    $this->profile = &$profile;
  }

  public function connect() {
    $hostname    = $this->profile->getBNLSHostname();
    $port        = $this->profile->getBNLSPort();
    $timeout     = 3;
    $maxtime     = microtime(true) + $timeout;
    do {
      $connected = @parent::connect($hostname, $port);
    } while (!$connected && microtime(true) < $maxtime);
    if ($connected) $this->set_nonblock();
    return $connected;
  }

  public function poll() {
    if (!$this->connected()) return;
    $data = $this->read(4096, PHP_BINARY_READ);
    if ($data === false) return;
    $this->buffer->writeRaw($data);
    $this->buffer->setPosition(0);
    $this->buffer->parsePacket();
  }

}
