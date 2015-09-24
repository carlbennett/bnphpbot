<?php

namespace bnphpbot\Libraries\Buffers;

use \bnphpbot\Libraries\Buffers\Buffer;
use \bnphpbot\Libraries\Logger;

class BNETBuffer extends Buffer {

  public function parsePacket() {
    if ($this->getLength() < 4) return false;
    $padding = $this->readUInt8();
    $id      = $this->readUInt8();
    $length  = $this->readUInt16();
    if ($padding !== 255) {
      Logger::writeLine("BNETBuffer: Invalid packet padding", true);
      return false;
    }
    if ($this->getLength() < $length) return false;
    switch ($id) {
      case 0x00: {
        Logger::writeLine("BNETBuffer: SID_NULL received", true);
        break;
      }
      default: {
        Logger::writeLine("BNETBuffer: Unknown packet id received", true);
        return false;
      }
    }
  }

}
