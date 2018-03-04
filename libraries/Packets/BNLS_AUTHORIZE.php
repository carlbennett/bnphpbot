<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZEPROOF;
use \bnphpbot\Libraries\Packets\BNLSPacket;

class BNLS_AUTHORIZE extends BNLSPacket {

  const ID = 0x0E;

  public $bot_id;
  public $server_code;

  public function &send() {
    $buffer = new BNLSBuffer();
    $buffer->writeUInt16(4 + strlen($this->bot_id));
    $buffer->writeByte(self::ID);

    $buffer->writeCString($this->bot_id);

    $buffer->setPosition(0);
    Logger::writeLine("SEND: BNLS_AUTHORIZE", true);
    return $buffer;
  }

  public function receive( &$buffer ) {
    Logger::writeLine("RECV: BNLS_AUTHORIZE", true);

    $this->server_code = $buffer->readUInt32();
  }

}
