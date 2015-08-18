<?php


	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	$sql =mysql_query("select *from(select
llave,F_Sincron,USUARIO,Cargo_gme,Num_AV,`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo,`NOM_PE`,Otro_PE,Departamento,Muncipio,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,dominio.LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION,AÑO, FASE ,'No' as Modificado
	from

		(select llave,F_Sincron,dominio.USUARIO,Cargo_gme,Num_AV,`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo,`NOM_PE`,Otro_PE,Departamento,Muncipio,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION,SUBSTR(`FECHA_INTO_AV`,9,2) as AÑO,SUBSTRING((CASE  WHEN NOM_PE ='99' THEN Otro_PE ELSE NOM_PE END ),3,2) as FASE  
			from 
				(select llave,F_Sincron,USUARIO,Num_AV,`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo,`NOM_PE`,Otro_PE,Departamento,Muncipio,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,dominio.RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION   
					from
						(select llave,F_Sincron,USUARIO,Num_AV,`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo,`NOM_PE`,Otro_PE,Departamento,Muncipio,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,dominio.Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION  
							from
								(select llave,F_Sincron,USUARIO,Num_AV,dominio.`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo,`NOM_PE`,Otro_PE,Departamento,Muncipio,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION 
									from
										(select llave,F_Sincron,USUARIO,Num_AV,`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo,`NOM_PE`,Otro_PE,Departamento,Muncipio,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION 
											from
												(select llave,F_Sincron,USUARIO,Num_AV,`NOM_APOYO`,Otro_Nom_Apoyo,Otro_CC_Apoyo, `NOM_PE`,Otro_PE,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,Nom_enfer,Otro_Nom_Enfer,Otro_CC_Enfer,Armada,Ejercito,Policia, NOM_UNIDAD,NOM_COMAN,CC_COMAN,TEL_COMAN,RANGO_COMAN,Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,`FECHA_INTO_AV`,DIA,MES,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG ,MIN_LONG,SEG_LONG, OBSERVACION 
													from
														(SELECT `_URI` as llave,_SUBMISSION_DATE as F_Sincron,`USUARIO`,`NO_AV` AS Num_AV, `NOM_APOYO`,GRUPO1_NOM_APOYO_NEW as Otro_Nom_Apoyo,GRUPO1_CC_APOYO_NEW as Otro_CC_Apoyo, `NOM_PE`,NOM_PE_NEW as Otro_PE,`NOM_DPTO`,`NOM_MPIO`,`NOM_VDA`,`NO_E`,`NO_OF`,`NO_SUBOF`,`NO_SOL`,`NO_PATRU`,`NOM_ENFER` as Nom_enfer,GRUPO2_CC_ENFER_NEW as Otro_CC_Enfer,GRUPO2_NOM_ENFER_NEW as Otro_Nom_Enfer,`NOM_UNIDAD`,`GRUPO5_NOM_COMAN` as NOM_COMAN,`GRUPO5_CC_COMAN` as CC_COMAN,`GRUPO5_NO_TEL` AS TEL_COMAN,`GRUPO5_RANGO_COMAN` AS RANGO_COMAN,GRUPO5_RANGO_COMAN_NEW as Otro_rango,`NO_GDETECCION`,`NO_BINOMIO`,DATE_FORMAT(`FECHA_INTO_AV`,'%d/%m/%Y') as FECHA_INTO_AV,DATE_FORMAT(`FECHA_INTO_AV`,'%d') as DIA,DATE_FORMAT(`FECHA_INTO_AV`,'%m') as MES,LATITUD,`GRUPO3_GRA_LAT` AS GRA_LAT,`GRUPO3_MIN_LAT`AS MIN_LAT,`GRUPO3_SEG_LAT`AS SEG_LAT ,`GRUPO4_GRA_LONG`AS GRA_LONG,`GRUPO4_MIN_LONG` AS MIN_LONG,`GRUPO4_SEG_LONG` AS SEG_LONG,`OBSERVACION` 
															FROM `control_areas_vivac_v2_f2_2014_core`
														) as ACV	
													join
														(SELECT llave2, sum(CASE WHEN ACV1.seguridad = '1' THEN 1 ELSE 0 END ) as Armada, sum(CASE WHEN ACV1.seguridad = '2' THEN 1 ELSE 0 END ) as Ejercito, sum(CASE WHEN ACV1.seguridad = '3' THEN 1 ELSE 0 END ) as Policia 
															FROM 
																(SELECT control_areas_vivac_v2_f2_2014_core.`_URI` as llave2, VALUE as seguridad 
																	from 
																		`control_areas_vivac_v2_f2_2014_core` 
																			join control_areas_vivac_v2_f2_2014_seguridad on control_areas_vivac_v2_f2_2014_core._URI=control_areas_vivac_v2_f2_2014_seguridad._PARENT_AURI 
																) as ACV1 GROUP by llave2
														) as AVC2 on AVC2.llave2=ACV.llave
												) as AVC3 
											join 
												(select label as Muncipio, departamento as Departamento,name from `dominio` where `list name`='municipio'
												) as dominio on AVC3.NOM_MPIO=dominio.name 
										) as AVC4
									join 
										(select label as NOM_APOYO,name from `dominio` where `list name`='apoyo'
										)as dominio on AVC4.NOM_APOYO=dominio.name 
								) as AVC5
							join 
								(select label as Nom_enfer,name from `dominio` where `list name`='enfer'
								)as dominio on AVC5.Nom_enfer=dominio.name 
						) as AVC6
					join 
						(select label as RANGO_COMAN,name from `dominio` where `list name`='rango'
						)as dominio on AVC6.RANGO_COMAN=dominio.name 
				) as AVC7
			join 
				(select label as USUARIO,name, departamento as Cargo_gme from `dominio` where `list name`='usuario'
				)as dominio on AVC7.USUARIO=dominio.name 
		)as AVC8
	left join 
		(select label as LATITUD,name from `dominio` where `list name`='latitud'
		)as dominio on AVC8.LATITUD=dominio.name) as final order by F_Sincron asc ");
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
			->setSubject("Datos_Areas_Vivac")
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
	header('Content-Disposition: attachment;filename="Datos_Areas_Vivac.xls"');//nombre del arrchivo de salida
	header('Cache-Control: max-age=0');
	 
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//crea el excel definitivo
	$objWriter->save('php://output');//expoporta o descarga el excel 
	exit;
	mysql_close($db);	
	
		
?> 	


