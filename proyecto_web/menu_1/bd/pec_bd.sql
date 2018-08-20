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

-- Volcando estructura para procedimiento pec.ACTUALIZARALUMNO
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTUALIZARALUMNO`(IN matricula int(10), IN nombre varchar(100), IN aPaterno varchar(100), IN aMaterno varchar(100), IN telefono varchar(10), IN grupo varchar(10), IN periodo varchar(20), IN activo int(1), IN idAl int(10), IN comentario varchar(100))
BEGIN
    UPDATE alumnos SET matricula = matricula, grupo = grupo, periodo = periodo, activo = activo, comentario = comentario WHERE idAlumno = idAl; 
    select @idPersonAC := persona FROM alumnos WHERE idAlumno = idAl;
    UPDATE personas SET nombre = nombre, aPaterno = aPaterno, aMaterno = aMaterno, telefono = telefono WHERE idPersona = @idPersonAC;
END//
DELIMITER ;

-- Volcando estructura para tabla pec.administradores
CREATE TABLE IF NOT EXISTS `administradores` (
  `idAdministrador` int(10) NOT NULL AUTO_INCREMENT,
  `correo` varchar(100) NOT NULL,
  `matricula` int(10) NOT NULL,
  `persona` int(10) NOT NULL,
  PRIMARY KEY (`idAdministrador`),
  KEY `persona` (`persona`),
  CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`persona`) REFERENCES `personas` (`idPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.administradores: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
INSERT INTO `administradores` (`idAdministrador`, `correo`, `matricula`, `persona`) VALUES
	(1, 'Maira@gmail.com', 1122334455, 17);
/*!40000 ALTER TABLE `administradores` ENABLE KEYS */;

-- Volcando estructura para procedimiento pec.ALUMNO
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `ALUMNO`(IN matricula int(10), IN nombre varchar(100), IN aPaterno varchar(100), IN aMaterno varchar(100), IN telefono varchar(10), IN grupo varchar(10), IN periodo varchar(20), IN activo int(5), IN comentario varchar(100))
BEGIN

    INSERT INTO personas(nombre,aPaterno, aMaterno, telefono) VALUES(nombre, aPaterno, aMaterno, telefono);
    select @idPersona := idPersona FROM personas ORDER BY idPersona DESC LIMIT 1;
    INSERT INTO alumnos(matricula,grupo,periodo,activo,persona,comentario) VALUES(matricula, grupo,periodo,activo, @idPersona, comentario);

END//
DELIMITER ;

-- Volcando estructura para tabla pec.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `idAlumno` int(10) NOT NULL AUTO_INCREMENT,
  `matricula` int(10) NOT NULL,
  `grupo` varchar(10) NOT NULL,
  `periodo` varchar(20) NOT NULL,
  `activo` int(5) NOT NULL,
  `persona` int(10) NOT NULL,
  `comentario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idAlumno`),
  UNIQUE KEY `matricula` (`matricula`),
  KEY `persona` (`persona`),
  CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`persona`) REFERENCES `personas` (`idPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.alumnos: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` (`idAlumno`, `matricula`, `grupo`, `periodo`, `activo`, `persona`, `comentario`) VALUES
	(1, 1715110005, 'ITIC92', 'Mayo-Agosto', 1, 1, '*Sin estado*'),
	(8, 1715110333, 'ITIC92', 'Mayo-Agosto', 1, 8, NULL),
	(9, 1715110007, 'ITIC92', 'Mayo-Agosto', 1, 9, '*Sin estado*'),
	(11, 1715110999, 'ITIC92', 'Mayo-Agosto', 3, 11, NULL),
	(13, 1715110456, 'IDN91', 'Mayo-Agosto', 1, 13, NULL),
	(14, 1715110976, 'ITIC92', 'Mayo-Agosto', 3, 14, NULL),
	(15, 1715110564, 'CRIM91', 'Mayo-Agosto', 0, 15, 'baja temporal'),
	(16, 1708675432, 'ENF83', 'Enero-Abril', 1, 16, '*Sin estado*');
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;

-- Volcando estructura para tabla pec.asignacionaf
CREATE TABLE IF NOT EXISTS `asignacionaf` (
  `idAsignacionAF` int(10) NOT NULL AUTO_INCREMENT,
  `asignacionSA` int(10) NOT NULL,
  `formulario` int(10) NOT NULL,
  `fechaAsignacion` date NOT NULL,
  `fechaFin` date NOT NULL,
  PRIMARY KEY (`idAsignacionAF`),
  KEY `formulario` (`formulario`),
  KEY `asignacionSA` (`asignacionSA`),
  CONSTRAINT `asignacionaf_ibfk_1` FOREIGN KEY (`formulario`) REFERENCES `formularios` (`idFormulario`),
  CONSTRAINT `asignacionaf_ibfk_2` FOREIGN KEY (`asignacionSA`) REFERENCES `asignacionsa` (`idAsignacionSA`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.asignacionaf: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `asignacionaf` DISABLE KEYS */;
INSERT INTO `asignacionaf` (`idAsignacionAF`, `asignacionSA`, `formulario`, `fechaAsignacion`, `fechaFin`) VALUES
	(1, 1, 1, '2018-08-18', '2023-08-18');
/*!40000 ALTER TABLE `asignacionaf` ENABLE KEYS */;

-- Volcando estructura para tabla pec.asignacionsa
CREATE TABLE IF NOT EXISTS `asignacionsa` (
  `idAsignacionSA` int(10) NOT NULL AUTO_INCREMENT,
  `supervisor` int(10) NOT NULL,
  `alumno` int(10) NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `lugar` int(10) NOT NULL,
  PRIMARY KEY (`idAsignacionSA`),
  KEY `supervisor` (`supervisor`),
  KEY `alumno` (`alumno`),
  KEY `lugar` (`lugar`),
  CONSTRAINT `asignacionsa_ibfk_1` FOREIGN KEY (`supervisor`) REFERENCES `supervisores` (`idSupervisor`),
  CONSTRAINT `asignacionsa_ibfk_2` FOREIGN KEY (`alumno`) REFERENCES `alumnos` (`idAlumno`),
  CONSTRAINT `asignacionsa_ibfk_3` FOREIGN KEY (`lugar`) REFERENCES `lugares` (`idLugar`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.asignacionsa: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `asignacionsa` DISABLE KEYS */;
INSERT INTO `asignacionsa` (`idAsignacionSA`, `supervisor`, `alumno`, `periodo`, `lugar`) VALUES
	(1, 1, 1, 'Mayo-Agosto', 1);
/*!40000 ALTER TABLE `asignacionsa` ENABLE KEYS */;

-- Volcando estructura para tabla pec.campos
CREATE TABLE IF NOT EXISTS `campos` (
  `idCampo` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`idCampo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.campos: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `campos` DISABLE KEYS */;
INSERT INTO `campos` (`idCampo`, `nombre`, `tipo`) VALUES
	(1, 'Fecha', 'date'),
	(2, '# Expediente e iniciales del paciente', 'text'),
	(3, 'Firma de Supervisor', 'text'),
	(4, 'Comentario', 'text');
/*!40000 ALTER TABLE `campos` ENABLE KEYS */;

-- Volcando estructura para tabla pec.detalleformulario
CREATE TABLE IF NOT EXISTS `detalleformulario` (
  `idDetForm` int(10) NOT NULL AUTO_INCREMENT,
  `formulario` int(10) NOT NULL,
  `campo` int(10) NOT NULL,
  PRIMARY KEY (`idDetForm`),
  KEY `formulario` (`formulario`),
  KEY `campo` (`campo`),
  CONSTRAINT `detalleformulario_ibfk_1` FOREIGN KEY (`formulario`) REFERENCES `formularios` (`idFormulario`),
  CONSTRAINT `detalleformulario_ibfk_2` FOREIGN KEY (`campo`) REFERENCES `campos` (`idCampo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.detalleformulario: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `detalleformulario` DISABLE KEYS */;
INSERT INTO `detalleformulario` (`idDetForm`, `formulario`, `campo`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4);
/*!40000 ALTER TABLE `detalleformulario` ENABLE KEYS */;

-- Volcando estructura para tabla pec.formularios
CREATE TABLE IF NOT EXISTS `formularios` (
  `idFormulario` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tema` int(10) NOT NULL,
  PRIMARY KEY (`idFormulario`),
  KEY `tema` (`tema`),
  CONSTRAINT `formularios_ibfk_1` FOREIGN KEY (`tema`) REFERENCES `temas` (`idTema`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.formularios: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `formularios` DISABLE KEYS */;
INSERT INTO `formularios` (`idFormulario`, `nombre`, `tema`) VALUES
	(1, 'EXAMEN FISICO INCLUYENDO PRUEBA DE PAP', 1);
/*!40000 ALTER TABLE `formularios` ENABLE KEYS */;

-- Volcando estructura para tabla pec.lugares
CREATE TABLE IF NOT EXISTS `lugares` (
  `idLugar` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  PRIMARY KEY (`idLugar`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.lugares: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `lugares` DISABLE KEYS */;
INSERT INTO `lugares` (`idLugar`, `nombre`, `direccion`) VALUES
	(1, 'Hospital General de Tulancingo', 'La chinita y mas alla por Paxtepec');
/*!40000 ALTER TABLE `lugares` ENABLE KEYS */;

-- Volcando estructura para tabla pec.personas
CREATE TABLE IF NOT EXISTS `personas` (
  `idPersona` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `aPaterno` varchar(100) NOT NULL,
  `aMaterno` varchar(100) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`idPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.personas: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` (`idPersona`, `nombre`, `aPaterno`, `aMaterno`, `telefono`) VALUES
	(1, 'Diego David', 'Hernández', 'López', '7751591531'),
	(8, 'Jose Enrique', 'Garrido', 'Perez', '7971126582'),
	(9, 'Jaime Jose', 'Trujillo', 'Purrio', '7751506656'),
	(11, 'Antonio', 'Altamira', 'Hernandez', '7751891232'),
	(13, 'Leny', 'Ortega', 'Ruiz', '7751245643'),
	(14, 'Luis Fernando', 'Alarcon', 'Romero', '7751035462'),
	(15, 'Dhalia', 'Hernández', 'Olvera', '7751078967'),
	(16, 'Juana', 'Lopez', 'Perez', '7751027781'),
	(17, 'Maira Gabriela', 'Adame', 'Salazar', '7751230989'),
	(18, 'Marisol', 'Olvera', 'Garrido', '7711235654');
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;

-- Volcando estructura para tabla pec.respuestas
CREATE TABLE IF NOT EXISTS `respuestas` (
  `idRespuesta` int(10) NOT NULL AUTO_INCREMENT,
  `noRegistro` int(10) DEFAULT '0',
  `orden` int(10) DEFAULT NULL,
  `asignacionAF` int(10) NOT NULL,
  `respuesta` varchar(200) NOT NULL,
  `fecha` date DEFAULT NULL,
  `completado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idRespuesta`),
  KEY `asignacionAF` (`asignacionAF`),
  CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`asignacionAF`) REFERENCES `asignacionaf` (`idAsignacionAF`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.respuestas: ~32 rows (aproximadamente)
/*!40000 ALTER TABLE `respuestas` DISABLE KEYS */;
INSERT INTO `respuestas` (`idRespuesta`, `noRegistro`, `orden`, `asignacionAF`, `respuesta`, `fecha`, `completado`) VALUES
	(1, 1, 0, 1, '2018-08-18', NULL, NULL),
	(2, 1, 1, 1, '123 DDHL GOP', '2018-08-19', NULL),
	(3, 1, 2, 1, '1234', NULL, NULL),
	(4, 1, 3, 1, 'excelente', NULL, NULL),
	(5, 2, 0, 1, '2018-08-25', NULL, NULL),
	(6, 2, 1, 1, '12 DDHL', NULL, NULL),
	(7, 2, 2, 1, '1234', NULL, NULL),
	(8, 2, 3, 1, 'mal desempeño', NULL, NULL),
	(9, 3, 0, 1, '2018-08-19', NULL, NULL),
	(10, 3, 1, 1, '43 APLG', NULL, NULL),
	(11, 3, 2, 1, '1234', NULL, NULL),
	(12, 3, 3, 1, 'excelente', NULL, NULL),
	(13, 4, 0, 1, '2018-08-19', NULL, NULL),
	(14, 4, 1, 1, '89 LLGH', NULL, NULL),
	(15, 4, 2, 1, '1234', NULL, NULL),
	(16, 4, 3, 1, 'mal desempeño', NULL, NULL),
	(17, 5, 0, 1, '2018-08-19', NULL, NULL),
	(18, 5, 1, 1, '09DLPOLO', NULL, NULL),
	(19, 5, 2, 1, 'sin revision', NULL, NULL),
	(20, 5, 3, 1, 'sin revision', NULL, NULL),
	(21, 6, 0, 1, '2018-08-19', NULL, NULL),
	(22, 6, 1, 1, '12 VAS', NULL, NULL),
	(23, 6, 2, 1, 'sin revision', '2018-08-19', NULL),
	(24, 6, 3, 1, 'sin revision', NULL, NULL),
	(25, 7, 0, 1, '2018-08-19', NULL, NULL),
	(26, 7, 1, 1, '98JM', NULL, NULL),
	(27, 7, 2, 1, 'sin revision', NULL, NULL),
	(28, 7, 3, 1, 'sin revision', NULL, NULL),
	(29, 8, 0, 1, '2018-08-19', NULL, NULL),
	(30, 8, 1, 1, '22LEHL', NULL, NULL),
	(31, 8, 2, 1, 'sin revision', NULL, NULL),
	(32, 8, 3, 1, 'sin revision', NULL, NULL);
/*!40000 ALTER TABLE `respuestas` ENABLE KEYS */;

-- Volcando estructura para tabla pec.secciones
CREATE TABLE IF NOT EXISTS `secciones` (
  `idSeccion` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`idSeccion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.secciones: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
INSERT INTO `secciones` (`idSeccion`, `nombre`) VALUES
	(1, 'Seccion A - Control Prenatal');
/*!40000 ALTER TABLE `secciones` ENABLE KEYS */;

-- Volcando estructura para tabla pec.supervisores
CREATE TABLE IF NOT EXISTS `supervisores` (
  `idSupervisor` int(10) NOT NULL AUTO_INCREMENT,
  `noNomina` varchar(20) NOT NULL,
  `firma` varchar(5) NOT NULL,
  `activo` int(5) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `persona` int(10) NOT NULL,
  PRIMARY KEY (`idSupervisor`),
  UNIQUE KEY `noNomina` (`noNomina`),
  UNIQUE KEY `firma` (`firma`),
  KEY `persona` (`persona`),
  CONSTRAINT `supervisores_ibfk_1` FOREIGN KEY (`persona`) REFERENCES `personas` (`idPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.supervisores: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `supervisores` DISABLE KEYS */;
INSERT INTO `supervisores` (`idSupervisor`, `noNomina`, `firma`, `activo`, `correo`, `persona`) VALUES
	(1, '998723465', '1234', 1, 'Mari@gmail.com', 18);
/*!40000 ALTER TABLE `supervisores` ENABLE KEYS */;

-- Volcando estructura para tabla pec.temas
CREATE TABLE IF NOT EXISTS `temas` (
  `idTema` int(10) NOT NULL AUTO_INCREMENT,
  `tema` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `metas` int(5) NOT NULL,
  `seccion` int(10) NOT NULL,
  PRIMARY KEY (`idTema`),
  KEY `seccion` (`seccion`),
  CONSTRAINT `temas_ibfk_1` FOREIGN KEY (`seccion`) REFERENCES `secciones` (`idSeccion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.temas: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `temas` DISABLE KEYS */;
INSERT INTO `temas` (`idTema`, `tema`, `descripcion`, `metas`, `seccion`) VALUES
	(1, 'EXAMEN FISICO INCLUYENDO PRUEBA DE PAP', 'Tiempo cada uno: 1 hora', 25, 1),
	(2, 'CONTROL PRENATAL DE POR LOMENOS 15 DISTINTAS ', '100 examinaciones prenatales, incluyendo 20 examinaciones iniciales. Tiempo cada uno: 30 min.', 120, 1);
/*!40000 ALTER TABLE `temas` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
