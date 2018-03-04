<?php

namespace bnphpbot\Libraries;

use \bnphpbot\Libraries\Pair;

class MessageItem extends Pair {

  const TYPE_PACKET_BNET  = 0;
  const TYPE_PACKET_BNLS  = 1;
  const TYPE_CHAT_MESSAGE = 2;

  public function getType() {
    return $this->getKey();
  }

}
