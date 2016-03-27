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

  public function &send() {
    $buffer = new BNLSBuffer();
    $buffer->writeUInt16(7);
    $buffer->writeByte(self::ID);

    $buffer->writeUInt32($this->checksum);

    $buffer->setPosition(0);
    Logger::writeLine("SEND: BNLS_AUTHORIZEPROOF", true);
    return $buffer;
  }

  public function receive(&$socket, &$buffer) {
    Logger::writeLine("RECV: BNLS_AUTHORIZEPROOF", true);
    $this->status = $buffer->readUInt32();
    if ($this->status != 0x00) {
      Logger::writeLine("BNLS: You are not authorized to use this server");
      $socket->close(); return;
    }
    $pkt = new BNLS_REQUESTVERSIONBYTE();
    $pkt->product_id = Common::convertBNETProductToBNLS(
      $socket->getProfile()->getBattlenetProduct()
    );
    $socket->sendPacket($pkt);
  }

}
