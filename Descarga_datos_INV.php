<?php


	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	$sql =mysql_query("select*from(select
llave,F_Sincron,`USUARIO`,Cargo_gme,FECHA_INV,DIA,MES,dominio.TIPO_INV,`NOM_PE`,Otro_PE,NOM_CAPATAZ,Otro_NOM_CAPAT,Otro_CC_CAPAT,NOM_LUGAR,Cocina,1_Abrelatas,1_Balde,1_Arrocero_50,1_Arrocero_44,1_Chocolatera,1_Colador,1_Cucharon_sopa,1_Cucharon_arroz,1_Cuchillo,1_Embudo,1_Espumera,1_Estufa,1_Cuchara_sopa,1_Recipiente,1_Kit_Repue_estufa,1_Molinillo,1_Olla_36,1_Olla_40,1_Paila_32,1_Paila_36_37,Camping,2_Aislante,2_Carpa_hamaca,2_Carpa_rancho,2_Fibra_rollo,2_CAL,2_Linterna,2_Botiquin,2_Mascara_filtro,2_Pimpina,2_Sleeping ,2_Plastico_negro,2_Tula_tropa,2_Camilla,Herramientas,3_Abrazadera,3_Aspersora,3_Cabo_hacha,3_Funda_machete,3_Glifosato_4lt,3_Hacha,3_Lima_12_uni,3_Llave_mixta ,3_Machete,3_Gafa_traslucida,3_Motosierra,3_Palin,3_Tubo_galvanizado,OBSERVACION,AÑO,FASE,'No' as Modificado
	from
		(select
		llave,F_Sincron,dominio.`USUARIO`,dominio.Cargo_gme,FECHA_INV,TIPO_INV,`NOM_PE`,Otro_PE,NOM_CAPATAZ,Otro_NOM_CAPAT,Otro_CC_CAPAT,NOM_LUGAR,Cocina,1_Abrelatas,1_Balde,1_Arrocero_50,1_Arrocero_44,1_Chocolatera,1_Colador,1_Cucharon_sopa,1_Cucharon_arroz,1_Cuchillo,1_Embudo,1_Espumera,1_Estufa,1_Cuchara_sopa,1_Recipiente,1_Kit_Repue_estufa,1_Molinillo,1_Olla_36,1_Olla_40,1_Paila_32,1_Paila_36_37,Camping,2_Aislante,2_Carpa_hamaca,2_Carpa_rancho,2_Fibra_rollo,2_CAL,2_Linterna,2_Botiquin,2_Mascara_filtro,2_Pimpina,2_Sleeping ,2_Plastico_negro,2_Tula_tropa,2_Camilla,Herramientas,3_Abrazadera,3_Aspersora,3_Cabo_hacha,3_Funda_machete,3_Glifosato_4lt,3_Hacha,3_Lima_12_uni,3_Llave_mixta ,3_Machete,3_Gafa_traslucida,3_Motosierra,3_Palin,3_Tubo_galvanizado,OBSERVACION,DIA,MES ,SUBSTR(`FECHA_INV`,9,2) as AÑO,SUBSTRING((CASE  WHEN NOM_PE ='99' THEN Otro_PE ELSE NOM_PE END ),3,2) as FASE 
			from

				(select
				llave,F_Sincron,`USUARIO`,FECHA_INV,TIPO_INV,`NOM_PE`,Otro_PE,dominio.NOM_CAPATAZ,Otro_NOM_CAPAT,Otro_CC_CAPAT,NOM_LUGAR,Cocina,1_Abrelatas,1_Balde,1_Arrocero_50,1_Arrocero_44,1_Chocolatera,1_Colador,1_Cucharon_sopa,1_Cucharon_arroz,1_Cuchillo,1_Embudo,1_Espumera,1_Estufa,1_Cuchara_sopa,1_Recipiente,1_Kit_Repue_estufa,1_Molinillo,1_Olla_36,1_Olla_40,1_Paila_32,1_Paila_36_37,Camping,2_Aislante,2_Carpa_hamaca,2_Carpa_rancho,2_Fibra_rollo,2_CAL,2_Linterna,2_Botiquin,2_Mascara_filtro,2_Pimpina,2_Sleeping ,2_Plastico_negro,2_Tula_tropa,2_Camilla,Herramientas,3_Abrazadera,3_Aspersora,3_Cabo_hacha,3_Funda_machete,3_Glifosato_4lt,3_Hacha,3_Lima_12_uni,3_Llave_mixta ,3_Machete,3_Gafa_traslucida,3_Motosierra,3_Palin,3_Tubo_galvanizado,OBSERVACION,DIA,MES
					from
						(select
						llave,`USUARIO`,F_Sincron,FECHA_INV,TIPO_INV,`NOM_PE`,Otro_PE,NOM_CAPATAZ,Otro_NOM_CAPAT,Otro_CC_CAPAT,NOM_LUGAR,Cocina,1_Abrelatas,1_Balde,1_Arrocero_50,1_Arrocero_44,1_Chocolatera,1_Colador,1_Cucharon_sopa,1_Cucharon_arroz,1_Cuchillo,1_Embudo,1_Espumera,1_Estufa,1_Cuchara_sopa,1_Recipiente,1_Kit_Repue_estufa,1_Molinillo,1_Olla_36,1_Olla_40,1_Paila_32,1_Paila_36_37,Camping,2_Aislante,2_Carpa_hamaca,2_Carpa_rancho,2_Fibra_rollo,2_CAL,2_Linterna,2_Botiquin,2_Mascara_filtro,2_Pimpina,2_Sleeping ,2_Plastico_negro,2_Tula_tropa,2_Camilla,Herramientas,3_Abrazadera,3_Aspersora,3_Cabo_hacha,3_Funda_machete,3_Glifosato_4lt,3_Hacha,3_Lima_12_uni,3_Llave_mixta ,3_Machete,3_Gafa_traslucida,3_Motosierra,3_Palin,3_Tubo_galvanizado,OBSERVACION,DIA,MES
							from
								(SELECT `_URI` as llave,_SUBMISSION_DATE as F_Sincron,`USUARIO`,DATE_FORMAT(FECHA_INV,'%d/%m/%Y') as FECHA_INV,TIPO_INV,`NOM_PE`,NOM_PE_NEW as Otro_PE,NOM_CAPATAZ,Grupo3_NOM_CAPATAZ_NEW as Otro_NOM_CAPAT,Grupo3_CC_CAPATAZ_NEW as Otro_CC_CAPAT,NOM_LUGAR,Grupo4_CANTIDAD_INV_LATA as 1_Abrelatas,Grupo4_CANTIDAD_INV_BALDE as 1_Balde,Grupo4_CANTIDAD_INV_CAL50 as 1_Arrocero_50 ,Grupo4_CANTIDAD_INV_CAL44 as 1_Arrocero_44,GRUPO4_CANTIDAD_INV_3_LTS as 1_Chocolatera,Grupo4_CANTIDAD_INV_C28_CM as 1_Colador,Grupo4_CANTIDAD_INV_C14_CM as 1_Cucharon_sopa,Grupo4_CANTIDAD_INV_C12_CM as 1_Cucharon_arroz,Grupo4_CANTIDAD_INV_CAI as 1_Cuchillo,Grupo4_CANTIDAD_INV_E12_CM as 1_Embudo,Grupo4_CANTIDAD_INV_E16_CM as 1_Espumera,Grupo4_CANTIDAD_INV_EG1 as 1_Estufa,Grupo4_CANTIDAD_INV_CS as 1_Cuchara_sopa,Grupo4_CANTIDAD_INV_PC as 1_Recipiente,Grupo4_CANTIDAD_INV_KRE as 1_Kit_Repue_estufa,Grupo4_CANTIDAD_INV_MOL as 1_Molinillo,Grupo4_CANTIDAD_INV_O36 as 1_Olla_36,Grupo4_CANTIDAD_INV_O40 as 1_Olla_40,Grupo4_CANTIDAD_INV_P32 as 1_Paila_32,Grupo4_CANTIDAD_INV_P36 as 1_Paila_36_37,Grupo5_CANTIDAD_INV_AT as 2_Aislante,Grupo5_CANTIDAD_INV_CH as 2_Carpa_hamaca,Grupo5_CANTIDAD_INV_CR as 2_Carpa_rancho,Grupo5_CANTIDAD_INV_P428_M as 2_Fibra_rollo,Grupo5_CANTIDAD_INV_CAL as 2_CAL,Grupo5_CANTIDAD_INV_L3_LED as 2_Linterna,Grupo5_CANTIDAD_INV_BOTIQUIN as 2_Botiquin,Grupo5_CANTIDAD_INV_MASCARA as 2_Mascara_filtro,Grupo5_CANTIDAD_INV_P3_LTS as 2_Pimpina,Grupo5_CANTIDAD_INV_SLEEP as 2_Sleeping ,GRUPO5_CANTIDAD_INV_P8_X8_M as 2_Plastico_negro,Grupo5_CANTIDAD_INV_TULA as 2_Tula_tropa,Grupo5_CANTIDAD_INV_CAMILLA as 2_Camilla,Grupo6_CANTIDAD_INV_AISI304 as 3_Abrazadera,Grupo6_CANTIDAD_INV_ASP as 3_Aspersora,Grupo6_CANTIDAD_INV_CABOH as 3_Cabo_hacha,Grupo6_CANTIDAD_INV_FM20 as 3_Funda_machete,Grupo6_CANTIDAD_INV_G4_LTS as 3_Glifosato_4lt,Grupo6_CANTIDAD_INV_HACHA as 3_Hacha,Grupo6_CANTIDAD_INV_LIMA14_CM as 3_Lima_12_uni,Grupo6_CANTIDAD_INV_KEY as 3_Llave_mixta,Grupo6_CANTIDAD_INV_M50_CM as 3_Machete,Grupo6_CANTIDAD_INV_GAFA as 3_Gafa_traslucida,Grupo6_CANTIDAD_INV_MOTOSIERRA as 3_Motosierra,Grupo6_CANTIDAD_INV_PALIN16_CM as 3_Palin,GRUPO6_CANTIDAD_INV_T1_M1_P as 3_Tubo_galvanizado,OBSERVACION,DATE_FORMAT(FECHA_INV,'%d') as DIA,DATE_FORMAT(FECHA_INV,'%m') as MES
									FROM 
										`formulario_inventario_v2_f2_2014_core`
								) as INV
							join	
								(SELECT llave2, sum(CASE WHEN INV1.elemento = '1' THEN 1 ELSE 0 END ) as Cocina, sum(CASE WHEN INV1.elemento = '2' THEN 1 ELSE 0 END ) as Camping, sum(CASE WHEN INV1.elemento = '3' THEN 1 ELSE 0 END ) as Herramientas 
									FROM 
										(SELECT formulario_inventario_v2_f2_2014_core.`_URI` as llave2, VALUE as elemento 
											from 
												`formulario_inventario_v2_f2_2014_core` 
													LEFT join formulario_inventario_v2_f2_2014_tipo_element_inv on formulario_inventario_v2_f2_2014_core._URI=formulario_inventario_v2_f2_2014_tipo_element_inv._PARENT_AURI 
										) as INV1 GROUP by llave2
								) as INV2 on INV2.llave2=INV.llave
						)as INV3
					LEFT join 
						(select label as NOM_CAPATAZ,name from `dominio` where `list name`='capataz'
						) as dominio on INV3.NOM_CAPATAZ=dominio.name
				) as INV4
			LEFT join 
				(select label as USUARIO,name, departamento as Cargo_gme from `dominio` where `list name`='usuario'
				)as dominio on INV4.USUARIO=dominio.name 
		)as INV5
	LEFT join 
		(select label as TIPO_INV,name from `dominio` where `list name`='inv'
		)as dominio on INV5.TIPO_INV=dominio.name ) as final order by F_Sincron asc



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
			->setSubject("Datos_Inventario")
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
	header('Content-Disposition: attachment;filename="Datos_Inventario.xls"');//nombre del arrchivo de salida
	header('Cache-Control: max-age=0');
	 
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//crea el excel definitivo
	$objWriter->save('php://output');//expoporta o descarga el excel 
	exit;
	mysql_close($db);	
	
		
?> 	


