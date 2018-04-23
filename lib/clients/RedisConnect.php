<?php
require_once __DIR__ . '/../../vendor/autoload.php';

class RedisConnect {
  private $user;
  private $pass;
  private $port;
  private $host;
  protected static $db = null;
  protected static $client = null;

  function __construct () {

  }

  public static function getInstance (){
    if (!isset(static::$db))
      static::$db = new RedisConnect();
    return static::$db;
  }
  private function getClient(){
    if (static::$client != null) {
      $rdis = static::$client;
    } else {
      $rdis = new Predis\Client(getenv('REDISCLOUD_URL'));
      static::$client = $rdis;
    }
  }
  public function addSession($session_id) {
    $rdis = $this->getClient();
    return $rdis->lpush("sessions",$session_id);
  }
}

?>