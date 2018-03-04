<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNETPacket;

class SID_PING extends BNETPacket {

  const ID = 0x25;

  public $ping_value;

  public function &send() {
    $buffer = new BNETBuffer();
    $buffer->writeByte(0xFF);
    $buffer->writeByte(self::ID);
    $buffer->writeUInt16(8);

    $buffer->writeUInt32($this->ping_value);

    $buffer->setPosition(0);
    Logger::writeLine("SEND: SID_PING", true);
    return $buffer;
  }

  public function receive( &$buffer ) {
    Logger::writeLine("RECV: SID_PING", true);

    $this->ping_value = $buffer->readUInt32();
  }

}
