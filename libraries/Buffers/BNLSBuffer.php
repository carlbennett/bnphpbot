<?php

namespace bnphpbot\Libraries\Buffers;

use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Buffers\Buffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\MessageItem;

use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZE;
use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZEPROOF;
use \bnphpbot\Libraries\Packets\BNLS_NULL;
use \bnphpbot\Libraries\Packets\BNLS_REQUESTVERSIONBYTE;
use \bnphpbot\Libraries\Packets\BNLS_VERSIONCHECKEX2;

use \bnphpbot\Libraries\Sockets\BNLSSocket;

class BNLSBuffer extends Buffer {

  public function parsePacket( BNLSSocket &$socket ) {
    if ($this->getLength() < 3) return false;
    $length = $this->readUInt16();
    $id     = $this->readUInt8();
    if ($this->getLength() + 3 < $length) return false;
    switch ($id) {
      case BNLS_NULL::ID: {
        $pkt = new BNLS_NULL( $socket ); $pkt->receive( $this );
        break;
      }
      case BNLS_AUTHORIZE::ID: {
        $pkt = new BNLS_AUTHORIZE( $socket ); $pkt->receive( $this );
        break;
      }
      case BNLS_AUTHORIZEPROOF::ID: {
        $pkt = new BNLS_AUTHORIZEPROOF( $socket ); $pkt->receive( $this );
        break;
      }
      case BNLS_REQUESTVERSIONBYTE::ID: {
        $pkt = new BNLS_REQUESTVERSIONBYTE( $socket ); $pkt->receive( $this );
        break;
      }
      case BNLS_VERSIONCHECKEX2::ID: {
        $pkt = new BNLS_VERSIONCHECKEX2( $socket ); $pkt->receive( $this );
        break;
      }
      default: {
        Logger::writeLine("BNLSBuffer: Unknown packet id received", true);
        return false;
      }
    }
    $message = new MessageItem( MessageItem::TYPE_PACKET_BNLS, $pkt );
    Common::$message_queue->push( $message );
    return true;
  }

}
