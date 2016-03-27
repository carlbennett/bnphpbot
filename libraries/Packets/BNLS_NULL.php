<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLSPacket;

class BNLS_NULL extends BNLSPacket {

  const ID = 0x00;

  public function &send() {
    $buffer = new BNLSBuffer();
    $buffer->writeByte(self::ID);
    $buffer->writeUInt16(3);
    $buffer->setPosition(0);
    Logger::writeLine("SEND: BNLS_NULL", true);
    return $buffer;
  }

  public function receive(&$socket, &$buffer) {
    Logger::writeLine("RECV: BNLS_NULL", true);
  }

}
