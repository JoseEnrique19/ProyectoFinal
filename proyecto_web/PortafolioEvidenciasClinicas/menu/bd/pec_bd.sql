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
CREATE DEFINER=`root`@`localhost` PROCEDURE `ACTUALIZARALUMNO`(IN matricula int(10), IN nombre varchar(100), IN aPaterno varchar(100), IN aMaterno varchar(100), IN telefono varchar(10), IN grupo varchar(10), IN periodo varchar(20), IN activo tinyint(1), IN idAl int(10))
BEGIN
    UPDATE alumnos SET matricula = matricula, grupo = grupo, periodo = periodo, activo = activo WHERE idAlumno = idAl; 
    select @idPersonAc := persona FROM alumnos WHERE idAlumno = idAl;
    UPDATE personas SET nombre = nombre, aPaterno = aPaterno, aMaterno = aMaterno, telefono = telefono WHERE idPersona = @idPersonAc;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.administradores: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
/*!40000 ALTER TABLE `administradores` ENABLE KEYS */;

-- Volcando estructura para procedimiento pec.ALUMNO
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `ALUMNO`(IN matricula int(10), IN nombre varchar(100), IN aPaterno varchar(100), IN aMaterno varchar(100), IN telefono varchar(10), IN grupo varchar(10), IN periodo varchar(20), IN activo tinyint(1))
BEGIN

    INSERT INTO personas(nombre,aPaterno, aMaterno, telefono) VALUES(nombre, aPaterno, aMaterno, telefono);
    select @idPersona := idPersona FROM personas ORDER BY idPersona DESC LIMIT 1;
    INSERT INTO alumnos(matricula,grupo,periodo,activo,persona) VALUES(matricula, grupo,periodo,activo, @idPersona);

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
  PRIMARY KEY (`idAlumno`),
  UNIQUE KEY `matricula` (`matricula`),
  KEY `persona` (`persona`),
  CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`persona`) REFERENCES `personas` (`idPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.alumnos: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` (`idAlumno`, `matricula`, `grupo`, `periodo`, `activo`, `persona`) VALUES
	(1, 1715110005, 'ITIC92', 'Mayo-Agosto', 1, 1),
	(8, 1715110333, 'ITIC92', 'Mayo-Agosto', 1, 8),
	(9, 1715110007, 'ITIC92', 'Mayo-Agosto', 1, 9);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.asignacionaf: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `asignacionaf` DISABLE KEYS */;
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
  CONSTRAINT `asignacionsa_ibfk_1` FOREIGN KEY (`supervisor`) REFERENCES `supervisores` (`idSuperisor`),
  CONSTRAINT `asignacionsa_ibfk_2` FOREIGN KEY (`alumno`) REFERENCES `alumnos` (`idAlumno`),
  CONSTRAINT `asignacionsa_ibfk_3` FOREIGN KEY (`lugar`) REFERENCES `lugares` (`idLugar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.asignacionsa: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `asignacionsa` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignacionsa` ENABLE KEYS */;

-- Volcando estructura para tabla pec.campos
CREATE TABLE IF NOT EXISTS `campos` (
  `idCampo` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`idCampo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.campos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `campos` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.detalleformulario: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `detalleformulario` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalleformulario` ENABLE KEYS */;

-- Volcando estructura para tabla pec.formularios
CREATE TABLE IF NOT EXISTS `formularios` (
  `idFormulario` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tema` int(10) NOT NULL,
  PRIMARY KEY (`idFormulario`),
  KEY `tema` (`tema`),
  CONSTRAINT `formularios_ibfk_1` FOREIGN KEY (`tema`) REFERENCES `temas` (`idTema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.formularios: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `formularios` DISABLE KEYS */;
/*!40000 ALTER TABLE `formularios` ENABLE KEYS */;

-- Volcando estructura para tabla pec.lugares
CREATE TABLE IF NOT EXISTS `lugares` (
  `idLugar` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  PRIMARY KEY (`idLugar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.lugares: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `lugares` DISABLE KEYS */;
/*!40000 ALTER TABLE `lugares` ENABLE KEYS */;

-- Volcando estructura para tabla pec.personas
CREATE TABLE IF NOT EXISTS `personas` (
  `idPersona` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `aPaterno` varchar(100) NOT NULL,
  `aMaterno` varchar(100) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`idPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.personas: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` (`idPersona`, `nombre`, `aPaterno`, `aMaterno`, `telefono`) VALUES
	(1, 'Diego David', 'Hernández', 'López', '7751591531'),
	(8, 'Jose Enrique', 'Garrido', 'Perez', '7971126582'),
	(9, 'Jaime', 'Trujillo', 'Purrio', '7751506656');
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;

-- Volcando estructura para tabla pec.respuestas
CREATE TABLE IF NOT EXISTS `respuestas` (
  `idRespuesta` int(10) NOT NULL AUTO_INCREMENT,
  `orden` int(10) NOT NULL,
  `asignacionAF` int(10) NOT NULL,
  `respuesta` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `completado` tinyint(1) NOT NULL,
  PRIMARY KEY (`idRespuesta`),
  KEY `asignacionAF` (`asignacionAF`),
  CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`asignacionAF`) REFERENCES `asignacionaf` (`idAsignacionAF`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.respuestas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `respuestas` DISABLE KEYS */;
/*!40000 ALTER TABLE `respuestas` ENABLE KEYS */;

-- Volcando estructura para tabla pec.secciones
CREATE TABLE IF NOT EXISTS `secciones` (
  `idSeccion` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`idSeccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.secciones: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `secciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `secciones` ENABLE KEYS */;

-- Volcando estructura para tabla pec.supervisores
CREATE TABLE IF NOT EXISTS `supervisores` (
  `idSuperisor` int(10) NOT NULL AUTO_INCREMENT,
  `noNomina` varchar(20) NOT NULL,
  `firma` varchar(5) NOT NULL,
  `activo` int(5) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `persona` int(10) NOT NULL,
  PRIMARY KEY (`idSuperisor`),
  UNIQUE KEY `noNomina` (`noNomina`),
  UNIQUE KEY `firma` (`firma`),
  KEY `persona` (`persona`),
  CONSTRAINT `supervisores_ibfk_1` FOREIGN KEY (`persona`) REFERENCES `personas` (`idPersona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.supervisores: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `supervisores` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pec.temas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `temas` DISABLE KEYS */;
/*!40000 ALTER TABLE `temas` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
