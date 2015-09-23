<?php

namespace bnphpbot\Libraries;

use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Sockets\BNETSocket;
use \bnphpbot\Libraries\Sockets\BNLSSocket;

class Profile {

  protected $backup_channel;
  protected $battlenet_cdkey;
  protected $battlenet_cdkey_expansion;
  protected $battlenet_cdkey_owner;
  protected $battlenet_email_address;
  protected $battlenet_hostname;
  protected $battlenet_password;
  protected $battlenet_platform;
  protected $battlenet_port;
  protected $battlenet_product;
  protected $battlenet_udp_support;
  protected $battlenet_username;
  protected $battlenet_version_byte;
  protected $bnls_hostname;
  protected $bnls_port;
  protected $bnls_usage_authentication;
  protected $bnls_usage_cdkey;
  protected $bnls_usage_version_byte;
  protected $bnls_usage_version_check;
  protected $filter_broadcast_message;
  protected $filter_error_message;
  protected $filter_info_message;
  protected $filter_join_message;
  protected $filter_part_message;
  protected $home_channel;
  protected $id;
  protected $rejoin_on_kick;
  protected $socket_battlenet;
  protected $socket_bnls;

  public function __construct($id) {
    $this->backup_channel            = null;
    $this->battlenet_cdkey           = null;
    $this->battlenet_cdkey_expansion = null;
    $this->battlenet_cdkey_owner     = null;
    $this->battlenet_email_address   = null;
    $this->battlenet_hostname        = null;
    $this->battlenet_password        = null;
    $this->battlenet_platform        = null;
    $this->battlenet_port            = null;
    $this->battlenet_product         = null;
    $this->battlenet_udp_support     = null;
    $this->battlenet_username        = null;
    $this->battlenet_version_byte    = null;
    $this->bnls_hostname             = null;
    $this->bnls_port                 = null;
    $this->bnls_usage_authentication = null;
    $this->bnls_usage_cdkey          = null;
    $this->bnls_usage_version_byte   = null;
    $this->bnls_usage_version_check  = null;
    $this->filter_broadcast_message  = null;
    $this->filter_error_message      = null;
    $this->filter_info_message       = null;
    $this->filter_join_message       = null;
    $this->filter_part_message       = null;
    $this->home_channel              = null;
    $this->id                        = $id;
    $this->rejoin_on_kick            = null;
    $this->socket_battlenet          = new BNETSocket();
    $this->socket_bnls               = null;

    if ($this->bnls_usage_authentication ||
        $this->bnls_usage_cdkey ||
        $this->bnls_usage_version_byte ||
        $this->bnls_usage_version_check)
      $this->socket_bnls = new BNLSSocket();
  }

  public static function loadAllProfiles() {
    foreach (Common::$profiles as $o) {
      Common::$profiles->detach($o);
    }
    $id = 0;
    foreach (Common::$config->bnphpbot->profiles as $a) {
      $o = new self(++$id);
      $o->backup_channel            = $a->backup_channel;
      $o->battlenet_cdkey           = $a->battlenet->cdkey;
      $o->battlenet_cdkey_expansion = $a->battlenet->cdkey_expansion;
      $o->battlenet_cdkey_owner     = $a->battlenet->cdkey_owner;
      $o->battlenet_email_address   = $a->battlenet->email_address;
      $o->battlenet_hostname        = $a->battlenet->hostname;
      $o->battlenet_password        = $a->battlenet->password;
      $o->battlenet_platform        = $a->battlenet->platform;
      $o->battlenet_port            = $a->battlenet->port;
      $o->battlenet_product         = $a->battlenet->product;
      $o->battlenet_udp_support     = $a->battlenet->udp_support;
      $o->battlenet_username        = $a->battlenet->username;
      $o->battlenet_version_byte    = $a->battlenet->version_byte;
      $o->bnls_hostname             = $a->bnls->hostname;
      $o->bnls_port                 = $a->bnls->port;
      $o->bnls_usage_authentication = $a->bnls->usage->authentication;
      $o->bnls_usage_cdkey          = $a->bnls->usage->cdkey;
      $o->bnls_usage_version_byte   = $a->bnls->usage->version_byte;
      $o->bnls_usage_version_check  = $a->bnls->usage->version_check;
      $o->filter_broadcast_message  = $a->filters->filter_broadcast_message;
      $o->filter_error_message      = $a->filters->filter_error_message;
      $o->filter_info_message       = $a->filters->filter_info_message;
      $o->filter_join_message       = $a->filters->filter_join_message;
      $o->filter_part_message       = $a->filters->filter_part_message;
      $o->home_channel              = $a->home_channel;
      $o->rejoin_on_kick            = $a->rejoin_on_kick;
      Common::$profiles->attach($o);
    }
    $count = Common::$profiles->count();
    Logger::writeLine("Loaded $count profile" . ($count != 1 ? "s" : ""));
    return $count;
  }

}
