<?php

namespace bnphpbot\Libraries\Sockets;

class Socket {

  private $connected;
  private $socket;

  public function __construct($domain, $type, $protocol) {
    $this->socket = socket_create($domain, $type, $protocol);
  }

  public function accept() {
    return socket_accept($this->socket);
  }

  public function bind($address, $port = 0) {
    return socket_bind($this->socket, $address, $port);
  }

  public function clear_error() {
    return socket_clear_error($this->socket);
  }

  public function close() {
    $ret = socket_close($this->socket);
    $this->connected = false;
    return $ret;
  }

  public function cmsg_space($level, $type) {
    return socket_cmsg_space($level, $type);
  }

  public function connect($address, $port = 0) {
    $this->connected = socket_connect($this->socket, $address, $port);
    return $this->connected;
  }

  public function connected() {
    return $this->connected;
  }

  public function get_option($level, $optname) {
    return socket_get_option($this->socket, $level, $optname);
  }

  public function getpeername(&$address, &$port) {
    return socket_getpeername($this->socket, $address, $port);
  }

  public function getsockname(&$addr, &$port) {
    return socket_getsockname($this->socket, $addr, $port);
  }

  public function last_error() {
    return socket_last_error($this->socket);
  }

  public function listen($backlog = 0) {
    return socket_listen($this->socket, $backlog);
  }

  public function read($length, $type = PHP_BINARY_READ) {
    return socket_read($this->socket, $length, $type);
  }

  public function recv(&$buf, $len, $flags) {
    return socket_recv($this->socket, $buf, $len, $flags);
  }

  public function recvfrom(&$buf, $len, $flags, &$name, &$port) {
    return socket_recvfrom($this->socket, $buf, $len, $flags, $name, $port);
  }

  public function recvmsg($message, $flags) {
    return socket_recvmsg($this->socket, $message, $flags);
  }

  public function send($buf, $len, $flags) {
    return socket_send($this->socket, $buf, $len, $flags);
  }

  public function sendmsg(array $message, $flags) {
    return socket_sendmsg($this->socket, $message, $flags);
  }

  public function sendto($buf, $len, $flags, $addr, $port = 0) {
    return socket_sendto($this->socket, $buf, $len, $flags, $addr, $port);
  }

  public function set_block() {
    return socket_set_block($this->socket);
  }

  public function set_nonblock() {
    return socket_set_nonblock($this->socket);
  }

  public function set_option($level, $optname, $optval) {
    return socket_set_option($this->socket, $level, $optname, $optval);
  }

  public function shutdown($how = 2) {
    return socket_shutdown($this->socket, $how);
  }

  public static function strerror($errno) {
    return socket_strerror($errno);
  }

  public function write($buffer, $length = 0) {
    return socket_write($this->socket, $buffer, $length);
  }

}
