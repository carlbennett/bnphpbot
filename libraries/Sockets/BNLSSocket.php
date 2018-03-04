<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZE;
use \bnphpbot\Libraries\Profile;
use \bnphpbot\Libraries\Sockets\TCPSocket;

class BNLSSocket extends TCPSocket {

  protected $buffer;
  protected $initial_handshake;
  protected $profile;
  protected $was_connected;

  public function __construct(Profile &$profile) {
    parent::__construct();
    $this->set_nonblock();
    $this->buffer            = new BNLSBuffer();
    $this->initial_handshake = false;
    $this->profile           = &$profile;
    $this->was_connected     = false;
  }

  public function connect($address = '', $port = 0) {
    $this->initial_handshake = false;
    $this->was_connected     = false;
    $hostname                = (
      !empty( $address ) ? $address : $this->profile->getBNLSHostname()
    );
    $port                    = (
      $port !== 0 ? $port : $this->profile->getBNLSPort()
    );
    $timeout                 = 3;
    $curtime                 = microtime(true);
    $maxtime                 = $curtime + $timeout;
    do {
      $connected = @parent::connect($hostname, $port);
    } while (!$connected && microtime(true) < $maxtime);
    if ($connected) {
      Logger::writeLine("BNLS: Connected in "
        . round((microtime(true) - $curtime) * 1000) . "ms"
      );
    }
    $this->was_connected = $connected;
    return $connected;
  }

  public function getProfile() {
    return $this->profile;
  }

  protected function initialHandshake() {
    $this->initial_handshake = true;

    $pkt = new BNLS_AUTHORIZE( $this );
    $pkt->bot_id = "bnphpbot";

    $this->sendPacket( $pkt );
  }

  public function poll() {
    $connected = $this->connected();
    if ($this->was_connected && !$connected) {
      $this->was_connected = $connected;
      Logger::writeLine("BNLS: Disconnected");
    }
    if (!$connected) return;
    if (!$this->initial_handshake) $this->initialHandshake();
    $data = $this->read(1500, PHP_BINARY_READ);
    if ($data === false) return;
    $this->buffer->writeRaw($data);
    $this->buffer->setPosition(0);
    $this->buffer->parsePacket($this);
  }

  public function sendPacket(&$packet) {
    $buffer = $packet->send(); $len = $buffer->getLength();
    return $this->send($buffer->readRaw($len), $len, 0);
  }

}
