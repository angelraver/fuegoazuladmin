<?php
class ClienteController {
  private $db;

  public $id;
  private $firstName;
  private $lastName;
  private $gender;
  private $email;
  private $phone;
  public $descripcion;

	public function __construct($params) {
    if ($params != null) {
      $this->id = isset($params->id) ? orNull($params->id) : NULL;
      $this->firstName = isset($params->firstName) ? orNull($params->firstName) : NULL;
      $this->lastName = isset($params->lastName) ? orNull($params->lastName) : NULL;
      $this->gender = isset($params->gender) ? orNull($params->gender) : NULL;
      $this->email = isset($params->email) ? orNull($params->email) : NULL;
      $this->phone = isset($params->phone) ? orNull($params->phone) : NULL;
      $this->descripcion = isset($params->descripcion) ? orNull($params->descripcion) : NULL;
    }
    $database = new Database();
    $this->db = $database->getConnection();
	}

  public function get($id){
    $sql = $this->db->prepare("CALL clienteGet(:id);");
    $_id = orNull($id);
    $sql->bindParam("id", $_id);
    $sql->execute();
    $datos = [];
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
      $datos []= $row;
    }

    return $datos;
  }

  function create(){
    if(empty($this->firstName)) return "ko de una";
    // echo "CALL clienteCreate(".$this->firstName.", ".$this->lastName.", ".$this->gender.", ".$this->email.", ".$this->phone.", ".$this->descripcion.")";
    // die();
    $sql = $this->db->prepare("CALL clienteCreate(:firstName, :lastName, :gender, :email, :phone, :descripcion)");
    $sql->bindParam("firstName", $this->firstName);
    $sql->bindParam("lastName", $this->lastName);
    $sql->bindParam("gender", $this->gender);
    $sql->bindParam("email", $this->email);
    $sql->bindParam("phone", $this->phone);
    $sql->bindParam("descripcion", $this->descripcion);
  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }

  function update(){
    if(empty($this->firstName)) return "ko";

    $sql = $this->db->prepare("CALL clienteUpdate(:id, :firstName, :lastName, :gender, :email, :phone, :descripcion)");
    $sql->bindParam("id", $this->id);
    $sql->bindParam("firstName", $this->firstName);
    $sql->bindParam("lastName", $this->lastName);
    $sql->bindParam("gender", $this->gender);
    $sql->bindParam("email", $this->email);
    $sql->bindParam("phone", $this->phone);
    $sql->bindParam("descripcion", $this->descripcion);
  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }

  function delete($id){
    if(empty($id)) return "ko";

    $sql = $this->db->prepare("CALL clienteDelete(:id)");
    $sql->bindParam("id", $id);  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }
};
