/*Envia la secciones asignadas al alumno por el id del alumno*/
SELECT p.nombre AS NomAlumno, s.idseccion AS id, s.nombre AS Seccion 
FROM secciones s INNER JOIN temas t ON t.seccion = s.idseccion INNER JOIN formularios f ON f.tema = t.idTema INNER JOIN asignacionaf aaf ON aaf.formulario=f.idFormulario
INNER JOIN asignacionsa asa ON aaf.asignacionSA = asa.idAsignacionSA INNER JOIN
alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON al.persona = p.idPersona WHERE al.idAlumno = 1
/*Envia la secciones asignadas al alumno*/

/*Seleccionamos los temas que hay en la seccion del alumno por el id del alumno y el id de la seccion*/
SELECT p.nombre AS NomAlumno, s.idseccion AS id, s.nombre AS Seccion, t.idTema AS idTema, t.tema AS NombreTema
FROM secciones s INNER JOIN temas t ON t.seccion = s.idseccion INNER JOIN formularios f ON f.tema = t.idTema INNER JOIN asignacionaf aaf ON aaf.formulario=f.idFormulario
INNER JOIN asignacionsa asa ON aaf.asignacionSA = asa.idAsignacionSA INNER JOIN
alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON al.persona = p.idPersona
WHERE al.idAlumno = 1 AND s.idSeccion = 1;

/*Seleccionamos el formulario que tiene el tema de la seccion del alumno por el id de alumno, seccion y de tema*/
SELECT p.nombre AS NomAlumno, s.idseccion AS id, s.nombre AS Seccion, t.tema AS NombreTema, f.idFormulario AS idFormulario, f.nombre AS Formulario
FROM secciones s INNER JOIN temas t ON t.seccion = s.idseccion INNER JOIN formularios f ON f.tema = t.idTema INNER JOIN asignacionaf aaf ON aaf.formulario=f.idFormulario
INNER JOIN asignacionsa asa ON aaf.asignacionSA = asa.idAsignacionSA INNER JOIN
alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON al.persona = p.idPersona
WHERE al.idAlumno = 1 AND s.idSeccion = 1 AND t.idTema = 1;

SELECT p.nombre AS NomAlumno, f.Nombre AS Formulario 
FROM formularios f INNER JOIN asignacionaf aaf ON aaf.formulario=f.idFormulario
INNER JOIN asignacionsa asa ON aaf.asignacionSA = asa.idAsignacionSA INNER JOIN
alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON al.persona = p.idPersona WHERE al.idAlumno = 1
/*MAnda el nombre del alumno y su formulario que tiene asignado*/

/*Seleccionamos el formulario que tiene el formulario del tema de la seccion del alumno por el id de alumno, seccion y de tema y de formulario*/
SELECT p.nombre AS NomAlumno, s.idseccion AS id, s.nombre AS Seccion, t.tema AS NombreTema, f.idFormulario AS idFormulario, f.nombre AS Formulario
FROM secciones s INNER JOIN temas t ON t.seccion = s.idseccion INNER JOIN formularios f ON f.tema = t.idTema INNER JOIN asignacionaf aaf ON aaf.formulario=f.idFormulario
INNER JOIN asignacionsa asa ON aaf.asignacionSA = asa.idAsignacionSA INNER JOIN
alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON al.persona = p.idPersona
WHERE al.idAlumno = 1 AND s.idSeccion = 1 AND t.idTema = 1;

SELECT af.idAsignacionAF AS idAsigAF, f.nombre AS NombreFormulario, ca.nombre AS Campo, ca.tipo AS tipo FROM campos ca INNER JOIN detalleformulario df ON df.campo = ca.idcampo
INNER JOIN formularios f ON df.formulario = f.idFormulario INNER JOIN asignacionaf af ON af.formulario = f.idFormulario WHERE f.idFormulario = 1
/*Envia los campos del formulario asignado y sleccionado*/


SELECT af.idAsignacionAF AS idAsigAF, f.nombre AS NombreFormulario, ca.nombre AS Campo, ca.tipo AS tipo FROM campos ca INNER JOIN detalleformulario df ON df.campo = ca.idcampo
INNER JOIN formularios f ON df.formulario = f.idFormulario INNER JOIN asignacionaf af ON af.formulario = f.idFormulario WHERE f.idFormulario = 1

SELECT rp.noRegistro AS noRegistro, rp.orden AS orden, rp.respuesta AS respuesta FROM respuestas rp WHERE rp.asignacionAF = 1 AND rp.noRegistro = 1;

SELECT rp.noRegistro AS noRegistro FROM respuestas rp WHERE rp.asignacionAF = 1  GROUP BY rp.noRegistro;
SELECT rp.noRegistro AS noRegistro FROM respuestas rp WHERE rp.asignacionAF = 1  GROUP BY rp.noRegistro DESC LIMIT 1;

/*Seleccionar campos y respuestas para cuando se edita un archivo por medio del id del registro y asignacionAF*/
SELECT af.idAsignacionAF AS idAsigAF, f.nombre AS NombreFormulario, ca.nombre AS Campo, ca.tipo AS tipo FROM campos ca INNER JOIN detalleformulario df ON df.campo = ca.idcampo
INNER JOIN formularios f ON df.formulario = f.idFormulario INNER JOIN asignacionaf af ON af.formulario = f.idFormulario WHERE f.idFormulario = 1

SELECT rp.noRegistro AS noRegistro, rp.orden AS orden, rp.respuesta AS respuesta FROM respuestas rp WHERE rp.asignacionAF = 1 AND rp.noRegistro = 1;


/*Consulta para saber que alumnos tiene asignados el supervisor mediante el id del supervisor*/
SELECT al.idAlumno AS idAlum, p.idPersona AS idPerAlum, p.nombre AS NombreAlumno, sp.idSupervisor AS idSupervisor
FROM alumnos al INNER JOIN personas p ON p.idPersona = al.persona INNER JOIN supervisores sp ON sp.persona = p.idPersona
INNER JOIN asignacionsa asa ON asa.supervisor = sp.idSupervisor WHERE sp.idSupervisor = 1;

SELECT asa.idAsignacionSA AS idASA, asa.supervisor AS idSupervisor, asa.alumno AS idAlumno, p.nombre AS nombreAlumno
FROM asignacionsa asa INNER JOIN alumnos al ON asa.alumno = al.idAlumno INNER JOIN personas p ON p.idPersona = al.persona
WHERE asa.supervisor = 1;
/*Consulta para saber que alumnos tiene asignados el supervisor mediante el id del supervisor*/