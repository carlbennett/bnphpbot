<?php

namespace bnphpbot\Libraries\Buffers;

use \bnphpbot\Libraries\Buffers\Buffer;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\MessageItem;

use \bnphpbot\Libraries\Packets\SID_AUTH_INFO;
use \bnphpbot\Libraries\Packets\SID_NULL;
use \bnphpbot\Libraries\Packets\SID_PING;

use \bnphpbot\Libraries\Sockets\BNETSocket;

class BNETBuffer extends Buffer {

  public function parsePacket(BNETSocket &$socket) {
    if ($this->getLength() < 4) return false;
    $padding = $this->readUInt8();
    $id      = $this->readUInt8();
    $length  = $this->readUInt16();
    if ($padding !== 255) {
      Logger::writeLine("BNETBuffer: Invalid packet padding", true);
      Logger::writeLine(
        "BNETBuffer: Dumping contents of buffer and starting over", true
      );
      $this->setPosition($this->getLength()); $this->trim();
      return false;
    }
    if ($this->getLength() + 4 < $length) return false;
    switch ($id) {
      case SID_NULL::ID: {
        $pkt = new SID_NULL(); $pkt->receive($socket, $this);
        break;
      }
      case SID_PING::ID: {
        $pkt = new SID_PING(); $pkt->receive($socket, $this);
        break;
      }
      case SID_AUTH_INFO::ID: {
        $pkt = new SID_AUTH_INFO(); $pkt->receive($socket, $this);
        break;
      }
      default: {
        Logger::writeLine("BNETBuffer: Unknown packet id received", true);
        return false;
      }
    }
    $message = new MessageItem( MessageItem::TYPE_PACKET_BNET, $pkt );
    $socket->getProfile()->queuePush( $message );
    return true;
  }

}
