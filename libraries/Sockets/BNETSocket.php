<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Packets\SID_AUTH_INFO;
use \bnphpbot\Libraries\Profile;
use \bnphpbot\Libraries\Sockets\TCPSocket;

class BNETSocket extends TCPSocket {

  protected $buffer;
  protected $initial_handshake;
  protected $profile;
  protected $was_connected;

  public function __construct(Profile &$profile) {
    parent::__construct();
    $this->buffer            = new BNETBuffer();
    $this->initial_handshake = false;
    $this->profile           = &$profile;
    $this->was_connected     = false;
  }

  public function connect() {
    $this->initial_handshake = false;
    $this->was_connected     = false;
    $hostname                = $this->profile->getBattlenetHostname();
    $port                    = $this->profile->getBattlenetPort();
    $timeout                 = 3;
    $maxtime                 = microtime(true) + $timeout;
    do {
      $connected = @parent::connect($hostname, $port);
    } while (!$connected && microtime(true) < $maxtime);
    if ($connected) $this->set_nonblock();
    $this->was_connected = $connected;
    return $connected;
  }

  protected function initialHandshake() {
    $this->initial_handshake = true;

    $this->send("\x01", 1, 0); // Protocol byte
    
    $pkt = new SID_AUTH_INFO();
    $pkt->protocol_id = 0;
    $pkt->platform_id = strrev($this->profile->getBattlenetPlatform());
    $pkt->product_id = strrev($this->profile->getBattlenetProduct());
    $pkt->version_byte = $this->profile->getBattlenetVersionByte();
    $pkt->product_language = 0;
    $pkt->local_ip = 0;
    $pkt->timezone_bias = 0;
    $pkt->locale_id = 0;
    $pkt->language_id = 0;
    $pkt->country_abbreviation = "USA";
    $pkt->country = "United States";
    $pkt = $pkt->send(); $len = $pkt->getLength();
    $this->send($pkt->readRaw($len), $len, 0);
  }

  public function poll() {
    $connected = $this->connected();
    if ($this->was_connected && !$connected) {
      $this->was_connected = $connected;
      \bnphpbot\Libraries\Logger::writeLine("Disconnected from Battle.net.", true);
      return;
    }
    if (!$this->initial_handshake) $this->initialHandshake();
    $data = $this->read(4096, PHP_BINARY_READ);
    if ($data === false) return;
    $this->buffer->writeRaw($data);
    $this->buffer->setPosition(0);
    $this->buffer->parsePacket($this);
  }

}
