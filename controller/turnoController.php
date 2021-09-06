<?php
class TurnoController {
  private $db;
  
  private $id;
  private $idCliente;
  private $idTratamiento;
  private $fecha;
  private $hora;
  private $status;
  private $next;

	public function __construct($params) {
    if ($params != null) {
      $this->id = isset($params->id) ? orNull($params->id) : NULL;
      $this->idCliente = isset($params->idCliente) ? orNull($params->idCliente) : NULL;
      $this->idTratamiento = isset($params->idTratamiento) ? orNull($params->idTratamiento) : NULL;
      $this->fecha = isset($params->fecha) ? orNull($params->fecha) : NULL;
      $this->hora = isset($params->hora) ? orNull($params->hora) : NULL;
      $this->status = isset($params->status) ? orNull($params->status) : NULL;
      $this->next = isset($params->next) ? orNull($params->next) : NULL;
    }
    $database = new Database();
    $this->db = $database->getConnection();
	}

  public function get(){
    $sql = $this->db->prepare("CALL turnoGet(:id, :idCliente, :idTratamiento, :fecha, :status, :next);");
    $id = orNull($this->id);
    $idCliente = orNull($this->idCliente);
    $idTratamiento = orNull($this->idTratamiento);
    $fecha = orNull($this->fecha);
    $status = orNull($this->status);
    $next = orNull($this->next);

    $sql->bindParam("id", $id);
    $sql->bindParam("idCliente", $idCliente);
    $sql->bindParam("idTratamiento", $idTratamiento);
    $sql->bindParam("fecha", $fecha);
    $sql->bindParam("status", $status);
    $sql->bindParam("next", $next);
    $sql->execute();
    $datos = [];
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
      $datos []= $row;
    }

    return $datos;
  }

  function create(){
    if(empty($this->idCliente) || empty($this->idTratamiento) || empty($this->fecha) || empty($this->hora)) return "ko";
    $sql = $this->db->prepare("CALL turnoCreate(:idCliente, :idTratamiento, :fecha, :hora)");
    $sql->bindParam("idCliente", $this->idCliente);
    $sql->bindParam("idTratamiento", $this->idTratamiento);
    $sql->bindParam("fecha", $this->fecha);
    $sql->bindParam("hora", $this->hora);
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }

  function update(){
    if(empty($this->id) || empty($this->status)) return "ko";

    $sql = $this->db->prepare("CALL turnoUpdate(:id, :status)");
    $sql->bindParam("id", $this->id);
    $sql->bindParam("status", $this->status);
  
    if($sql->execute()) {
      return "ok";
    }
    return "ko";
  }
};
