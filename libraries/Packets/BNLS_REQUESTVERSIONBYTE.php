<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLSPacket;

class BNLS_REQUESTVERSIONBYTE extends BNLSPacket {

  const ID = 0x10;

  public $product_id;
  public $version_byte;

  public function &send() {
    $buffer = new BNLSBuffer();
    $buffer->writeUInt16(7);
    $buffer->writeByte(self::ID);

    $buffer->writeUInt32($this->product_id);

    $buffer->setPosition(0);
    Logger::writeLine("SEND: BNLS_REQUESTVERSIONBYTE", true);
    return $buffer;
  }

  public function receive(&$socket, &$buffer) {
    Logger::writeLine("RECV: BNLS_REQUESTVERSIONBYTE", true);
    $this->product_id   = $buffer->readUInt32();
    $this->version_byte = (
      $this->product_id == 0x00 ? null : $buffer->readUInt32()
    );
  }

}
