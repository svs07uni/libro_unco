
------------------------------------------------------------
-- apex_dimension
------------------------------------------------------------

--- INICIO Grupo de desarrollo 10
INSERT INTO apex_dimension (proyecto, dimension, nombre, descripcion, schema, tabla, col_id, col_desc, col_desc_separador, multitabla_col_tabla, multitabla_id_tabla, fuente_datos_proyecto, fuente_datos) VALUES (
	'libro_unco', --proyecto
	'10000003', --dimension
	'posgrado', --nombre
	'Determina planes de posgrado', --descripcion
	NULL, --schema
	'plan_estudio', --tabla
	'id_plan', --col_id
	'nivel', --col_desc
	NULL, --col_desc_separador
	NULL, --multitabla_col_tabla
	NULL, --multitabla_id_tabla
	'libro_unco', --fuente_datos_proyecto
	'libro_unco'  --fuente_datos
);
--- FIN Grupo de desarrollo 10
