<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNETPacket;

class SID_NULL extends BNETPacket {

  const ID = 0x00;

  public function &send() {
    $buffer = new BNETBuffer();
    $buffer->writeByte(0xFF);
    $buffer->writeByte(self::ID);
    $buffer->writeUInt16(4);
    $buffer->setPosition(0);
    Logger::writeLine("SEND: SID_NULL", true);
    return $buffer;
  }

  public function receive( &$buffer ) {
    Logger::writeLine("RECV: SID_NULL", true);
  }

}
