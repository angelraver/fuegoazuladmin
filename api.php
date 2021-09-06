<?php
require './headers.php';
require './utils.php';
require './database.php';
require './controller/tratamientoController.php';
require './controller/clienteController.php';
require './controller/turnoController.php';

$params = json_decode(file_get_contents("php://input"));
$method = $_SERVER['REQUEST_METHOD'];
$uris = explode("/", "$_SERVER[REQUEST_URI]");
$endpoint = isset($uris[2]) ? $uris[2] : NULL;
$id = isset($uris[3]) ? $uris[3] : NULL;

switch ($endpoint) {
  case 'tratamiento':
    $tratamiento = new TratamientoController($params);
    switch($method) {
      case 'GET':
        $data = $tratamiento->get($id);
        echo json_encode($data);
      break;
      case 'POST':
        if (isset($params->delete)) {
          $result = $tratamiento->delete($id);
        } else {
          if (!isset($id)) {
            $result = $tratamiento->create();
          } else {
            $result = $tratamiento->update();
          }
        }
        echo json_encode("{ status: $result }");
      break;
    }
  break;
  case 'cliente':
    $cliente = new ClienteController($params);
    switch($method) {
      case 'GET':
        $data = $cliente->get($id);
        echo json_encode($data);
      break;
      case 'POST':
        if (isset($params->delete)) {
          $result = $cliente->delete($id);
        } else {
          if (is_null($id)) {
            $result = $cliente->create();
          } else {
            $result = $cliente->update();
          }
        }
        echo json_encode("{ status: $result }");
      break;
    }
  break;
  case 'turno':
    $turno = new TurnoController($params);
    switch($method) {
      case 'POST':
        if (isset($params->get)) {
          $data = $turno->get();
          echo json_encode($data);
        } else {
          if (!isset($params->id)) {
            $result = $turno->create();
          } else {
            $result = $turno->update();
          }
          echo json_encode("{ status: $result }");
        }
      break;
    }
  break;
  default:
    echo 'Nada por aquí...';
  break;
};
