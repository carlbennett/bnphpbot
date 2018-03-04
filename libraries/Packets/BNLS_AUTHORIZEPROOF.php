<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLS_REQUESTVERSIONBYTE;
use \bnphpbot\Libraries\Packets\BNLSPacket;

class BNLS_AUTHORIZEPROOF extends BNLSPacket {

  const ID = 0x0F;

  public $checksum;
  public $status;
  public $ip_address;

  public function &send() {
    $buffer = new BNLSBuffer();
    $buffer->writeUInt16(7);
    $buffer->writeByte(self::ID);

    $buffer->writeUInt32( (int) $this->checksum );

    $buffer->setPosition(0);
    Logger::writeLine("SEND: BNLS_AUTHORIZEPROOF", true);
    return $buffer;
  }

  public function receive( &$buffer ) {
    Logger::writeLine("RECV: BNLS_AUTHORIZEPROOF", true);

    $this->status     = $buffer->readUInt32();
    $this->ip_address = $buffer->readUInt32();
  }

}
