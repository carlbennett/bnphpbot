<?php

namespace bnphpbot\Libraries\Packets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNETPacket;

class SID_AUTH_CHECK extends BNETPacket {

  const ID = 0x51;

  /* Client -> Server */
  public $client_token;
  public $exe_version;
  public $exe_checksum;
  public $key_count;
  public $spawn_key;
  public $keys;
  public $exe_info;
  public $key_owner;

  /* Server -> Client */
  public $result;
  public $result_info;

  public function &send() {
    $buffer = new BNETBuffer();
    $buffer->writeByte(0xFF);
    $buffer->writeByte(self::ID);
    $buffer->writeUInt16(4);

    $buffer->writeUInt32( $this->client_token );
    $buffer->writeUInt32( $this->exe_version );
    $buffer->writeUInt32( $this->exe_checksum );
    $buffer->writeUInt32( $this->key_count );
    $buffer->writeUInt32( $this->spawn_key );

    foreach ( $this->keys as $key ) {
      $buffer->writeUInt32( key_length );
      $buffer->writeUInt32( key_product );
      $buffer->writeUInt32( key_public );
      $buffer->writeUInt32( 0 );
      $buffer->writeRaw( key_data[20] );
    }

    $buffer->writeCString( $this->exe_info );
    $buffer->writeCString( $this->key_owner );

    $buffer->setPosition(2);
    $buffer->writeUInt16($buffer->getLength());
    $buffer->setPosition(0);

    Logger::writeLine("SEND: SID_AUTH_INFO", true);

    return $buffer;
  }

  public function receive( &$buffer ) {
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

    $profile                         = $this->socket->getProfile();
    $state                           = $profile->getState();
    $state["logon_type"]             = $this->logon_type;
    $state["udp_token"]              = $this->udp_token;
    $state["version_check_mpq_name"] = $this->mpq_filename;
    $state["version_check_mpq_time"] = $this->mpq_filetime;
    $state["version_check_checksum"] = $this->checksum;
    $state["server_signature"]       = $this->signature;
  }

}
