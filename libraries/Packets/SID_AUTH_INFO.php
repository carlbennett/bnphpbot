<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNETPacket;

class SID_AUTH_INFO extends BNETPacket {

  const ID = 0x50;

  public $protocol_id;
  public $platform_id;
  public $product_id;
  public $version_byte;
  public $product_language;
  public $local_ip;
  public $timezone_bias;
  public $locale_id;
  public $language_id;
  public $country_abbreviation;
  public $country;

  public function &send() {
    $buffer = new BNETBuffer();
    $buffer->writeByte(0xFF);
    $buffer->writeByte(self::ID);
    $buffer->writeUInt16(4);
    
    $buffer->writeUInt32($this->protocol_id);
    $buffer->writeUInt32($this->platform_id);
    $buffer->writeUInt32($this->product_id);
    $buffer->writeUInt32($this->version_byte);
    $buffer->writeUInt32($this->product_language);
    $buffer->writeUInt32($this->local_ip);
    $buffer->writeUInt32($this->timezone_bias);
    $buffer->writeUInt32($this->locale_id);
    $buffer->writeUInt32($this->language_id);
    $buffer->writeCString($this->country_abbreviation);
    $buffer->writeCString($this->country);

    $buffer->setPosition(2);
    $buffer->writeUInt16($buffer->getLength());
    $buffer->setPosition(0);
    Logger::writeLine("SEND: SID_AUTH_INFO", true);
    return $buffer;
  }

  public function receive(&$socket, &$buffer) {
    Logger::writeLine("RECV: SID_AUTH_INFO", true);
    $socket->close();
  }

}
