/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE DATABASE /*!32312 IF NOT EXISTS*/ `vals` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `vals`;
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `created` datetime DEFAULT NULL COMMENT 'created tiem',
  `updated` datetime DEFAULT NULL COMMENT 'updated tiem',
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `tratamiento`;
CREATE TABLE `tratamiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `titulo` varchar(50) DEFAULT NULL,
  `duracion` varchar(50) DEFAULT NULL,
  `precio` varchar(50) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `created` datetime DEFAULT NULL COMMENT 'created tiem',
  `updated` datetime DEFAULT NULL COMMENT 'updated tiem',
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `turno`;
CREATE TABLE `turno` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `idCliente` int(11) DEFAULT NULL,
  `idTratamiento` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

DROP PROCEDURE IF EXISTS clienteCreate;
DELIMITER ;;
CREATE PROCEDURE `clienteCreate`(
  $firstName VARCHAR(50), 
  $lastName VARCHAR(50),
  $gender VARCHAR(1),
  $email VARCHAR(50),
  $phone VARCHAR(50),
  $descripcion TEXT
)
BEGIN
insert into 
  vals.cliente (
    firstName, 
    lastName, 
    gender, 
    email, 
    phone, 
    descripcion, 
    created, 
    updated,
    status
  )
values
  (
    $firstName, 
    $lastName, 
    $gender, 
    $email, 
    $phone, 
    $descripcion, 
    NOW(), 
    NOW(),
    'activo'
  );
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS clienteDelete;
DELIMITER ;;
CREATE PROCEDURE `clienteDelete`(
  $id INT
)
BEGIN
update 
  cliente 
set 
  status = 'eliminado',
  updated = NOW()
where 
  id = $id
  ;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS clienteGet;
DELIMITER ;;
CREATE PROCEDURE `clienteGet`(
  IN _id INT
)
BEGIN
  SELECT *
  FROM cliente
  WHERE (id = _id OR _id IS NULL)
  AND status = 'activo'
ORDER BY firstName;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS clienteUpdate;
DELIMITER ;;
CREATE PROCEDURE `clienteUpdate`(
  $id INT,
  $firstName VARCHAR(50), 
  $lastName VARCHAR(50),
  $gender VARCHAR(1),
  $email VARCHAR(50),
  $phone VARCHAR(50),
  $descripcion TEXT
)
BEGIN
update 
  cliente 
set 
  firstName = $firstName,
  lastName = $lastName,
  gender = $gender,
  email = $email,
  phone = $phone,
  descripcion = $descripcion,
  updated = NOW()
where 
  id = $id
  ;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS tratamientoCreate;
DELIMITER ;;
CREATE PROCEDURE `tratamientoCreate`(
  IN $titulo VARCHAR(50), 
  IN $duracion VARCHAR(10),
  IN $precio VARCHAR(10),
  IN $descripcion VARCHAR(500)
)
BEGIN
insert into 
  tratamiento (
    titulo,
    duracion,
    precio,
    descripcion,
    created,
    updated
  )
values
  (
    $titulo, 
    $duracion, 
    $precio, 
    $descripcion,
    NOW(), 
    NOW()
  );
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS tratamientoDelete;
DELIMITER ;;
CREATE PROCEDURE `tratamientoDelete`(
  IN $id INT
)
BEGIN
UPDATE tratamiento 
SET 
    status = 'discontinuado',
    updated = NOW()
WHERE id = $id;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS tratamientoGet;
DELIMITER ;;
CREATE PROCEDURE `tratamientoGet`(
  IN $id INT
)
BEGIN
SELECT 
  id,
  titulo,
  duracion,
  precio,
  descripcion,
  status
FROM tratamiento
WHERE
  (id = $id OR $id IS NULL)
  AND status IS NULL
ORDER BY titulo
  ;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS tratamientoUpdate;
DELIMITER ;;
CREATE PROCEDURE `tratamientoUpdate`(
  IN $id INT, 
  IN $titulo VARCHAR(50), 
  IN $duracion VARCHAR(10),
  IN $precio VARCHAR(10),
  IN $descripcion VARCHAR(500)
)
BEGIN
UPDATE tratamiento 
SET 
    titulo = $titulo,
    duracion = $duracion,
    precio = $precio,
    descripcion = $descripcion,
    updated = NOW()
WHERE id = $id;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS turnoCreate;
DELIMITER ;;
CREATE PROCEDURE `turnoCreate`(
  IN $idCliente INT,
  IN $idTratamiento INT,
  IN $fecha DATE,
  IN $hora INT
)
BEGIN
INSERT INTO
  turno (
    idCliente,
    idTratamiento,
    fecha,
    hora,
    status,
    created,
    updated
  )
values
  (
    $idCliente,
    $idTratamiento,
    $fecha,
    $hora,
    'activo',
    NOW(), 
    NOW()
  );
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS turnoGet;
DELIMITER ;;
CREATE PROCEDURE `turnoGet`(
  IN $idTurno INT,
  IN $idCliente INT,
  IN $idTratamiento INT,
  IN $fecha DATETIME,
  IN $status VARCHAR (20),
  IN $next VARCHAR(2)
)
BEGIN
  SELECT 
    t.id AS id,
    t.idCliente AS idCliente,
    t.idTratamiento AS idTratamiento,
    t.fecha AS fecha,
    t.hora AS hora,
    t.status AS status,
    t.created AS created,
    t.updated AS updated,
    c.firstName AS firstName,
    c.lastName AS lastName,
    c.descripcion AS descripcionCliente,
    c.gender AS gender,
    tr.titulo AS titulo,
    tr.duracion AS duracion,
    tr.precio AS precio,
    tr.descripcion AS descripcion
FROM
  turno AS t
  INNER JOIN cliente AS c ON t.idCliente = c.id
  INNER JOIN tratamiento AS tr ON t.idTratamiento = tr.id
WHERE (t.id = $idTurno OR $idTurno IS NULL)
  AND (t.idCliente = $idCliente OR $idCliente IS NULL)
  AND (t.idTratamiento = $idTratamiento OR $idTratamiento IS NULL)
  AND (t.fecha = $fecha OR $fecha IS NULL)
  AND (t.status = $status OR $status IS NULL)
  AND ((t.fecha > CURDATE() AND $next = 'ok') OR $next IS NULL)
ORDER BY t.fecha ASC, t.hora ASC
;
END;;
DELIMITER ;


DROP PROCEDURE IF EXISTS turnoUpdate;
DELIMITER ;;
CREATE PROCEDURE `turnoUpdate`(
  IN $id INT,
  IN $status VARCHAR(20)
)
BEGIN
UPDATE turno
  SET status = $status
  WHERE id = $id
;
END;;
DELIMITER ;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
