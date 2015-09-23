
<?php 	
$ano =$_GET['ano'];
$fase =$_GET['fase'];
$punto =$_GET['punto'];
$profesional =$_GET['profesional'];


valores($ano,$fase,$punto,$profesional);
function valores ($ano,$fase,$punto,$profesional) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	if($ano==99){
	$años="";
	$fases="";
	$puntos="";
	$profesionales="";
	}elseif($fase==99 && $punto==99 && $profesional==99 && $ano != 99){
	$años=" and SUBSTR(`FECHA_REPORT`,1,4)=".$ano;
	$fases="";
	$puntos="";
	$profesionales="";
	}elseif($fase!==99 && $punto==99 && $profesional==99 && $ano != 99){
	$años=" and SUBSTR(`FECHA_REPORT`,1,4)=".$ano;
	$fases=" and `FASE`='".$fase."'";
	$puntos="";
	$profesionales="";
	}elseif($fase!==99 && $punto!=99 && $profesional==99 && $ano != 99){
	$años=" and SUBSTR(`FECHA_REPORT`,1,4)=".$ano;
	$fases=" and `FASE`='".$fase."'";
	$puntos=" and (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )='".$punto."'";
	$profesionales="";
	}elseif($fase!==99 && $punto==99 && $profesional!=99 && $ano != 99){
	$años=" and SUBSTR(`FECHA_REPORT`,1,4)=".$ano;
	$fases=" and `FASE`='".$fase."'";
	$puntos=" ";
	$profesionales="  and `NOM_PGE`='".$profesional."'";
	}elseif($fase!==99 && $punto!=99 && $profesional!=99 && $ano != 99){
	$años=" and SUBSTR(`FECHA_REPORT`,1,4)=".$ano;
	$fases=" and `FASE`='".$fase."'";
	$puntos=" and (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )='".$punto."'";
	$profesionales="  and `NOM_PGE`='".$profesional."'";
	
	}
		
		
		

			//Esta priemra parte se realiza para los dias en que hubo erradicación T_erradi>0
	
	//sql que trae una consulta con el total de situaciones reportadas por cada tipo para el grupo administrativo GME

	$adm_gme=mysql_query("SELECT (sum(1_Abastecimiento) + sum(1_Acompanamiento_firma_GME) + sum(1_Apoyo_zonal_sin_punto_asignado) + sum(1_Descanso_en_dia_habil) + sum(1_Descanso_festivo_dominical) + sum(1_Dia_compensatorio) + sum(1_Erradicacion_en_dia_festivo) + sum(1_Espera_helicoptero_Helistar) + sum(1_Extraccion) + sum(1_Firma_contrato_GME) + sum(1_Induccion_Apoyo_Zonal) + sum(1_Insercion) + sum(1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) + sum(1_Novedad_apoyo_zonal) + sum(1_Novedad_enfermero) + sum(1_Punto_fuera_del_area_de_erradicacion) + sum(1_Transporte_bus) + sum(1_Traslado_apoyo_zonal) + sum(1_Traslado_area_vivac)) as Adm_GME,sum(1_Abastecimiento) as Abastecimiento,sum(1_Acompanamiento_firma_GME) as Acompanamiento_firma_GME,sum(1_Apoyo_zonal_sin_punto_asignado) as Apoyo_zonal_sin_punto_asignado,sum(1_Descanso_en_dia_habil) as Descanso_en_dia_habil,sum(1_Descanso_festivo_dominical) as Descanso_festivo_dominical,sum(1_Dia_compensatorio) as Dia_compensatorio,sum(1_Erradicacion_en_dia_festivo) as Erradicacion_en_dia_festivo,sum(1_Espera_helicoptero_Helistar) as Espera_helicoptero_Helistar,sum(1_Extraccion) as Extraccion,sum(1_Firma_contrato_GME) as Firma_contrato_GME,sum(1_Induccion_Apoyo_Zonal) as Induccion_Apoyo_Zonal,sum(1_Insercion) as Insercion,sum(1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) as Llegada_GME_a_su_lugar_de_Origen_fin_fase,sum(1_Novedad_apoyo_zonal) as Novedad_apoyo_zonal,sum(1_Novedad_enfermero) as Novedad_enfermero,sum(1_Punto_fuera_del_area_de_erradicacion) as Punto_fuera_del_area_de_erradicacion,sum(1_Transporte_bus) as Transporte_bus,sum(1_Traslado_apoyo_zonal) as Traslado_apoyo_zonal,sum(1_Traslado_area_vivac) as Traslado_area_vivac FROM `info_diario` WHERE `Adm_GME`=1 and T_erradi>0".$años.$fases.$puntos.$profesionales );
	//creacion de un arreglo "adm_gme_array" segun la estructura para la grafica
	$adm_gme_array= array();
		while ($fila1 = mysql_fetch_array($adm_gme, MYSQL_ASSOC)) {
			$adm_gme_array['y']=$fila1['Adm_GME'];// y: es el total de situaciones adm_gme
			$adm_gme_array['color']='#E2A9F3';// color de esta clase	
			$adm_gme_array['drilldown']['name']='Adm_GME';	//nombre de la serie = Adm_GME
			$adm_gme_array['drilldown']['categories']=['Abastecimiento','Acompanamiento firma GME','Apoyo zonal sin punto asignado','Descanso en dia habil','Descanso festivo dominical','Dia compensatorio','Erradicacion en dia festivo','Espera helicoptero Helistar','Extraccion','Firma contrato GME','Induccion Apoyo Zonal','Insercion','Llegada GME a su lugar de Origen fin fase','Novedad apoyo zonal','Novedad enfermero','Punto fuera del area de erradicacion','Transporte bus','Traslado apoyo zonal','Traslado area vivac'];//posibles categorias que puede tener esta clase adm_gme, estos datos se insertna manual segun el formulario
			//los siguientes codigos ingresan el total de situaciones para cada categoria segun BD
			$adm_gme_array['drilldown']['data'][]=$fila1['Abastecimiento'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Acompanamiento_firma_GME'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Apoyo_zonal_sin_punto_asignado'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Descanso_en_dia_habil'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Descanso_festivo_dominical'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Dia_compensatorio'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Erradicacion_en_dia_festivo'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Espera_helicoptero_Helistar'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Extraccion'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Firma_contrato_GME'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Induccion_Apoyo_Zonal'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Insercion'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Llegada_GME_a_su_lugar_de_Origen_fin_fase'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Novedad_apoyo_zonal'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Novedad_enfermero'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Punto_fuera_del_area_de_erradicacion'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Transporte_bus'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Traslado_apoyo_zonal'];
			$adm_gme_array['drilldown']['data'][]=$fila1['Traslado_area_vivac'];
			$adm_gme_array['drilldown']['color']='#E2A9F3';	

		}
		//sql que trae una consulta con el total de situaciones reportadas por cada tipo para el grupo administrativo Fuerza publica
		$Adm_Fuerza=mysql_query("SELECT (sum(2_A_la_espera_definicion_nuevo_punto_FP) + sum(2_Espera_helicoptero_FP_de_seguridad) + sum(2_Espera_helicoptero_FP_que_abastece) + sum(2_Induccion_FP) + sum(2_Novedad_canino_o_del_grupo_de_deteccion) + sum(2_Problemas_fuerza_publica) + sum(2_Sin_seguridad)) as Adm_Fuerza,sum(2_A_la_espera_definicion_nuevo_punto_FP) as A_la_espera_definicion_nuevo_punto_FP,sum(2_Espera_helicoptero_FP_de_seguridad) as Espera_helicoptero_FP_de_seguridad,sum(2_Espera_helicoptero_FP_que_abastece) as Espera_helicoptero_FP_que_abastece,sum(2_Induccion_FP) as Induccion_FP,sum(2_Novedad_canino_o_del_grupo_de_deteccion) as Novedad_canino_o_del_grupo_de_deteccion,sum(2_Problemas_fuerza_publica) as Problemas_fuerza_publica,sum(2_Sin_seguridad) as Sin_seguridad FROM `info_diario` WHERE Adm_Fuerza=1  and T_erradi>0".$años.$fases.$puntos.$profesionales);
	//creacion de un arreglo "Adm_Fuerza_array" segun la estructura para la grafica
	$Adm_Fuerza_array= array();
		while ($fila1 = mysql_fetch_array($Adm_Fuerza, MYSQL_ASSOC)) {
			$Adm_Fuerza_array['y']=$fila1['Adm_Fuerza'];// y: es el total de situaciones Adm_Fuerza
			$Adm_Fuerza_array['color']='#A9F5D0';	// color de esta clase
			$Adm_Fuerza_array['drilldown']['name']='Adm_Fuerza';//nombre de la serie = Adm_Fuerza	
			$Adm_Fuerza_array['drilldown']['categories']=['A la espera definicion nuevo punto FP','Espera helicoptero FP de seguridad','Espera helicoptero FP que abastece','Induccion FP','Novedad canino o del grupo de deteccion','Problemas fuerza publica','Sin seguridad'];//posibles categorias que puede tener esta clase Adm_Fuerza, estos datos se insertna manual segun el formulario
			//los siguientes codigos ingresan el total de situaciones para cada categoria segun BD
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['A_la_espera_definicion_nuevo_punto_FP'];
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['Espera_helicoptero_FP_de_seguridad'];
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['Espera_helicoptero_FP_que_abastece'];
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['Induccion_FP'];
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['Novedad_canino_o_del_grupo_de_deteccion'];
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['Problemas_fuerza_publica'];
			$Adm_Fuerza_array['drilldown']['data'][]=$fila1['Sin_seguridad'];
			$Adm_Fuerza_array['drilldown']['color']='#A9F5D0';	

		}
	//sql que trae una consulta con el total de situaciones reportadas por cada tipo para el grupo de situaciones de seguridad	
	$Sit_Seguridad=mysql_query("SELECT (sum(3_AEI_controlado) + sum(3_AEI_no_controlado) + sum(3_Bloqueo_parcial_de_la_comunidad) + sum(3_Bloqueo_total_de_la_comunidad) + sum(3_Combate) + sum(3_Hostigamiento) + sum(3_MAP_Controlada) + sum(3_MAP_No_controlada) + sum(3_Operaciones_de_seguridad)) as Sit_Seguridad,sum(3_AEI_controlado) as AEI_controlado,sum(3_AEI_no_controlado) as AEI_no_controlado,sum(3_Bloqueo_parcial_de_la_comunidad) as Bloqueo_parcial_de_la_comunidad,sum(3_Bloqueo_total_de_la_comunidad) as Bloqueo_total_de_la_comunidad,sum(3_Combate) as Combate,sum(3_Hostigamiento) as Hostigamiento,sum(3_MAP_Controlada) as MAP_Controlada,sum(3_MAP_No_controlada) as MAP_No_controlada,sum(3_Operaciones_de_seguridad) as Operaciones_de_seguridad FROM `info_diario` WHERE Sit_Seguridad=1 and T_erradi>0".$años.$fases.$puntos.$profesionales);
	//creacion de un arreglo "Sit_Seguridad_array" segun la estructura para la grafica
	$Sit_Seguridad_array= array();
		while ($fila1 = mysql_fetch_array($Sit_Seguridad, MYSQL_ASSOC)) {
			$Sit_Seguridad_array['y']=$fila1['Sit_Seguridad'];// y: es el total de situaciones Sit_Seguridad
			$Sit_Seguridad_array['color']='#F7FE2E';// color de esta clase	
			$Sit_Seguridad_array['drilldown']['name']='Situación_seguridad';//nombre de la serie = Situación_seguridad		
			$Sit_Seguridad_array['drilldown']['categories']=['AEI controlado','AEI no controlado','Bloqueo parcial de la comunidad','Bloqueo total de la comunidad','Combate','Hostigamiento','MAP Controlada','MAP No controlada','Operaciones de seguridad'];//posibles categorias que puede tener esta clase Sit_Seguridad, estos datos se insertna manual segun el formulario
			//los siguientes codigos ingresan el total de situaciones para cada categoria segun BD
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['AEI_controlado'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['AEI_no_controlado'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['Bloqueo_parcial_de_la_comunidad'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['Bloqueo_total_de_la_comunidad'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['Combate'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['Hostigamiento'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['MAP_Controlada'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['MAP_No_controlada'];
			$Sit_Seguridad_array['drilldown']['data'][]=$fila1['Operaciones_de_seguridad'];
			$Sit_Seguridad_array['drilldown']['color']='#F7FE2E';	

		}
		//sql que trae una consulta con el total de situaciones reportadas por cada tipo para el grupo de Novedades
		$Novedad=mysql_query("SELECT (sum(4_Epidemia) + sum(4_Novedad_climatologica) + sum(4_Registro_de_cultivos) + sum(4_Zona_con_cultivos_muy_dispersos) + sum(4_Zona_de_cruce_de_rios_caudalosos) + sum(4_Zona_sin_cultivos)) as Novedad,sum(4_Epidemia) as Epidemia,sum(4_Novedad_climatologica) as Novedad_climatologica,sum(4_Registro_de_cultivos) as Registro_de_cultivos,sum(4_Zona_con_cultivos_muy_dispersos) as Zona_con_cultivos_muy_dispersos,sum(4_Zona_de_cruce_de_rios_caudalosos) as Zona_de_cruce_de_rios_caudalosos,sum(4_Zona_sin_cultivos) as Zona_sin_cultivos FROM `info_diario` WHERE Novedad=1 and T_erradi>0".$años.$fases.$puntos.$profesionales);
	//creacion de un arreglo "Novedad_array" segun la estructura para la grafica
	$Novedad_array= array();
		while ($fila1 = mysql_fetch_array($Novedad, MYSQL_ASSOC)) {
			$Novedad_array['y']=$fila1['Novedad'];// y: es el total de situaciones Novedad
			$Novedad_array['color']='#BDBDBD';	// color de esta clase	
			$Novedad_array['drilldown']['name']='Novedad';	//nombre de la serie = Novedad		
			$Novedad_array['drilldown']['categories']=['Epidemia','Novedad climatologica','Registro de cultivos','Zona con cultivos muy dispersos','Zona de cruce de rios caudalosos','Zona sin cultivos'];//posibles categorias que puede tener esta clase Novedad, estos datos se insertna manual segun el formulario
			//los siguientes codigos ingresan el total de situaciones para cada categoria segun BD
			$Novedad_array['drilldown']['data'][]=$fila1['Epidemia'];
			$Novedad_array['drilldown']['data'][]=$fila1['Novedad_climatologica'];
			$Novedad_array['drilldown']['data'][]=$fila1['Registro_de_cultivos'];
			$Novedad_array['drilldown']['data'][]=$fila1['Zona_con_cultivos_muy_dispersos'];
			$Novedad_array['drilldown']['data'][]=$fila1['Zona_de_cruce_de_rios_caudalosos'];
			$Novedad_array['drilldown']['data'][]=$fila1['Zona_sin_cultivos'];
			$Novedad_array['drilldown']['color']='#BDBDBD';	

		}
		$con_erradicacion=array();

		
		array_push($con_erradicacion,$adm_gme_array,$Adm_Fuerza_array,$Sit_Seguridad_array,$Novedad_array);//une los vectores de cada grupo de situaciones en uno solo "con_erradicacion"
		
		//Esta segunda posee los mismos codigos que la primera solo que se realzia para los dias en que no hubo erradicación T_erradi=0

		$adm_gme2=mysql_query("SELECT (sum(1_Abastecimiento) + sum(1_Acompanamiento_firma_GME) + sum(1_Apoyo_zonal_sin_punto_asignado) + sum(1_Descanso_en_dia_habil) + sum(1_Descanso_festivo_dominical) + sum(1_Dia_compensatorio) + sum(1_Erradicacion_en_dia_festivo) + sum(1_Espera_helicoptero_Helistar) + sum(1_Extraccion) + sum(1_Firma_contrato_GME) + sum(1_Induccion_Apoyo_Zonal) + sum(1_Insercion) + sum(1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) + sum(1_Novedad_apoyo_zonal) + sum(1_Novedad_enfermero) + sum(1_Punto_fuera_del_area_de_erradicacion) + sum(1_Transporte_bus) + sum(1_Traslado_apoyo_zonal) + sum(1_Traslado_area_vivac)) as Adm_GME,sum(1_Abastecimiento) as Abastecimiento,sum(1_Acompanamiento_firma_GME) as Acompanamiento_firma_GME,sum(1_Apoyo_zonal_sin_punto_asignado) as Apoyo_zonal_sin_punto_asignado,sum(1_Descanso_en_dia_habil) as Descanso_en_dia_habil,sum(1_Descanso_festivo_dominical) as Descanso_festivo_dominical,sum(1_Dia_compensatorio) as Dia_compensatorio,sum(1_Erradicacion_en_dia_festivo) as Erradicacion_en_dia_festivo,sum(1_Espera_helicoptero_Helistar) as Espera_helicoptero_Helistar,sum(1_Extraccion) as Extraccion,sum(1_Firma_contrato_GME) as Firma_contrato_GME,sum(1_Induccion_Apoyo_Zonal) as Induccion_Apoyo_Zonal,sum(1_Insercion) as Insercion,sum(1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) as Llegada_GME_a_su_lugar_de_Origen_fin_fase,sum(1_Novedad_apoyo_zonal) as Novedad_apoyo_zonal,sum(1_Novedad_enfermero) as Novedad_enfermero,sum(1_Punto_fuera_del_area_de_erradicacion) as Punto_fuera_del_area_de_erradicacion,sum(1_Transporte_bus) as Transporte_bus,sum(1_Traslado_apoyo_zonal) as Traslado_apoyo_zonal,sum(1_Traslado_area_vivac) as Traslado_area_vivac FROM `info_diario` WHERE `Adm_GME`=1 and T_erradi=0".$años.$fases.$puntos.$profesionales);
	
	$adm_gme2_array= array();
		while ($fila1 = mysql_fetch_array($adm_gme2, MYSQL_ASSOC)) {
			$adm_gme2_array['y']=$fila1['Adm_GME'];
			$adm_gme2_array['color']='#E2A9F3';	
			$adm_gme2_array['drilldown']['name']='Adm_GME';	
			$adm_gme2_array['drilldown']['categories']=['Abastecimiento','Acompanamiento firma GME','Apoyo zonal sin punto asignado','Descanso en dia habil','Descanso festivo dominical','Dia compensatorio','Erradicacion en dia festivo','Espera helicoptero Helistar','Extraccion','Firma contrato GME','Induccion Apoyo Zonal','Insercion','Llegada GME a su lugar de Origen fin fase','Novedad apoyo zonal','Novedad enfermero','Punto fuera del area de erradicacion','Transporte bus','Traslado apoyo zonal','Traslado area vivac'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Abastecimiento'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Acompanamiento_firma_GME'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Apoyo_zonal_sin_punto_asignado'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Descanso_en_dia_habil'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Descanso_festivo_dominical'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Dia_compensatorio'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Erradicacion_en_dia_festivo'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Espera_helicoptero_Helistar'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Extraccion'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Firma_contrato_GME'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Induccion_Apoyo_Zonal'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Insercion'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Llegada_GME_a_su_lugar_de_Origen_fin_fase'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Novedad_apoyo_zonal'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Novedad_enfermero'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Punto_fuera_del_area_de_erradicacion'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Transporte_bus'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Traslado_apoyo_zonal'];
			$adm_gme2_array['drilldown']['data'][]=$fila1['Traslado_area_vivac'];
			$adm_gme2_array['drilldown']['color']='#E2A9F3';	

		}
		
		$Adm_Fuerza2=mysql_query("SELECT (sum(2_A_la_espera_definicion_nuevo_punto_FP) + sum(2_Espera_helicoptero_FP_de_seguridad) + sum(2_Espera_helicoptero_FP_que_abastece) + sum(2_Induccion_FP) + sum(2_Novedad_canino_o_del_grupo_de_deteccion) + sum(2_Problemas_fuerza_publica) + sum(2_Sin_seguridad)) as Adm_Fuerza,sum(2_A_la_espera_definicion_nuevo_punto_FP) as A_la_espera_definicion_nuevo_punto_FP,sum(2_Espera_helicoptero_FP_de_seguridad) as Espera_helicoptero_FP_de_seguridad,sum(2_Espera_helicoptero_FP_que_abastece) as Espera_helicoptero_FP_que_abastece,sum(2_Induccion_FP) as Induccion_FP,sum(2_Novedad_canino_o_del_grupo_de_deteccion) as Novedad_canino_o_del_grupo_de_deteccion,sum(2_Problemas_fuerza_publica) as Problemas_fuerza_publica,sum(2_Sin_seguridad) as Sin_seguridad FROM `info_diario` WHERE Adm_Fuerza=1  and T_erradi=0".$años.$fases.$puntos.$profesionales);
	
	$Adm_Fuerza2_array= array();
		while ($fila1 = mysql_fetch_array($Adm_Fuerza2, MYSQL_ASSOC)) {
			$Adm_Fuerza2_array['y']=$fila1['Adm_Fuerza'];
			$Adm_Fuerza2_array['color']='#A9F5D0';	
			$Adm_Fuerza2_array['drilldown']['name']='Adm_Fuerza';	
			$Adm_Fuerza2_array['drilldown']['categories']=['A la espera definicion nuevo punto FP','Espera helicoptero FP de seguridad','Espera helicoptero FP que abastece','Induccion FP','Novedad canino o del grupo de deteccion','Problemas fuerza publica','Sin seguridad'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['A_la_espera_definicion_nuevo_punto_FP'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['Espera_helicoptero_FP_de_seguridad'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['Espera_helicoptero_FP_que_abastece'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['Induccion_FP'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['Novedad_canino_o_del_grupo_de_deteccion'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['Problemas_fuerza_publica'];
			$Adm_Fuerza2_array['drilldown']['data'][]=$fila1['Sin_seguridad'];
			$Adm_Fuerza2_array['drilldown']['color']='#A9F5D0';	

		}
		
		$Sit_Seguridad2=mysql_query("SELECT (sum(3_AEI_controlado) + sum(3_AEI_no_controlado) + sum(3_Bloqueo_parcial_de_la_comunidad) + sum(3_Bloqueo_total_de_la_comunidad) + sum(3_Combate) + sum(3_Hostigamiento) + sum(3_MAP_Controlada) + sum(3_MAP_No_controlada) + sum(3_Operaciones_de_seguridad)) as Sit_Seguridad,sum(3_AEI_controlado) as AEI_controlado,sum(3_AEI_no_controlado) as AEI_no_controlado,sum(3_Bloqueo_parcial_de_la_comunidad) as Bloqueo_parcial_de_la_comunidad,sum(3_Bloqueo_total_de_la_comunidad) as Bloqueo_total_de_la_comunidad,sum(3_Combate) as Combate,sum(3_Hostigamiento) as Hostigamiento,sum(3_MAP_Controlada) as MAP_Controlada,sum(3_MAP_No_controlada) as MAP_No_controlada,sum(3_Operaciones_de_seguridad) as Operaciones_de_seguridad FROM `info_diario` WHERE Sit_Seguridad=1 and T_erradi=0".$años.$fases.$puntos.$profesionales);
	
	$Sit_Seguridad2_array= array();
		while ($fila1 = mysql_fetch_array($Sit_Seguridad2, MYSQL_ASSOC)) {
			$Sit_Seguridad2_array['y']=$fila1['Sit_Seguridad'];
			$Sit_Seguridad2_array['color']='#F7FE2E';	
			$Sit_Seguridad2_array['drilldown']['name']='Situación_seguridad';	
			$Sit_Seguridad2_array['drilldown']['categories']=['AEI controlado','AEI no controlado','Bloqueo parcial de la comunidad','Bloqueo total de la comunidad','Combate','Hostigamiento','MAP Controlada','MAP No controlada','Operaciones de seguridad'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['AEI_controlado'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['AEI_no_controlado'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['Bloqueo_parcial_de_la_comunidad'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['Bloqueo_total_de_la_comunidad'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['Combate'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['Hostigamiento'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['MAP_Controlada'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['MAP_No_controlada'];
			$Sit_Seguridad2_array['drilldown']['data'][]=$fila1['Operaciones_de_seguridad'];
			$Sit_Seguridad2_array['drilldown']['color']='#F7FE2E';	

		}
		
		$Novedad2=mysql_query("SELECT (sum(4_Epidemia) + sum(4_Novedad_climatologica) + sum(4_Registro_de_cultivos) + sum(4_Zona_con_cultivos_muy_dispersos) + sum(4_Zona_de_cruce_de_rios_caudalosos) + sum(4_Zona_sin_cultivos)) as Novedad,sum(4_Epidemia) as Epidemia,sum(4_Novedad_climatologica) as Novedad_climatologica,sum(4_Registro_de_cultivos) as Registro_de_cultivos,sum(4_Zona_con_cultivos_muy_dispersos) as Zona_con_cultivos_muy_dispersos,sum(4_Zona_de_cruce_de_rios_caudalosos) as Zona_de_cruce_de_rios_caudalosos,sum(4_Zona_sin_cultivos) as Zona_sin_cultivos FROM `info_diario` WHERE Novedad=1 and T_erradi=0".$años.$fases.$puntos.$profesionales);
	
	$Novedad2_array= array();
		while ($fila1 = mysql_fetch_array($Novedad2, MYSQL_ASSOC)) {
			$Novedad2_array['y']=$fila1['Novedad'];
			$Novedad2_array['color']='#BDBDBD';	
			$Novedad2_array['drilldown']['name']='Novedad';	
			$Novedad2_array['drilldown']['categories']=['Epidemia','Novedad climatologica','Registro de cultivos','Zona con cultivos muy dispersos','Zona de cruce de rios caudalosos','Zona sin cultivos'];
			$Novedad2_array['drilldown']['data'][]=$fila1['Epidemia'];
			$Novedad2_array['drilldown']['data'][]=$fila1['Novedad_climatologica'];
			$Novedad2_array['drilldown']['data'][]=$fila1['Registro_de_cultivos'];
			$Novedad2_array['drilldown']['data'][]=$fila1['Zona_con_cultivos_muy_dispersos'];
			$Novedad2_array['drilldown']['data'][]=$fila1['Zona_de_cruce_de_rios_caudalosos'];
			$Novedad2_array['drilldown']['data'][]=$fila1['Zona_sin_cultivos'];
			$Novedad2_array['drilldown']['color']='#BDBDBD';	

		}
		
		$sin_erradicacion=array();
		array_push($sin_erradicacion,$adm_gme2_array,$Adm_Fuerza2_array,$Sit_Seguridad2_array,$Novedad2_array);//une los vectores de cada grupo de situaciones en uno solo "sin_erradicacion"
		
		$total_sin_erra=$adm_gme2_array['y']+$Adm_Fuerza2_array['y']+$Sit_Seguridad2_array['y']+$Novedad2_array['y'];// crea una variable con el total de situaciones reportadas para los dias sin erradicacion
		
		$total_con_erra=$adm_gme_array['y']+$Adm_Fuerza_array['y']+$Sit_Seguridad_array['y']+$Novedad_array['y'];// crea una variable con el total de situaciones reportadas para los dias con erradicacion
		
	
		print json_encode(array("a" => $con_erradicacion, "b" => $sin_erradicacion,"c" =>$total_con_erra,"d" =>$total_sin_erra), JSON_NUMERIC_CHECK);
		mysql_close($db);
}
?>
