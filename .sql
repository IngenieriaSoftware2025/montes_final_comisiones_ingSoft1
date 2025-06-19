
CREATE TABLE jemg_usuario(
usuario_id SERIAL PRIMARY KEY,
usuario_nom1 VARCHAR (50) NOT NULL,
usuario_nom2 VARCHAR (50) NOT NULL,
usuario_ape1 VARCHAR (50) NOT NULL,
usuario_ape2 VARCHAR (50) NOT NULL,
usuario_tel INT NOT NULL, 
usuario_direc VARCHAR (150) NOT NULL,
usuario_dpi VARCHAR (13) NOT NULL,
usuario_correo VARCHAR (100) NOT NULL,
usuario_contra LVARCHAR (1056) NOT NULL,
usuario_token LVARCHAR (1056) NOT NULL,
usuario_fecha_creacion DATE DEFAULT TODAY,
usuario_fecha_contra DATE DEFAULT TODAY,
usuario_fotografia LVARCHAR (2056),
usuario_situacion SMALLINT DEFAULT 1
);

CREATE TABLE jemg_aplicacion(
app_id SERIAL PRIMARY KEY,
app_nombre_largo VARCHAR (250) NOT NULL,
app_nombre_medium VARCHAR (150) NOT NULL,
app_nombre_corto VARCHAR (50) NOT NULL,
app_fecha_creacion DATE DEFAULT TODAY,
app_situacion SMALLINT DEFAULT 1
);

CREATE TABLE jemg_rol(
    rol_id SERIAL PRIMARY KEY,
    rol_nombre VARCHAR(75),
    rol_nombre_ct VARCHAR(25),
    app_id INTEGER,
    rol_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (app_id) REFERENCES jemg_aplicacion(app_id)
);

CREATE TABLE jemg_permiso(
permiso_id SERIAL PRIMARY KEY, 
permiso_app_id INT NOT NULL,
permiso_nombre VARCHAR (150) NOT NULL,
permiso_clave VARCHAR (250) NOT NULL,
permiso_desc VARCHAR (250) NOT NULL,
permiso_fecha DATE DEFAULT TODAY,
permiso_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (permiso_app_id) REFERENCES jemg_aplicacion(app_id) 
);

CREATE TABLE jemg_asig_permisos(
asignacion_id SERIAL PRIMARY KEY,
asignacion_usuario_id INT NOT NULL,
asignacion_app_id INT NOT NULL,
asignacion_permiso_id INT NOT NULL,
asignacion_fecha DATE DEFAULT TODAY,
asignacion_fecha_retiro DATETIME YEAR TO SECOND,
asignacion_usuario_asigno INT NOT NULL,
asignacion_motivo VARCHAR (250) NOT NULL,
asignacion_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (asignacion_usuario_id) REFERENCES usuario(usuario_id),
FOREIGN KEY (asignacion_app_id) REFERENCES jemg_aplicacion(app_id),
FOREIGN KEY (asignacion_permiso_id) REFERENCES permiso(permiso_id)
);




INSERT INTO jemg_rol (rol_nombre, rol_nombre_ct, app_id) VALUES 
('ADMINISTRADOR', 'ADMIN', 1),


INSERT INTO jemg_permiso (permiso_app_id, permiso_nombre, permiso_clave, permiso_desc) VALUES 
(1, 'Administrar Sistema', 'ADMIN_SYSTEM', 'Permiso para administrar el sistema completo')


INSERT INTO jemg_aplicacion (app_nombre_largo, app_nombre_medium, app_nombre_corto) VALUES 
('Sistema de Gestión de usuarios', 'Gestión Usuarios', 'Usuarios')