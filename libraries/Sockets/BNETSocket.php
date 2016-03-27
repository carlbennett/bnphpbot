<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Logger;
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
    $curtime                 = microtime(true);
    $maxtime                 = $curtime + $timeout;
    do {
      $connected = @parent::connect($hostname, $port);
    } while (!$connected && microtime(true) < $maxtime);
    if ($connected) {
      Logger::writeLine("BNET: Connected in "
        . round((microtime(true) - $curtime) * 1000) . "ms"
      );
      $this->set_nonblock();
    }
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
    $this->sendPacket($pkt);
  }

  public function poll() {
    $connected = $this->connected();
    if ($this->was_connected && !$connected) {
      $this->was_connected = $connected;
      Logger::writeLine("BNET: Disconnected");
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
