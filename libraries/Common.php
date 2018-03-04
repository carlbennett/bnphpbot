<?php

namespace bnphpbot\Libraries;

use \Exception;
use \RuntimeException;
use \SplObjectStorage;
use \StdClass;

use \bnphpbot\Libraries\CommonQueue;
use \bnphpbot\Libraries\Profile;

final class Common {

  const BNET_PRODUCT_STAR = 0x53544152; // Starcraft Original
  const BNET_PRODUCT_SEXP = 0x53455850; // Starcraft Broodwar
  const BNET_PRODUCT_SSHR = 0x53534852; // Starcraft Shareware
  const BNET_PRODUCT_JSTR = 0x4A535452; // Starcraft Japanese
  const BNET_PRODUCT_DRTL = 0x4452544C; // Diablo I Retail
  const BNET_PRODUCT_DSHR = 0x44534852; // Diablo I Shareware
  const BNET_PRODUCT_D2DV = 0x44324456; // Diablo II
  const BNET_PRODUCT_D2XP = 0x44325850; // Diablo II Lord of Destruction
  const BNET_PRODUCT_W2BN = 0x5732424E; // Warcraft II Battle.net Edition
  const BNET_PRODUCT_WAR3 = 0x57415233; // Warcraft III Reign of Chaos
  const BNET_PRODUCT_W3DM = 0x5733444D; // Warcraft III Demo
  const BNET_PRODUCT_W3XP = 0x57335850; // Warcraft III The Frozen Throne
  const BNET_PRODUCT_CHAT = 0x43484154; // Chat Gateway Protocol (Telnet)

  const BNET_PLATFORM_IX86 = 0x49583836; // Windows/Linux (x86)
  const BNET_PLATFORM_PMAC = 0x504D4143; // Mac OS Classic (PowerPC)
  const BNET_PLATFORM_XMAC = 0x584D4143; // Mac OS X (Intel)

  const BNLS_PRODUCT_STAR = 0x00000001; // Starcraft Original
  const BNLS_PRODUCT_SEXP = 0x00000002; // Starcraft Broodwar
  const BNLS_PRODUCT_W2BN = 0x00000003; // Warcraft II Battle.net Edition
  const BNLS_PRODUCT_D2DV = 0x00000004; // Diablo II
  const BNLS_PRODUCT_D2XP = 0x00000005; // Diablo II Lord of Destruction
  const BNLS_PRODUCT_JSTR = 0x00000006; // Starcraft Japanese
  const BNLS_PRODUCT_WAR3 = 0x00000007; // Warcraft III Reign of Chaos
  const BNLS_PRODUCT_W3XP = 0x00000008; // Warcraft III The Frozen Throne
  const BNLS_PRODUCT_DRTL = 0x00000009; // Diablo I Retail
  const BNLS_PRODUCT_DSHR = 0x0000000A; // Diablo I Shareware
  const BNLS_PRODUCT_SSHR = 0x0000000B; // Starcraft Shareware
  const BNLS_PRODUCT_W3DM = 0x0000000C; // Warcraft III Demo

  public static $args;
  public static $config;
  public static $exit_trap;
  public static $message_queue;
  public static $profiles;

  public static function convertBNETProductToBNLS($product_id) {
    switch ($product_id) {
      case self::BNET_PRODUCT_STAR: return self::BNLS_PRODUCT_STAR;
      case self::BNET_PRODUCT_SEXP: return self::BNLS_PRODUCT_SEXP;
      case self::BNET_PRODUCT_SSHR: return self::BNLS_PRODUCT_SSHR;
      case self::BNET_PRODUCT_JSTR: return self::BNLS_PRODUCT_JSTR;
      case self::BNET_PRODUCT_DRTL: return self::BNLS_PRODUCT_DRTL;
      case self::BNET_PRODUCT_DSHR: return self::BNLS_PRODUCT_DSHR;
      case self::BNET_PRODUCT_D2DV: return self::BNLS_PRODUCT_D2DV;
      case self::BNET_PRODUCT_D2XP: return self::BNLS_PRODUCT_D2XP;
      case self::BNET_PRODUCT_W2BN: return self::BNLS_PRODUCT_W2BN;
      case self::BNET_PRODUCT_WAR3: return self::BNLS_PRODUCT_WAR3;
      case self::BNET_PRODUCT_W3DM: return self::BNLS_PRODUCT_W3DM;
      case self::BNET_PRODUCT_W3XP: return self::BNLS_PRODUCT_W3XP;
      default: throw new Exception("Cannot convert BNET product to BNLS");
    }
  }

  public static function convertBNLSProductToBNET($product_id) {
    switch ($product_id) {
      case self::BNLS_PRODUCT_STAR: return self::BNET_PRODUCT_STAR;
      case self::BNLS_PRODUCT_SEXP: return self::BNET_PRODUCT_SEXP;
      case self::BNLS_PRODUCT_SSHR: return self::BNET_PRODUCT_SSHR;
      case self::BNLS_PRODUCT_JSTR: return self::BNET_PRODUCT_JSTR;
      case self::BNLS_PRODUCT_DRTL: return self::BNET_PRODUCT_DRTL;
      case self::BNLS_PRODUCT_DSHR: return self::BNET_PRODUCT_DSHR;
      case self::BNLS_PRODUCT_D2DV: return self::BNET_PRODUCT_D2DV;
      case self::BNLS_PRODUCT_D2XP: return self::BNET_PRODUCT_D2XP;
      case self::BNLS_PRODUCT_W2BN: return self::BNET_PRODUCT_W2BN;
      case self::BNLS_PRODUCT_WAR3: return self::BNET_PRODUCT_WAR3;
      case self::BNLS_PRODUCT_W3DM: return self::BNET_PRODUCT_W3DM;
      case self::BNLS_PRODUCT_W3XP: return self::BNET_PRODUCT_W3XP;
      default: throw new Exception("Cannot convert BNLS product to BNET");
    }
  }

  public static function convertRawToBNETPlatform($str) {
    switch (strtoupper($str)) {
      case "IX86": case "68XI": return self::BNET_PLATFORM_IX86;
      case "PMAC": case "CAMP": return self::BNET_PLATFORM_PMAC;
      case "XMAC": case "CAMX": return self::BNET_PLATFORM_XMAC;
      default: throw new Exception("Cannot convert raw to BNET platform");
    }
  }

  public static function convertRawToBNETProduct($str) {
    switch (strtoupper($str)) {
      case "STAR": case "RATS": return self::BNET_PRODUCT_STAR;
      case "SEXP": case "PXES": return self::BNET_PRODUCT_SEXP;
      case "SSHR": case "RHSS": return self::BNET_PRODUCT_SSHR;
      case "JSTR": case "RTSJ": return self::BNET_PRODUCT_JSTR;
      case "DRTL": case "LTRD": return self::BNET_PRODUCT_DRTL;
      case "DSHR": case "RHSD": return self::BNET_PRODUCT_DSHR;
      case "D2DV": case "VD2D": return self::BNET_PRODUCT_D2DV;
      case "D2XP": case "PX2D": return self::BNET_PRODUCT_D2XP;
      case "W2BN": case "NB2W": return self::BNET_PRODUCT_W2BN;
      case "WAR3": case "3RAW": return self::BNET_PRODUCT_WAR3;
      case "W3DM": case "MD3W": return self::BNET_PRODUCT_W3DM;
      case "W3XP": case "PX3W": return self::BNET_PRODUCT_W3XP;
      case "CHAT": case "TAHC": return self::BNET_PRODUCT_CHAT;
      default: throw new Exception("Cannot convert raw to BNET product");
    }
  }

  public static function getBNLSChecksum($password, $server_code) {
    return hash("crc32b", $password
      . strtoupper(str_pad(dechex($server_code), 8, "0", STR_PAD_LEFT))
    );
  }

  public static function initialize() {
    self::$message_queue = new CommonQueue();
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

      try { $item = self::$message_queue->pop(); }
      catch ( RuntimeException $e ) { $item = null; }
      if ( $item ) { self::$message_queue->process( $item ); }
    }
  }

  public static function &versionProperties() {
    $version = new StdClass();
    $version->bnphpbot = "0.1.0";
    $version->php      = phpversion();
    return $version;
  }

}
