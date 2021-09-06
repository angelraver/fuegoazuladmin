<?php 
class Database {
  public $conn;

  public function getConnection(){
    $this->conn = null;
    if($_SERVER['SERVER_NAME'] === '127.0.0.1') {
      $user = "root";
      $pass = "";
      $host = "127.0.0.1";
      $db = "vals";
    } else {
      $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
      $host = $cleardb_url["host"];
      $user = $cleardb_url["user"];
      $pass = $cleardb_url["pass"];
      $db = substr($cleardb_url["path"],1);  
    }
    $dns = "mysql:host=" . $host . ";dbname=" . $db;
    $this->conn = new PDO($dns, $user, $pass);
    try{
      $this->conn->exec("set names utf8");
    }catch(PDOException $exception){
      echo "Database could not be connected: " . $exception->getMessage();
    }
    return $this->conn;
  }
}  
?>
