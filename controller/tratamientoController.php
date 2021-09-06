<?php
class TratamientoController {
  private $db;

  private $id;
  private $titulo;
  private $duracion;
  private $precio;
  private $descripcion;

  public function __construct($params) {
    if ($params != null) {
      $this->id = orNull($params->id);
      $this->titulo = orNull($params->titulo);
      $this->precio = orNull($params->precio);
      $this->duracion = orNull($params->duracion);
      $this->descripcion = orNull($params->descripcion);
    }
    $database = new Database();
    $this->db = $database->getConnection();
	}

  public function get($id){
    $sql = $this->db->prepare("CALL tratamientoGet(:id);");
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
    if(empty($this->titulo)) return "ko";

    $sql = $this->db->prepare("CALL tratamientoCreate(:titulo, :duracion, :precio, :descripcion)");
    $sql->bindParam("titulo", $this->titulo);
    $sql->bindParam("duracion", $this->duracion);
    $sql->bindParam("precio", $this->precio);
    $sql->bindParam("descripcion", $this->descripcion);
  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }

  function update(){
    if(empty($this->id) || empty($this->titulo)) return "ko";

    $sql = $this->db->prepare("CALL tratamientoUpdate(:id, :titulo, :duracion, :precio, :descripcion)");
    $sql->bindParam("id", $this->id);
    $sql->bindParam("titulo", $this->titulo);
    $sql->bindParam("duracion", $this->duracion);
    $sql->bindParam("precio", $this->precio);
    $sql->bindParam("descripcion", $this->descripcion);
  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }

  function delete($id){
    if(empty($id)) return "ko";

    $sql = $this->db->prepare("CALL tratamientoDelete(:id)");
    $sql->bindParam("id", $id);  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }
};
