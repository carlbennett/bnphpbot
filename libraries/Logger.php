<?php

namespace bnphpbot\Libraries;

use \bnphpbot\Libraries\Common;
use \RuntimeException;

class Logger {

  private function __construct() {}

  public static function initialize() {

  }

  public static function writeLine($text, $error = false) {
    $h = ($error ? STDERR : STDOUT);
    if (!is_resource($h))
      throw new RuntimeException("Cannot open file handle");
    return fwrite($h, $text . "\n");
  }

  public static function writeMotd() {
    $version = Common::versionProperties();
    self::writeLine("bnphpbot " . $version->bnphpbot);
    self::writeLine("==============");
    self::writeLine("");
  }

}
