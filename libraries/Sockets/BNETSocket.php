<?php

namespace bnphpbot\Libraries\Sockets;

use \bnphpbot\Libraries\Buffers\BNETBuffer;
use \bnphpbot\Libraries\Sockets\TCPSocket;

class BNETSocket extends TCPSocket {

  protected $buffer;

  public function __construct() {
    parent::__construct();
    $this->buffer = new BNETBuffer();

    // TESTING THE BUFFER, DEBUG ONLY:
    $this->buffer->writeUInt8(8);
    $this->buffer->writeUInt16(27045);
    $this->buffer->writeUInt32(306735525);
    $this->buffer->writeCString("Hello World");
    $this->buffer->writeUInt64(6917535624717139974);
    $this->buffer->setPosition(0);
    var_dump($this->buffer->readUInt8());
    var_dump($this->buffer->readUInt16());
    var_dump($this->buffer->readUInt32());
    var_dump($this->buffer->readCString());
    var_dump($this->buffer->readUInt64());
    // REMOVE ABOVE THIS LINE
  }

}
