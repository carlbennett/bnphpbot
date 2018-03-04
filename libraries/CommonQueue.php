<?php

namespace bnphpbot\Libraries;

use \LogicException;
use \RuntimeException;

use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\MessageItem;
use \bnphpbot\Libraries\MessageQueue;

use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZE;
use \bnphpbot\Libraries\Packets\BNLS_AUTHORIZEPROOF;
use \bnphpbot\Libraries\Packets\BNLS_NULL;
use \bnphpbot\Libraries\Packets\BNLS_REQUESTVERSIONBYTE;
use \bnphpbot\Libraries\Packets\BNLS_VERSIONCHECKEX2;

use \bnphpbot\Libraries\Packets\SID_AUTH_INFO;
use \bnphpbot\Libraries\Packets\SID_NULL;
use \bnphpbot\Libraries\Packets\SID_PING;

class CommonQueue extends MessageQueue {

  public function process( MessageItem &$message )
  {
    // TODO
    switch ( $message->getType() )
    {
      case MessageItem::TYPE_PACKET_BNET:
      {
        $pkt = $message->getValue();

        if ( $pkt instanceof SID_AUTH_INFO )
        {
          $socket = $pkt->socket->getProfile()->getSocketBNLS();
          $packet = new BNLS_VERSIONCHECKEX2( $socket );

          $packet->product_id   = Common::convertBNETProductToBNLS(
            $pkt->socket->getProfile()->getBattlenetProduct()
          );
          $packet->flags        = 0;
          $packet->cookie       = mt_rand();
          $packet->mpq_filetime = $pkt->mpq_filetime;
          $packet->mpq_filename = $pkt->mpq_filename;
          $packet->checksum     = $pkt->checksum;

          $socket->sendPacket( $packet );
        }
        else if ( $pkt instanceof SID_NULL )
        {
          // do nothing
        }
        else if ( $pkt instanceof SID_PING )
        {
          $pkt->socket->sendPacket( $pkt );
        }
        else
        {
          throw new RuntimeException('Unknown BNET message received');
        }

        break;
      }
      case MessageItem::TYPE_PACKET_BNLS:
      {
        $pkt = $message->getValue();

        if ( $pkt instanceof BNLS_AUTHORIZE )
        {
          $packet = new BNLS_AUTHORIZEPROOF( $pkt->socket );

          $packet->checksum = Common::getBNLSChecksum(
            'bnphpbot' . Common::versionProperties()->bnphpbot,
            $pkt->server_code
          );

          $packet->socket->sendPacket( $packet );
        }
        else if ( $pkt instanceof BNLS_AUTHORIZEPROOF )
        {

          if ( $pkt->status != 0x00 ) {
            Logger::writeLine(
              'BNLS: You are not authorized to use this server'
            );
            $pkt->socket->close();
            break;
          }

          $packet = new BNLS_REQUESTVERSIONBYTE( $pkt->socket );

          $packet->product_id = Common::convertBNETProductToBNLS(
            $pkt->socket->getProfile()->getBattlenetProduct()
          );

          $packet->socket->sendPacket( $packet );
        }
        else if ( $pkt instanceof BNLS_NULL )
        {
          // do nothing
        }
        else if ( $pkt instanceof BNLS_REQUESTVERSIONBYTE )
        {
          $pkt->socket->getProfile()->getSocketBattlenet()->connect();
        }
        else if ( $pkt instanceof BNLS_VERSIONCHECKEX2 )
        {
          if ( !$pkt->success ) {
            Logger::writeLine('Failed version check with BNLS.');
          } else {
            $socket = $pkt->socket->getProfile()->getSocketBNLS();
            $packet = new SID_AUTH_CHECK( $socket );

//            $socket->sendPacket( $packet );
          }
        }
        else
        {
          var_dump( $pkt );
          throw new RuntimeException('Unknown BNLS message received');
        }

        break;
      }
      case MessageItem::TYPE_CHAT_MESSAGE:
      {

        throw new LogicException('Chat messages not yet implemented');

        break;
      }
      default: throw new LogicException('Unknown message type');
    }
  }

}
