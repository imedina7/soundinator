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

  public function getSounds($user_id){
    $pg = $this->getConnection();
    $rows = $pg->where("user_id",[$user_id])->get('sounds',null,"sound_id, sound_name, sound_type, encode(sound_data,'base64') AS data_base64, sound_tags, user_id");
    return $rows;
  }
  public function saveSounds($user_id,$sounds){
    $pg = $this->getConnection();
    /*
    *
    *$_FILES['userfile']['name']
      The original name of the file on the client machine.

      $_FILES['userfile']['type']
      The mime type of the file, if the browser provided this information. 
      An example would be "image/gif". This mime type is however not 
      checked on the PHP side and therefore don't take its value for granted.

      $_FILES['userfile']['size']
      The size, in bytes, of the uploaded file.

      $_FILES['userfile']['tmp_name']
      The temporary filename of the file in which the uploaded file was stored on the server.

      $_FILES['userfile']['error']
      The error code associated with this file upload.
    */
    foreach ($sounds as $key => $s) {
      if ($s['error'] == 0) {
        $name = $this->parseFileName($s['name']);

        $filename = $s['tmp_name'];
        $contentType = mime_content_type ( $s['tmp_name'] );;

        $handle = fopen($filename, "rb");
        $contents = fread($handle, filesize($filename));
        
        fclose($handle);
  
        $pg->insert('sounds', [ 'sound_name' => $name, 
                                'sound_type' => $contentType, 
                                'sound_data' => "decode('".bin2hex($contents)."','hex')",
                                'user_id' => $user_id ]);
        $output = '{ "status" : "success" }';
      } else {
        error_log("Error uploading file '".$key."': ". $s['error']);
      }
    }
  }
  public function userExists($user) {
    $pg = $this->getConnection();
    $row = $pg->where('user_name',$user)->getOne('users');
    if (isset($row['user_id']))
      return $row['user_id'];
    return false;
  }

  public function userAuth($user, $password) {
    $pg = $this->getConnection();
    $row = $pg->where('user_name',[$user])->getOne('users');
    
    return password_verify($password, $row['user_password']);
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
  private function parseFileName($filename) {
    $filename = preg_replace('/[_]/'," ",$filename);
    $filename = preg_replace('/\.(.*)$/',"",$filename);
    return ucwords($filename);
  }
}

?>
