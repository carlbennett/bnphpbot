<?php

namespace bnphpbot\Libraries\Packets;

abstract class Packet {

  public abstract function &send();
  public abstract function receive(&$socket, &$buffer);

}
