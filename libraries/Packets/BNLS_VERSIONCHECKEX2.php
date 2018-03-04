<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNLSBuffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLSPacket;

class BNLS_VERSIONCHECKEX2 extends BNLSPacket {

  const ID = 0x1A;

  public $product_id;
  public $flags;
  public $cookie;
  public $mpq_filetime;
  public $mpq_filename;
  public $checksum;

  public $success;
  public $version;
  public $version_string;
  public $version_byte;

  public function &send() {
    $buffer = new BNLSBuffer();
    $buffer->writeUInt16(7);
    $buffer->writeByte(self::ID);

    $buffer->writeUInt32($this->product_id);
    $buffer->writeUInt32($this->flags);
    $buffer->writeUInt32($this->cookie);
    $buffer->writeUInt64($this->mpq_filetime);
    $buffer->writeCString($this->mpq_filename);
    $buffer->writeCString($this->checksum);

    $buffer->setPosition(0);
    $buffer->writeUInt16($buffer->getLength());
    $buffer->setPosition(0);
    Logger::writeLine("SEND: BNLS_VERSIONCHECKEX2", true);
    return $buffer;
  }

  public function receive( &$buffer ) {
    Logger::writeLine("RECV: BNLS_VERSIONCHECKEX2", true);

    $this->success        = $buffer->readUInt32();
    $this->version        = $buffer->readUInt32();
    $this->checksum       = $buffer->readUInt32();
    $this->version_string = $buffer->readCString();
    $this->cookie         = $buffer->readUInt32();
    $this->version_byte   = $buffer->readUInt32();

    $profile                    = $this->socket->getProfile();
    $state                      = $profile->getState();
    $state["version_check_id"]  = $this->version;
    $state["version_check_str"] = $this->version_string;
    $state["version_check_crc"] = $this->checksum;
    $state["version_byte"]      = $this->version_byte;

    if ( !$this->success ) {
      Logger::writeLine("BNLS: Version check failed.", true);
    }
  }

}
