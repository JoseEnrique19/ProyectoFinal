-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.20-log - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para pec
CREATE DATABASE IF NOT EXISTS `pec` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pec`;

-- Volcando estructura para tabla pec.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `IdAlumno` int(5) NOT NULL AUTO_INCREMENT,
  `Matricula` int(10) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `APaterno` varchar(50) DEFAULT NULL,
  `AMaterno` varchar(50) DEFAULT NULL,
  `Grupo` varchar(50) DEFAULT NULL,
  `Generacion` date DEFAULT NULL,
  `Activo` int(5) unsigned NOT NULL,
  PRIMARY KEY (`IdAlumno`),
  UNIQUE KEY `Matricula` (`Matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Tabla donde se registraran los alumnos que utilizan el portafolio de evidencias clinicas';

-- Volcando datos para la tabla pec.alumnos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` (`IdAlumno`, `Matricula`, `Nombre`, `APaterno`, `AMaterno`, `Grupo`, `Generacion`, `Activo`) VALUES
	(1, 1715110005, 'Diego David', 'Hernández', 'López', 'ITIC92', '2018-07-24', 0),
	(2, 1715110333, 'Jose Enrique', 'Garrido', 'Perez', 'ITIC92', '2018-07-27', 1),
	(3, 1715130001, 'Yael Nereida', 'Trinidad', 'Kuri', 'ITIC92', '2018-07-27', 3),
	(4, 1715110111, 'Tatiana', 'Vera', 'Sanchez', 'IDN91', '2018-07-28', 1);
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;

-- Volcando estructura para tabla pec.c_nivel_usuario
CREATE TABLE IF NOT EXISTS `c_nivel_usuario` (
  `IdNivelUsuario` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Activo` int(1) NOT NULL,
  `UsuarioCreacion` varchar(50) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  `UsuarioUltimaModificacion` varchar(50) DEFAULT NULL,
  `FechaUltimaModificacion` datetime DEFAULT NULL,
  `Pantalla` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdNivelUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='es donde se guarda el nivel de usuarios';

-- Volcando datos para la tabla pec.c_nivel_usuario: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `c_nivel_usuario` DISABLE KEYS */;
INSERT INTO `c_nivel_usuario` (`IdNivelUsuario`, `Nombre`, `Activo`, `UsuarioCreacion`, `FechaCreacion`, `UsuarioUltimaModificacion`, `FechaUltimaModificacion`, `Pantalla`) VALUES
	(1, 'Administrador', 1, 'demo', '2018-07-24 20:41:16', 'demo', NULL, NULL);
/*!40000 ALTER TABLE `c_nivel_usuario` ENABLE KEYS */;

-- Volcando estructura para tabla pec.c_usuario
CREATE TABLE IF NOT EXISTS `c_usuario` (
  `IdUsuario` int(5) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `APaterno` varchar(50) NOT NULL,
  `AMaterno` varchar(50) DEFAULT NULL,
  `NombreUsuario` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `IdNivelUsuario` int(5) unsigned NOT NULL,
  `SesionActiva` int(1) DEFAULT NULL,
  `IPSesionActiva` varchar(15) DEFAULT NULL,
  `UltimoCambioContenidos` datetime DEFAULT NULL,
  `Activo` bit(1) NOT NULL,
  `UsuarioCreacion` varchar(50) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT NULL,
  `UsuarioUltimaModificacion` varchar(50) DEFAULT NULL,
  `FechaUltimaModificacion` datetime DEFAULT NULL,
  `Pantalla` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdUsuario`),
  KEY `FK_c_usuario_c_nivel_usuario` (`IdNivelUsuario`),
  CONSTRAINT `FK_c_usuario_c_nivel_usuario` FOREIGN KEY (`IdNivelUsuario`) REFERENCES `c_nivel_usuario` (`IdNivelUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.c_usuario: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `c_usuario` DISABLE KEYS */;
INSERT INTO `c_usuario` (`IdUsuario`, `Nombre`, `APaterno`, `AMaterno`, `NombreUsuario`, `Password`, `IdNivelUsuario`, `SesionActiva`, `IPSesionActiva`, `UltimoCambioContenidos`, `Activo`, `UsuarioCreacion`, `FechaCreacion`, `UsuarioUltimaModificacion`, `FechaUltimaModificacion`, `Pantalla`) VALUES
	(1, 'Usuario', 'Demo', 'memo', 'demo', 'ceb1cfe679a00cc7eb232971fef40568', 1, NULL, NULL, NULL, b'1', 'sistemas', NULL, 'demo', '2018-07-24 21:03:45', NULL);
/*!40000 ALTER TABLE `c_usuario` ENABLE KEYS */;

-- Volcando estructura para tabla pec.supervisores
CREATE TABLE IF NOT EXISTS `supervisores` (
  `IdSupervisor` int(5) NOT NULL AUTO_INCREMENT,
  `NoNomina` int(13) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `APaterno` varchar(50) DEFAULT NULL,
  `AMaterno` varchar(50) DEFAULT NULL,
  `Telefono` varchar(50) DEFAULT NULL,
  `Activo` bit(1) NOT NULL,
  `Generacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdSupervisor`),
  UNIQUE KEY `NoNomina` (`NoNomina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='es la tabla que contiene todos los supervisores que participan en el proyecto PEC';

-- Volcando datos para la tabla pec.supervisores: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `supervisores` DISABLE KEYS */;
/*!40000 ALTER TABLE `supervisores` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
