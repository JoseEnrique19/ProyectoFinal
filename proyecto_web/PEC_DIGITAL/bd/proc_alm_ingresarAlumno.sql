DELIMITER $$
CREATE PROCEDURE ALUMNO(IN matricula int(10), IN nombre varchar(100), IN aPaterno varchar(100), IN aMaterno varchar(100), IN telefono varchar(10), IN grupo varchar(10), IN periodo varchar(20), IN activo int(5), IN comentario varchar(100))
BEGIN

    INSERT INTO personas(nombre,aPaterno, aMaterno, telefono) VALUES(nombre, aPaterno, aMaterno, telefono);
    select @idPersona := idPersona FROM personas ORDER BY idPersona DESC LIMIT 1;
    INSERT INTO alumnos(matricula,grupo,periodo,activo,persona,comentario) VALUES(matricula, grupo,periodo,activo, @idPersona, comentario);

END
$$
DELIMITER ;

CALL ALUMNO(1715110005,'DiegoDavid','Hernandez','Lopez','7751591531','ITIC92','Mayo-Agosto',1);

DROP PROCEDURE ALUMNO;

SELECT * FROM personas INNER JOIN alumnos ON alumnos.persona = personas.idPersona;


DELIMITER $$
CREATE PROCEDURE ACTUALIZARALUMNO(IN matricula int(10), IN nombre varchar(100), IN aPaterno varchar(100), IN aMaterno varchar(100), IN telefono varchar(10), IN grupo varchar(10), IN periodo varchar(20), IN activo int(1), IN idAl int(10), IN comentario varchar(100))
BEGIN
    UPDATE alumnos SET matricula = matricula, grupo = grupo, periodo = periodo, activo = activo, comentario = comentario WHERE idAlumno = idAl; 
    select @idPersonAC := persona FROM alumnos WHERE idAlumno = idAl;
    UPDATE personas SET nombre = nombre, aPaterno = aPaterno, aMaterno = aMaterno, telefono = telefono WHERE idPersona = @idPersonAC;
END
$$
DELIMITER ;