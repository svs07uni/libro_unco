------------------------------------------------------------
--[2562]--  DT - sede 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'libro_unco', --proyecto
	'2562', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'16', --punto_montaje
	'dt_sede', --subclase
	'datos/dt_sede.php', --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'DT - sede', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'libro_unco', --fuente_datos_proyecto
	'libro_unco', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2015-08-21 14:35:13', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'16', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'sede', --tabla
	NULL, --tabla_ext
	NULL, --alias
	'0', --modificar_claves
	'libro_unco', --fuente_datos_proyecto
	'libro_unco', --fuente_datos
	'1', --permite_actualizacion_automatica
	NULL, --esquema
	'public'  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1043', --col_id
	'id_sede', --columna
	'E', --tipo
	'1', --pk
	'sede_id_sede_seq', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1044', --col_id
	'telefono_1', --columna
	'N', --tipo
	'0', --pk
	'', --secuencia
	'8', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1045', --col_id
	'telefono_2', --columna
	'N', --tipo
	'0', --pk
	'', --secuencia
	'8', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1046', --col_id
	'telefono_3', --columna
	'N', --tipo
	'0', --pk
	'', --secuencia
	'8', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1047', --col_id
	'fax_1', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'8', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1048', --col_id
	'fax_2', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'8', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1049', --col_id
	'fax_3', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'8', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1050', --col_id
	'direccion', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'100', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1052', --col_id
	'id_localidad', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1070', --col_id
	'coordenadas_x', --columna
	'N', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1071', --col_id
	'coordenadas_y', --columna
	'N', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1094', --col_id
	'interno_1', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1095', --col_id
	'interno_2', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1096', --col_id
	'interno_3', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'libro_unco', --objeto_proyecto
	'2562', --objeto
	'1124', --col_id
	'id_unidad_academica', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'5', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'sede'  --tabla
);
--- FIN Grupo de desarrollo 0
