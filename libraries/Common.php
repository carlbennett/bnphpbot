<?php

namespace bnphpbot\Libraries;

use \SplObjectStorage;
use \StdClass;
use \bnphpbot\Libraries\Profile;

final class Common {

  public static $args;
  public static $config;
  public static $exit_trap;
  public static $profiles;

  public static function initialize() {
    self::$profiles = new SplObjectStorage();
    Profile::loadAllProfiles();
  }

  public static function processWork() {
    foreach (Common::$profiles as $profile) {
      $bnet = $profile->getSocketBattlenet();
      $bnls = $profile->getSocketBNLS();
      if (!is_null($bnls)) {
        $bnls->poll();
      }
      if (!is_null($bnet)) {
        $bnet->poll();
      }
    }
  }

  public static function &versionProperties() {
    $version = new StdClass();
    $version->bnphpbot = "0.1.0";
    $version->php      = phpversion();
    return $version;
  }

}
