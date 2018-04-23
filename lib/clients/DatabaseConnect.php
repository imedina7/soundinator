<?php
require_once __DIR__ . '/../../vendor/autoload.php';

class DatabaseConnect {
  private $user;
  private $pass;
  private $port;
  private $dbname;
  private $host;
  protected static $db = null;
  protected static $conn = null;

  function __construct () {

  }

  public static function getInstance (){
    if (!isset(static::$db))
      static::$db = new DatabaseConnect();
    return static::$db;
  }

  private function getConnection (){
    $dbUrl = getenv("DATABASE_URL");

    if (static::$conn != null) {
      $pg = static::$conn;
    } else {
      $this->populateDbParams($dbUrl);

      $pg = new PostgresDb( $this->dbname,
                            $this->host,
                            $this->user,
                            $this->pass);
      $pg->pdo();
      static::$conn = $pg;
    }
    return $pg;
  }

  public function getSounds(){
    $pg = $this->getConnection();
    $rows = $pg->get('sounds');
    return $rows;
  }
  
  public function userExists($user) {
    $pg = $this->getConnection();
    return $pg->where('user_name',$user)->has('users');
  }

  public function userAuth($user, $passwordHash) {
    $pg = $this->getConnection();
    $row = $pg->where('user_name',$user)->getOne('users');
    return $passwordHash == $row['user_password'];
  }

  private function populateDbParams($url) {
    if ($_cod = preg_match(
            "/postgres:\/\/(.*):(.*)@(.*):(.*)\/([0-9a-zA-Z]*)/",
            $url,
            $regs) > 0)
    {
      $this->user = $regs[1];
      $this->pass = $regs[2];
      $this->host = $regs[3];
      $this->port = $regs[4];
      $this->dbname = $regs[5];
    } else
      return $_cod;
    return 0;
  }
}

?>
