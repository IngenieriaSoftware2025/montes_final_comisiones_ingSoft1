-- TABLAS PRINCIPALES

CREATE TABLE jemg_usuarios(
    usuario_id SERIAL PRIMARY KEY,
    usuario_nom1 VARCHAR(50) NOT NULL,
    usuario_nom2 VARCHAR(50),
    usuario_ape1 VARCHAR(50) NOT NULL,
    usuario_ape2 VARCHAR(50),
    usuario_tel INT NOT NULL, 
    usuario_direc VARCHAR(150) NOT NULL,
    usuario_dpi VARCHAR(13) NOT NULL,
    usuario_correo VARCHAR(100) NOT NULL,
    usuario_contra LVARCHAR(1056) NOT NULL,
    usuario_token LVARCHAR(1056) NOT NULL,
    usuario_fecha_creacion DATE DEFAULT TODAY,
    usuario_fecha_contra DATE DEFAULT TODAY,
    usuario_fotografia LVARCHAR(2056),
    usuario_situacion SMALLINT DEFAULT 1
);

CREATE TABLE jemg_aplicaciones(
    app_id SERIAL PRIMARY KEY,
    app_nombre_largo VARCHAR(250) NOT NULL,
    app_nombre_medium VARCHAR(150) NOT NULL,
    app_nombre_corto VARCHAR(50) NOT NULL,
    app_fecha_creacion DATE DEFAULT TODAY,
    app_situacion SMALLINT DEFAULT 1
);

CREATE TABLE jemg_permisos(
    permiso_id SERIAL PRIMARY KEY, 
    permiso_app_id INT NOT NULL,
    permiso_nombre VARCHAR(150) NOT NULL,
    permiso_clave VARCHAR(250) NOT NULL,
    permiso_desc VARCHAR(250) NOT NULL,
    permiso_fecha DATE DEFAULT TODAY,
    permiso_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (permiso_app_id) REFERENCES jemg_aplicaciones(app_id) 
);

CREATE TABLE jemg_asig_permisos(
    asignacion_id SERIAL PRIMARY KEY,
    asignacion_usuario_id INT NOT NULL,
    asignacion_app_id INT NOT NULL,
    asignacion_permiso_id INT NOT NULL,
    asignacion_fecha DATE DEFAULT TODAY,
    asignacion_usuario_asigno INT NOT NULL,
    asignacion_motivo VARCHAR(250) NOT NULL,
    asignacion_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (asignacion_usuario_id) REFERENCES jemg_usuarios(usuario_id),
    FOREIGN KEY (asignacion_app_id) REFERENCES jemg_aplicaciones(app_id),
    FOREIGN KEY (asignacion_permiso_id) REFERENCES jemg_permisos(permiso_id)
);

CREATE TABLE jemg_rutas(
    ruta_id SERIAL PRIMARY KEY,
    ruta_app_id INT NOT NULL,
    ruta_nombre LVARCHAR(1056) NOT NULL,
    ruta_descripcion VARCHAR(250) NOT NULL,
    ruta_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (ruta_app_id) REFERENCES jemg_aplicaciones(app_id)
);

CREATE TABLE jemg_historial_act(
    historial_id SERIAL PRIMARY KEY,
    historial_usuario_id INT NOT NULL,
    historial_fecha DATETIME YEAR TO MINUTE,
    historial_ruta INT NOT NULL,
    historial_ejecucion LVARCHAR(1056) NOT NULL,
    historial_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (historial_usuario_id) REFERENCES jemg_usuarios(usuario_id),
    FOREIGN KEY (historial_ruta) REFERENCES jemg_rutas(ruta_id)
);

-- TABLAS DEL MÓDULO DE COMISIONES

CREATE TABLE jemg_comision(
    comision_id SERIAL PRIMARY KEY,
    comision_titulo VARCHAR(200) NOT NULL,
    comision_descripcion LVARCHAR(2056),
    comision_tipo VARCHAR(20) NOT NULL CHECK (comision_tipo IN ('transmisiones', 'informatica')),
    comision_fecha_inicio DATETIME YEAR TO MINUTE NOT NULL,
    comision_duracion_valor INT NOT NULL,
    comision_duracion_tipo VARCHAR(10) NOT NULL CHECK (comision_duracion_tipo IN ('horas', 'dias')),
    comision_estado VARCHAR(20) DEFAULT 'pendiente' CHECK (comision_estado IN ('pendiente', 'activa', 'finalizada', 'cancelada')),
    comision_creado_por INT NOT NULL,
    comision_fecha_creacion DATETIME YEAR TO MINUTE,
    comision_fecha_fin_calculada DATETIME YEAR TO MINUTE,
    comision_observaciones LVARCHAR(1056),
    comision_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (comision_creado_por) REFERENCES jemg_usuarios(usuario_id)
);

CREATE TABLE jemg_asignacion_comision(
    asignacion_comision_id SERIAL PRIMARY KEY,
    asignacion_comision_comision_id INT NOT NULL,
    asignacion_comision_usuario_id INT NOT NULL,
    asignacion_comision_fecha DATETIME YEAR TO MINUTE,
    asignacion_comision_asignado_por INT NOT NULL,
    asignacion_comision_observaciones LVARCHAR(1056),
    asignacion_comision_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (asignacion_comision_comision_id) REFERENCES jemg_comision(comision_id),
    FOREIGN KEY (asignacion_comision_usuario_id) REFERENCES jemg_usuarios(usuario_id),
    FOREIGN KEY (asignacion_comision_asignado_por) REFERENCES jemg_usuarios(usuario_id)
);