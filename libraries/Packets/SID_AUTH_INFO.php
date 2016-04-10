<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNETPacket;
use \bnphpbot\Libraries\Packets\BNLS_VERSIONCHECKEX2;

class SID_AUTH_INFO extends BNETPacket {

  const ID = 0x50;

  /* Client -> Server */
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

  /* Server -> Client */
  public $logon_type;
  public $server_token;
  public $udp_token;
  public $mpq_filetime;
  public $mpq_filename;
  public $checksum;
  public $signature;

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
    $this->logon_type   = $buffer->readUInt32();
    $this->server_token = $buffer->readUInt32();
    $this->udp_token    = $buffer->readUInt32();
    $this->mpq_filetime = $buffer->readUInt64();
    $this->mpq_filename = $buffer->readCString();
    $this->checksum     = $buffer->readCString();
    $this->signature    = (
      $buffer->getLength() >= 128 ? $buffer->readRaw(128) : null
    );

    $profile                         = $socket->getProfile();
    $state                           = $profile->getState();
    $state["logon_type"]             = $this->logon_type;
    $state["udp_token"]              = $this->udp_token;
    $state["version_check_mpq_name"] = $this->mpq_filename;
    $state["version_check_mpq_time"] = $this->mpq_filetime;
    $state["version_check_checksum"] = $this->checksum;
    $state["server_signature"]       = $this->signature;

    $pkt = new BNLS_VERSIONCHECKEX2();

    $pkt->product_id   = Common::convertBNETProductToBNLS(
      $profile->getBattlenetProduct()
    );
    $pkt->flags        = 0;
    $pkt->cookie       = mt_rand();
    $pkt->mpq_filetime = $this->mpq_filetime;
    $pkt->mpq_filename = $this->mpq_filename;
    $pkt->checksum     = $this->checksum;

    $profile->getSocketBNLS()->sendPacket($pkt);
  }

}
