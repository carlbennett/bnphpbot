#!/usr/bin/php
<?php

namespace bnphpbot;

use \Exception;
use \bnphpbot\Libraries\Common;
use \bnphpbot\Libraries\Logger;
use \bnphpbot\Libraries\Pair;

function autoload($c) {
  if (substr($c, 0, 9) == "bnphpbot\\") $c = substr($c, 9);
  $cursor = strpos($c, "\\");
  if ($cursor !== false)
    $c = strtolower(substr($c, 0, $cursor)) . substr($c, $cursor);
  $c = str_replace("\\", "/", $c);
  $c = "./" . $c . ".php";
  if (!file_exists($c))
    throw new Exception("Class not found: " . $c);
  require_once($c);
}

function sig_handler($signo) {
  switch ($signo) {
    case SIGTERM:
    case SIGINT:
      Logger::writeLine("Shutting down...");
      Common::$exit_trap = true;
      break;
    case SIGHUP:
    case SIGUSR1:
      Logger::writeLine("Restarting...");
      break;
    default:
      Logger::writeLine("Unhandled signal $signo", true);
  }
}

function main(&$argc, &$argv) {

  if (php_sapi_name() != "cli") {
    throw new Exception("bnphpbot is a php-cli application only");
  }

  spl_autoload_register("\bnphpbot\autoload");

  Logger::initialize();
  Logger::writeMotd();

  Logger::writeLine("Reading configuration...");
  Common::$args   = &$argv;
  Common::$config = json_decode(file_get_contents("./config.json"));

  Logger::writeLine("Initializing application...");
  Common::initialize();

  Common::$exit_trap = false;
  pcntl_signal(SIGHUP, "\bnphpbot\sig_handler");
  pcntl_signal(SIGINT, "\bnphpbot\sig_handler");
  pcntl_signal(SIGTERM, "\bnphpbot\sig_handler");
  pcntl_signal(SIGUSR1, "\bnphpbot\sig_handler");

  Logger::writeLine("Running...");
  while (!Common::$exit_trap) {
    sleep(1);
    pcntl_signal_dispatch();
  };

  return 0;
}

exit(main($argc, $argv));
