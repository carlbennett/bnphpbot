<?php

namespace bnphpbot\Libraries\Buffers;

use \bnphpbot\Libraries\Buffers\Buffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZE;
use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZEPROOF;
use \bnphpbot\Libraries\Packets\BNLS_NULL;
use \bnphpbot\Libraries\Packets\BNLS_REQUESTVERSIONBYTE;
use \bnphpbot\Libraries\Packets\BNLS_VERSIONCHECKEX2;
use \bnphpbot\Libraries\Sockets\BNLSSocket;

class BNLSBuffer extends Buffer {

  public function parsePacket(BNLSSocket &$socket) {
    if ($this->getLength() < 3) return false;
    $length = $this->readUInt16();
    $id     = $this->readUInt8();
    if ($this->getLength() + 3 < $length) return false;
    switch ($id) {
      case BNLS_NULL::ID: {
        $pkt = new BNLS_NULL(); $pkt->receive($socket, $this);
        break;
      }
      case BNLS_AUTHORIZE::ID: {
        $pkt = new BNLS_AUTHORIZE(); $pkt->receive($socket, $this);
        break;
      }
      case BNLS_AUTHORIZEPROOF::ID: {
        $pkt = new BNLS_AUTHORIZEPROOF(); $pkt->receive($socket, $this);
        break;
      }
      case BNLS_REQUESTVERSIONBYTE::ID: {
        $pkt = new BNLS_REQUESTVERSIONBYTE(); $pkt->receive($socket, $this);
        break;
      }
      case BNLS_VERSIONCHECKEX2::ID: {
        $pkt = new BNLS_VERSIONCHECKEX2(); $pkt->receive($socket, $this);
        break;
      }
      default: {
        Logger::writeLine("BNLSBuffer: Unknown packet id received", true);
        return false;
      }
    }
    return true;
  }

}
