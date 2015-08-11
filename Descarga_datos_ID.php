<?php


	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	$sql =mysql_query("select*from(
	select llave,F_Sincron,USUARIO,Cargo_gme,dominio.NOM_PE,Otro_PE,NOM_PGE,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,Departamento,Muncipio,NOM_VDA,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,AÑO,FASE,TEMA,Otro_Tema,OBSERVACION,NULL AS FUERZA ,Ini_Jorna,Fin_Jorna,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos
		from
	
	(select llave,F_Sincron,USUARIO,Cargo_gme,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`Departamento`,Muncipio,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,dominio.LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,AÑO, FASE
	from
		(select 
		llave,F_Sincron,USUARIO,Cargo_gme,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`Departamento`,Muncipio,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,dominio.LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,AÑO, FASE,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
			from
				(select
				llave,F_Sincron,USUARIO,Cargo_gme,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`Departamento`,Muncipio,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,dominio.TEMA,Otro_Tema,OBSERVACION,SUBSTR(`FECHA_REPORT`,9,2) as AÑO,SUBSTRING((CASE  WHEN NOM_PE ='99' THEN Otro_PE ELSE NOM_PE END ),3,2) as FASE,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
					from
							
						(select
						llave,F_Sincron,USUARIO,Cargo_gme,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,dominio.TIPO_INFORME,FECHA_REPORT,DIA,MES,`Departamento`,Muncipio,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
							from

								(select
								llave,F_Sincron,USUARIO,Cargo_gme,`NOM_PE`,Otro_PE,dominio.`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`Departamento`,Muncipio,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
									from
										(select 
										llave,F_Sincron,dominio.USUARIO,Cargo_gme,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`Departamento`,Muncipio,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
											from
												(select
												llave,F_Sincron,`USUARIO`,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,dominio.`Departamento`,dominio.`Muncipio`,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
													from

														(Select
														llave,F_Sincron,`USUARIO`,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,4_Epidemia,4_Novedad_climatologica,4_Registro_de_cultivos,4_Zona_con_cultivos_muy_dispersos,4_Zona_de_cruce_de_rios_caudalosos,4_Zona_sin_cultivos,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION ,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
															from

																(select
																llave,F_Sincron,`USUARIO`,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,3_AEI_controlado,3_AEI_no_controlado,3_Bloqueo_parcial_de_la_comunidad,3_Bloqueo_total_de_la_comunidad,3_Combate,3_Hostigamiento,3_MAP_Controlada,3_MAP_No_controlada,3_Operaciones_de_seguridad,Novedad,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION ,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri

																from

																		(select
																		llave,F_Sincron,`USUARIO`,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,2_A_la_espera_definicion_nuevo_punto_FP,2_Espera_helicoptero_FP_de_seguridad,2_Espera_helicoptero_FP_que_abastece,2_Induccion_FP,2_Novedad_canino_o_del_grupo_de_deteccion,2_Problemas_fuerza_publica,2_Sin_seguridad,Sit_Seguridad,Novedad,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION ,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri

																		from
																			(select 
																			llave,F_Sincron,`USUARIO`,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,1_Abastecimiento,1_Acompanamiento_firma_GME,1_Apoyo_zonal_sin_punto_asignado,1_Descanso_en_dia_habil,1_Descanso_festivo_dominical,1_Dia_compensatorio,1_Erradicacion_en_dia_festivo,1_Espera_helicoptero_Helistar,1_Extraccion,1_Firma_contrato_GME,1_Induccion_Apoyo_Zonal,1_Insercion,1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,1_Novedad_apoyo_zonal,1_Novedad_enfermero,1_Punto_fuera_del_area_de_erradicacion,1_Transporte_bus,1_Traslado_apoyo_zonal,1_Traslado_area_vivac,Adm_Fuerza,Sit_Seguridad,Novedad,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION ,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
																				from

																					(select llave,F_Sincron,`USUARIO`,`NOM_PE`,Otro_PE,`NOM_PGE`,Otro_NOM_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_REPORT,DIA,MES,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,Ha_Coca,Ha_Amapola,Ha_Marihuana,T_erradi,LATITUD_sector,GRA_LAT_Sector,MIN_LAT_Sector,SEG_LAT_Sector,GRA_LONG_Sector,MIN_LONG_Sector,SEG_LONG_Sector,Ini_Jorna,Fin_Jorna,Situ_Especial,Adm_GME,Adm_Fuerza,Sit_Seguridad,Novedad,Num_Erra_Salen,Num_Erra_Quedan,No_ENFERMERO,NUM_FP,NUM_Perso_EVA,NUM_Poli,TEMA,Otro_Tema,OBSERVACION,LATITUD_segurid,GRA_LAT_segurid,MIN_LAT_segurid,SEG_LAT_segurid,GRA_LONG_seguri,MIN_LONG_seguri,SEG_LONG_seguri
																						from
																							(SELECT `_URI` as llave,_SUBMISSION_DATE as F_Sincron,`USUARIO`,`NOM_PE`, NOM_PE_NEW as Otro_PE,`NOM_PGE`,GRUPO2_NOM_PGE_NEW as Otro_NOM_PGE,GRUPO2_CC_PGE_NEW as Otro_CC_PGE,TIPO_INFORME,DATE_FORMAT(FECHA_REPORT,'%d/%m/%Y') as FECHA_REPORT,DATE_FORMAT(FECHA_REPORT,'%d') as DIA,DATE_FORMAT(FECHA_REPORT,'%m') as MES, `NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,GRUPO4_CI_COCA as Ha_Coca,GRUPO4_CI_AMAPOLA as Ha_Amapola,GRUPO4_CI_MARIHUANA as Ha_Marihuana,cast(SUM as decimal(10,3))as T_erradi,LATITUD_sector,Grupo10_GRA_LAT_sector as GRA_LAT_Sector,Grupo10_MIN_LAT_sector as MIN_LAT_Sector,Grupo10_SEG_LAT_sector as SEG_LAT_Sector,Grupo11_GRA_LONG_sector as GRA_LONG_Sector,Grupo11_MIN_LONG_sector as MIN_LONG_Sector,Grupo11_SEG_LONG_sector  as SEG_LONG_Sector,DATE_FORMAT(HORA_INICIO,'%T') as Ini_Jorna, DATE_FORMAT(HORA_FINAL,'%T') as Fin_Jorna,SITUACION as Situ_Especial,No_ERRADICA_S as Num_Erra_Salen,No_ERRADICA_Q as Num_Erra_Quedan,No_ENFERMERO,No_FP as NUM_FP,No_PERSONAS as NUM_Perso_EVA,No_POLICIA as NUM_Poli,TEMA,TEMA_NEW as Otro_Tema,OBSERVACION,Grupo6_GRA_LAT as GRA_LAT_segurid,Grupo6_MIN_LAT as MIN_LAT_segurid,Grupo6_SEG_LAT as SEG_LAT_segurid,LATITUD as LATITUD_segurid,Grupo7_GRA_LONG as GRA_LONG_seguri,Grupo7_MIN_LONG as MIN_LONG_seguri,Grupo7_SEG_LONG as SEG_LONG_seguri
																								FROM `informe_apoyo_zonal_diario_v2_f2_2014_core`
																							) as ID 
																						join
																							(SELECT llave2, sum(CASE WHEN ID1.situacion = 'a' THEN 1 ELSE 0 END ) as Adm_GME, sum(CASE WHEN ID1.situacion = 'b' THEN 1 ELSE 0 END ) as Adm_Fuerza, sum(CASE WHEN ID1.situacion = 'c' THEN 1 ELSE 0 END ) as Sit_Seguridad, sum(CASE WHEN ID1.situacion = 'd' THEN 1 ELSE 0 END ) as Novedad  
																								FROM 
																									(SELECT informe_apoyo_zonal_diario_v2_f2_2014_core.`_URI` as llave2, VALUE as situacion 
																										from 
																											`informe_apoyo_zonal_diario_v2_f2_2014_core` 
																												LEFT join informe_apoyo_zonal_diario_v2_f2_2014_tipo_situacion on informe_apoyo_zonal_diario_v2_f2_2014_core._URI=informe_apoyo_zonal_diario_v2_f2_2014_tipo_situacion._PARENT_AURI 
																									) as ID1 GROUP by llave2
																							) as ID2 on ID2.llave2=ID.llave
																					)as ID3

																				join
																					(SELECT llave3, sum(CASE WHEN ID4.Ad_GME = '1.1' THEN 1 ELSE 0 END ) as 1_Abastecimiento,sum(CASE WHEN ID4.Ad_GME = '1.2' THEN 1 ELSE 0 END ) as 1_Acompanamiento_firma_GME,sum(CASE WHEN ID4.Ad_GME = '1.3' THEN 1 ELSE 0 END ) as 1_Apoyo_zonal_sin_punto_asignado,sum(CASE WHEN ID4.Ad_GME = '1.4' THEN 1 ELSE 0 END ) as 1_Descanso_en_dia_habil,sum(CASE WHEN ID4.Ad_GME = '1.5' THEN 1 ELSE 0 END ) as 1_Descanso_festivo_dominical,sum(CASE WHEN ID4.Ad_GME = '1.6' THEN 1 ELSE 0 END ) as 1_Dia_compensatorio,sum(CASE WHEN ID4.Ad_GME = '1.7' THEN 1 ELSE 0 END ) as 1_Erradicacion_en_dia_festivo,sum(CASE WHEN ID4.Ad_GME = '1.8' THEN 1 ELSE 0 END ) as 1_Espera_helicoptero_Helistar,sum(CASE WHEN ID4.Ad_GME = '1.9' THEN 1 ELSE 0 END ) as 1_Extraccion,sum(CASE WHEN ID4.Ad_GME = '1.10' THEN 1 ELSE 0 END ) as 1_Firma_contrato_GME,sum(CASE WHEN ID4.Ad_GME = '1.11' THEN 1 ELSE 0 END ) as 1_Induccion_Apoyo_Zonal,sum(CASE WHEN ID4.Ad_GME = '1.12' THEN 1 ELSE 0 END ) as 1_Insercion,sum(CASE WHEN ID4.Ad_GME = '1.13' THEN 1 ELSE 0 END ) as 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase,sum(CASE WHEN ID4.Ad_GME = '1.14' THEN 1 ELSE 0 END ) as 1_Novedad_apoyo_zonal,sum(CASE WHEN ID4.Ad_GME = '1.15' THEN 1 ELSE 0 END ) as 1_Novedad_enfermero,sum(CASE WHEN ID4.Ad_GME = '1.16' THEN 1 ELSE 0 END ) as 1_Punto_fuera_del_area_de_erradicacion,sum(CASE WHEN ID4.Ad_GME = '1.17' THEN 1 ELSE 0 END ) as 1_Transporte_bus,sum(CASE WHEN ID4.Ad_GME = '1.18' THEN 1 ELSE 0 END ) as 1_Traslado_apoyo_zonal,sum(CASE WHEN ID4.Ad_GME = '1.19' THEN 1 ELSE 0 END ) as 1_Traslado_area_vivac  
																						FROM 
																							(SELECT informe_apoyo_zonal_diario_v2_f2_2014_core.`_URI` as llave3, VALUE as Ad_GME 
																								from 
																									`informe_apoyo_zonal_diario_v2_f2_2014_core` 
																										 LEFT join informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_gme on informe_apoyo_zonal_diario_v2_f2_2014_core._URI=informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_gme._PARENT_AURI 
																							) as ID4 GROUP by llave3
																					) as ID5 on ID5.llave3=ID3.llave		
																			)as ID6	
																		join	
																			(SELECT llave4, sum(CASE WHEN ID7.Ad_FP = '2.1' THEN 1 ELSE 0 END ) as 2_A_la_espera_definicion_nuevo_punto_FP,sum(CASE WHEN ID7.Ad_FP = '2.2' THEN 1 ELSE 0 END ) as 2_Espera_helicoptero_FP_de_seguridad,sum(CASE WHEN ID7.Ad_FP = '2.3' THEN 1 ELSE 0 END ) as 2_Espera_helicoptero_FP_que_abastece,sum(CASE WHEN ID7.Ad_FP = '2.4' THEN 1 ELSE 0 END ) as 2_Induccion_FP,sum(CASE WHEN ID7.Ad_FP = '2.5' THEN 1 ELSE 0 END ) as 2_Novedad_canino_o_del_grupo_de_deteccion,sum(CASE WHEN ID7.Ad_FP = '2.6' THEN 1 ELSE 0 END ) as 2_Problemas_fuerza_publica,sum(CASE WHEN ID7.Ad_FP = '2.7' THEN 1 ELSE 0 END ) as 2_Sin_seguridad

																						FROM 
																							(SELECT informe_apoyo_zonal_diario_v2_f2_2014_core.`_URI` as llave4, VALUE as Ad_FP
																								from 
																									`informe_apoyo_zonal_diario_v2_f2_2014_core` 
																										LEFT join informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_fp on informe_apoyo_zonal_diario_v2_f2_2014_core._URI=informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_fp._PARENT_AURI 
																							) as ID7 GROUP by llave4
																					) as ID8 on ID8.llave4=ID6.llave
																		) as ID9
																	join	
																		(SELECT llave5, sum(CASE WHEN ID10.Seg = '3.1' THEN 1 ELSE 0 END ) as 3_AEI_controlado,sum(CASE WHEN ID10.Seg = '3.2' THEN 1 ELSE (CASE WHEN ID10.Seg = '3.3' THEN 1 ELSE 0 END ) END ) as 3_AEI_no_controlado,sum(CASE WHEN ID10.Seg = '3.4' THEN 1 ELSE 0 END ) as 3_Bloqueo_parcial_de_la_comunidad,sum(CASE WHEN ID10.Seg = '3.5' THEN 1 ELSE 0 END ) as 3_Bloqueo_total_de_la_comunidad,sum(CASE WHEN ID10.Seg = '3.6' THEN 1 ELSE 0 END ) as 3_Combate,sum(CASE WHEN ID10.Seg = '3.7' THEN 1 ELSE 0 END ) as 3_Hostigamiento,sum(CASE WHEN ID10.Seg = '3.8' THEN 1 ELSE 0 END ) as 3_MAP_Controlada,sum(CASE WHEN ID10.Seg = '3.9' THEN 1 ELSE 0 END ) as 3_MAP_No_controlada,sum(CASE WHEN ID10.Seg = '3.10' THEN 1 ELSE 0 END ) as 3_Operaciones_de_seguridad

																					FROM 
																						(SELECT informe_apoyo_zonal_diario_v2_f2_2014_core.`_URI` as llave5, VALUE as Seg
																							from 
																								`informe_apoyo_zonal_diario_v2_f2_2014_core` 
																									LEFT join informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_seg on informe_apoyo_zonal_diario_v2_f2_2014_core._URI=informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_seg._PARENT_AURI 
																						) as ID10 GROUP by llave5
																		) as ID11 on ID11.llave5=ID9.llave
																) as ID12
															join	
																(SELECT llave6, sum(CASE WHEN ID13.Nov= '4.1' THEN 1 ELSE 0 END ) as 4_Epidemia,sum(CASE WHEN ID13.Nov= '4.2' THEN 1 ELSE 0 END ) as 4_Novedad_climatologica,sum(CASE WHEN ID13.Nov= '4.3' THEN 1 ELSE 0 END ) as 4_Registro_de_cultivos,sum(CASE WHEN ID13.Nov= '4.4' THEN 1 ELSE 0 END ) as 4_Zona_con_cultivos_muy_dispersos,sum(CASE WHEN ID13.Nov= '4.5' THEN 1 ELSE 0 END ) as 4_Zona_de_cruce_de_rios_caudalosos,sum(CASE WHEN ID13.Nov= '4.6' THEN 1 ELSE 0 END ) as 4_Zona_sin_cultivos

																			FROM 
																				(SELECT informe_apoyo_zonal_diario_v2_f2_2014_core.`_URI` as llave6, VALUE as Nov
																					from 
																						`informe_apoyo_zonal_diario_v2_f2_2014_core` 
																							LEFT join informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_nov on informe_apoyo_zonal_diario_v2_f2_2014_core._URI=informe_apoyo_zonal_diario_v2_f2_2014_grupo5_admin_nov._PARENT_AURI 
																				) as ID13 GROUP by llave6
																) as ID14 on ID14.llave6=ID12.llave
														) as ID15	
													left join 
														(select label as Muncipio, departamento as Departamento,name from `dominio` where `list name`='municipio'
														) as dominio on ID15.NOM_MPIO=dominio.name
												)as ID16
											left join 
												(select label as USUARIO,name, departamento as Cargo_gme from `dominio` where `list name`='usuario'
												)as dominio on ID16.USUARIO=dominio.name 
										)as ID17
									left join 
										(select label as NOM_PGE,name from `dominio` where `list name`='GE'
										)as dominio on ID17.NOM_PGE=dominio.name 
								)as ID18		
							left join 
								(select label as TIPO_INFORME,name from `dominio` where `list name`='informe'
								)as dominio on ID18.TIPO_INFORME=dominio.name	
						) as ID19		
					left join 
						(select label as TEMA,name from `dominio` where `list name`='tema'
						)as dominio on ID19.TEMA=dominio.name	
				)as ID20
			left join 
			(select label as LATITUD_sector,name from `dominio` where `list name`='latitud'
			)as dominio on ID20.LATITUD_sector=dominio.name	
		)as ID21
	left join 
		(select label as LATITUD_segurid,name from `dominio` where `list name`='latitud'
		)as dominio on ID21.LATITUD_segurid=dominio.name		
)as ID22
	left join 
		(select label as NOM_PE,name from `dominio` where `list name`='punto'
		)as dominio on ID22.NOM_PE=dominio.name	)as final ORDER by `F_Sincron` asc
		");
	$registros = mysql_num_rows ($sql);

 
	if ($registros > 0) {
		require_once 'PhpExcel/Classes/PHPExcel.php';//libreria PhpExcel que exporta una consulta Sql de una base de dates a un archivo excel
		$objPHPExcel = new PHPExcel();
		
		//Informacion del excel
		$objPHPExcel->
		getProperties()//propiedades del excel
			->setCreator("ingenieroweb.com.co")
			->setLastModifiedBy("ingenieroweb.com.co")
			->setTitle("Exportar excel desde mysql")
			->setSubject("Datos_Informe_diario")
			->setDescription("Documento generado con PHPExcel")
			->setKeywords("ingenieroweb.com.co  con  phpexcel")
			->setCategory("ciudades");    

		
		$Encabezado = 1;  //variable que contiene la posicion de la fila para el encabezado
		//Parque que crea el encabezado de la tabla segun los datos del Sql

		$columna = 'A';//variable que contiene la posicion de la colunmapara el encabezado
		for ($i = 0; $i < mysql_num_fields($sql); $i++)  //ciclo que se realiza para todos los atributos del encabezado que genera el sql. mysql_num_fields : Obtiene el número de campos de un resultado
		{
			$objPHPExcel->getActiveSheet()->setCellValue($columna.$Encabezado, mysql_field_name($sql,$i)); //propiedad que rellena una celda (setCellValue) para el documento excel creado (objPHPExcel) en la hoja activa (getActiveSheet):  setCellValue(columna.Fila, Texto)  mysql_field_name:Obtiene el nombre del campo especificado de un resultado
			$columna++;// aumenta la columna
		}
		//Finaliza la creación del encabezado
		
		//Empieza la adicion de los datos 
		$dato = 2;   //variable que contiene la posicion de la fila para los datos
		while($row = mysql_fetch_row($sql))  //ciclo que se repite hasta el numero de colunmas en la consulta.  mysql_fetch_row : Obtiene una fila de resultados como un array numérico
		{  
			$columna = 'A';//variable que contiene la posicion de la colunma para los datos
			for($j=0; $j<mysql_num_fields($sql);$j++)  //ciclo que se realiza para todos los atributos que genera el sql. 
			{  
				if(!isset($row[$j]))  //valida si la celda o campo del Sql está definido y no es NULL y lo convierte en Null
					$value = NULL;  
				elseif ($row[$j] != "")  
					$value = strip_tags($row[$j]);  //valida si la celda o campo del Sql no tiene valor y devuelve un string con todos los bytes NULL y las etiquetas HTML y PHP retirados de un str dado.
				else  
					$value = "";  

				$objPHPExcel->getActiveSheet()->setCellValue($columna.$dato, $value);//rellena la celda segun el dato de la consulta sql
				$columna++;
			}  
			$dato++;
		} 
	}
	header('Content-Type: application/vnd.ms-excel');//tipo de aplicación: excel
	header('Content-Disposition: attachment;filename="Datos_Informe_diario.xls"');//nombre del arrchivo de salida
	header('Cache-Control: max-age=0');
	 
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//crea el excel definitivo
	$objWriter->save('php://output');//expoporta o descarga el excel 
	exit;
	mysql_close($db);	
	
		
?> 	


