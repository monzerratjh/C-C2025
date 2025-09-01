CREATE TABLE grupo (
	id_grupo int PRIMARY KEY NOT NULL,
	orientacion_grupo varchar(50) NOT NULL,
	turno_grupo varchar(50) NOT NULL,
	nombre_grupo varchar(50) NOT NULL,
	cantidad_alumno_grupo int NOT NULL,
	id_adscripo int NOT NULL,
	id_secretario int NOT NULL
);

CREATE TABLE secretario_administra_recurso (
	id_secretario int NOT NULL,
	id_recurso int NOT NULL
);


CREATE TABLE recurso (
	id_recurso int PRIMARY KEY NOT NULL,
	disponibilidad_recurso varchar(30) NOT NULL,
	nombre_recurso varchar(100) NOT NULL,
	historial_recurso varchar(300),
	tipo_recurso varchar(100) NOT NULL,
	estado_recurso varchar(50) NOT NULL,
	id_espacio int NOT NULL
);



CREATE TABLE usuario (
	id_usuario int PRIMARY KEY NOT NULL,
	nombre_usuario varchar(120) NOT NULL,
	apellido_usuario varchar(120) NOT NULL,
	gmail_usuario varchar(200) NOT NULL,
	telefono_usuario int NOT NULL
);


CREATE TABLE secretario (
	id_secretario int PRIMARY KEY NOT NULL,
	grado_secretario varchar(50) NOT NULL,
	horario_trabajo_secretario time NOT NULL,
    id_usuario int NOT NULL
);


CREATE TABLE docente (
	id_docente int PRIMARY KEY NOT NULL,
	grado_docente varchar(30) NOT NULL,
    id_usuario int NOT NULL
);


CREATE TABLE docente_pide_recurso (
	id_docente int NOT NULL,
	id_recurso int NOT NULL
);


CREATE TABLE adcripto (
	id_adscripto int PRIMARY KEY NOT NULL,
    id_usuario int NOT NULL,
	cantidad_grupos_asignados int NOT NULL,
	horario_trabajo_adscripto time NOT NULL,
	caracter_cargo_adscripto varchar(100) NOT NULL
);


CREATE TABLE espacio (
	id_espacio int PRIMARY KEY NOT NULL, 
	nombre_espacio varchar (120) NOT NULL,
	capacidad_espacio int NOT NULL,
	historial_espacio varchar(300),
    disponibilidad_espacio varchar(300)
);

CREATE TABLE horario_clase (
    id_horario_clase int PRIMARY KEY NOT NULL,
	hora_reloj_horario_clase time NOT NULL,
	id_asignatura int NOT NULL
);


CREATE TABLE adscripto_organiza_horario_clase (
	id_adscripto int,
	id_horario_clase int
);

CREATE TABLE asignatura_docente_solicita_espacio (
	id_asignatura int NOT NULL,
	id_docente int NOT NULL,
	fecha_hora_reserva timestamp NOT NULL,
	hora_clase time NOT NULL,
	id_espacio int NOT NULL
);


CREATE TABLE asignatura (
	id_asignatura int PRIMARY KEY NOT NULL,
	cantidad_horas_asignatura int NOT NULL,
	nombre_asignatura varchar(30) NOT NULL
 );


CREATE TABLE docente_tiene_grupo (
	id_grupo int NOT NULL,
	id_docente int NOT NULL, 
	id_asignatura int NOT NULL
);


CREATE TABLE docente_dicta_asignatura (
	id_docente int NOT NULL,
	id_asignatura int NOT NULL
);

