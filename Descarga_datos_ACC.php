<?php


	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	$sql =mysql_query("select
	llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados,NUM_Afectado,Nom_Afectado,CC_Afectado,Cargo_Afectado, Parte_Cuerpo,ESTADO_AFEC,EVACUADO,DESC_ACC 
		from
			(select
				llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,dominio.CARGO_TESTI2,Afectados	
					from
						(select
							llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, dominio.CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados	
								from
									(select
										llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,dominio.NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados	
											From
												(select
													llave, F_Sincron,USUARIO,Cargo_gme,dominio.NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
														from
															(select
																llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,dominio.NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																	from
																		(select
																			llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace,NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA, dominio.LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																				from
																					(select
																						llave, F_Sincron,USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace, dominio.NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																							from
																							(select
																								llave, F_Sincron,dominio.USUARIO,Cargo_gme,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace, NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,Muncipio,NOM_VDA,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																									from
																										(select
																											llave,USUARIO, F_Sincron,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace, NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,Departamento,dominio.Muncipio,NOM_VDA,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																												from
																													(select
																														llave,USUARIO, F_Sincron,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace, NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,NOM_MPIO,NOM_VDA,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,FP_Armada,FP_Ejercito,FP_Policia,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																															from
																																(select
																																	llave,USUARIO, F_Sincron,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace, NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,NOM_MPIO,NOM_VDA,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2,Afectados
																																		from		
																																			(select
																																				llave,USUARIO, F_Sincron,NOM_PE, Otro_PE,NOM_APOYO,Otro_Nom_Apoyo,Otro_CC_Apoyo,NOM_ENLACE,Otro_Nom_Enlace,Otro_CC_Enlace, NOM_PGE,Otro_Nom_PGE,Otro_CC_PGE,NOM_MPIO,NOM_VDA,LATITUD,GRA_LAT,MIN_LAT,SEG_LAT,GRA_LONG,MIN_LONG, SEG_LONG,FECHA_ACC,HORA_ACC,Hora_ingreso,NOM_COMANDANTE,TESTI1, CC_TESTI1, CARGO_TESTI1,TESTI2, CC_TESTI2,CARGO_TESTI2
																																				from
																																					(SELECT
																																						_URI as llave,USUARIO,_SUBMISSION_DATE as F_Sincron,NOM_PE,NOM_PE_NEW as Otro_PE,NOM_APOYO,NOM_ENLACE, NOM_PGE,NOM_MPIO,NOM_VDA,LATITUD,DATE_FORMAT(FECHA_ACC,'%d/%m/%Y') as FECHA_ACC,DATE_FORMAT(HORA_ACC,'%T') as HORA_ACC,DATE_FORMAT(HORA_INTO,'%T') as Hora_ingreso,NOM_COMANDANTE 
																																							FROM 
																																								formulario_accidentes_trabajo_v2_f2_2014_core
																																					)as ACC
																																				join 
																																					(SELECT 
																																						 _PARENT_AURI as llave1,GRUPO3_NOM_APOYO_NEW as Otro_Nom_Apoyo,GRUPO3_CC_APOYO_NEW as Otro_CC_Apoyo,GRUPO4_NOM_ENLACE_NEW as Otro_Nom_Enlace,GRUPO4_CC_ENLACE_NEW as Otro_CC_Enlace,GRUPO5_NOM_PGE_NEW as Otro_Nom_PGE,GRUPO5_CC_PGE_NEW as Otro_CC_PGE,GRUPO6_GRA_LAT as GRA_LAT,GRUPO6_MIN_LAT as MIN_LAT,GRUPO6_SEG_LAT as SEG_LAT,GRUPO7_GRA_LONG as GRA_LONG,GRUPO7_MIN_LONG AS MIN_LONG, GRUPO7_SEG_LONG AS SEG_LONG,GRUPO8_NOM_TESTIGO1 AS TESTI1,GRUPO8_CC_TESTIGO1 AS CC_TESTI1,GRUPO8_CARGO_TESTIGO1 AS CARGO_TESTI1,GRUPO9_NOM_TESTIGO2 AS TESTI2,GRUPO9_CC_TESTIGO2 AS CC_TESTI2,GRUPO9_CARGO_TESTIGO2 AS CARGO_TESTI2 
																																							FROM 
																																								formulario_accidentes_trabajo_v2_f2_2014_core2
																																					)as ACC1 on ACC1.llave1=ACC.llave
																																			)as ACC2
																																		join
																																			(SELECT 
																																				_TOP_LEVEL_AURI as llave3,NO_AFEC as Afectados 
																																					FROM 
																																						formulario_accidentes_trabajo_v2_f2_2014_core4
																																			)as ACC3 on ACC2.llave=ACC3.llave3
																																)as ACC4
																															left join	
																																(SELECT llave4, sum(CASE WHEN ACC5.seguridad = '1' THEN 1 ELSE 0 END ) as FP_Armada, sum(CASE WHEN ACC5.seguridad = '2' THEN 1 ELSE 0 END ) as FP_Ejercito, sum(CASE WHEN ACC5.seguridad = '3' THEN 1 ELSE 0 END ) as FP_Policia 
																																	FROM 
																																		(SELECT formulario_accidentes_trabajo_v2_f2_2014_core.`_URI` as llave4, VALUE as seguridad 
																																			from 
																																				`formulario_accidentes_trabajo_v2_f2_2014_core` 
																																					join formulario_accidentes_trabajo_v2_f2_2014_seguridad on formulario_accidentes_trabajo_v2_f2_2014_core._URI=formulario_accidentes_trabajo_v2_f2_2014_seguridad._PARENT_AURI 
																																		) as ACC5 GROUP by llave4
																																) as ACC6 on ACC6.llave4=ACC4.llave
																													)as ACC7		
																												left join 
																													(select label as Muncipio, departamento as Departamento,name from `dominio` where `list name`='municipio'
																													) as dominio on ACC7.NOM_MPIO=dominio.name
																										)	as  ACC8
																									left join 
																										(select label as USUARIO,name, departamento as Cargo_gme from `dominio` where `list name`='usuario'
																										)as dominio on ACC8.USUARIO=dominio.name 	
																							)as ACC9			
																						left join 
																							(select label as NOM_PGE,name from `dominio` where `list name`='GE'
																							)as dominio on ACC9.NOM_PGE=dominio.name 	
																					)as ACC10
																				left join 
																					(select label as LATITUD,name from `dominio` where `list name`='latitud'
																					)as dominio on ACC10.LATITUD=dominio.name		
																		)	as ACC11
																	left join 
																		(select label as NOM_APOYO,name from `dominio` where `list name`='apoyo'
																		)as dominio on ACC11.NOM_APOYO=dominio.name 
															)	as ACC12		
														left join 
															(select label as NOM_PE,name from `dominio` where `list name`='punto'
															)as dominio on ACC12.NOM_PE=dominio.name		
												)as 	ACC13		
											left join 
												(select label as NOM_ENLACE,name from `dominio` where `list name`='enlace'
												)as dominio on ACC13.NOM_ENLACE=dominio.name			
									)	as ACC14
								left join 
									(select label as CARGO_TESTI1,name from `dominio` where `list name`='cargo'
									)as dominio on ACC14.CARGO_TESTI1=dominio.name	
						)	as ACC15
					left join 
						(select label as CARGO_TESTI2,name from `dominio` where `list name`='cargo'
						)as dominio on ACC15.CARGO_TESTI2=dominio.name		
			)	as ACC16
		left join
			(select
				llave5, NUM_Afectado,Nom_Afectado,CC_Afectado,Cargo_Afectado, Parte_Cuerpo,ESTADO_AFEC,dominio.EVACUADO,DESC_ACC 
				from
					(select	
						llave5, NUM_Afectado,Nom_Afectado,CC_Afectado,Cargo_Afectado, Parte_Cuerpo,dominio.ESTADO_AFEC,EVACUADO,DESC_ACC 
							from
								(select
									llave5, NUM_Afectado,Nom_Afectado,CC_Afectado,dominio.Cargo_Afectado, Parte_Cuerpo,ESTADO_AFEC,EVACUADO,DESC_ACC 
										from
											(select
												llave5, NUM_Afectado,(CASE WHEN  dominio.Nom_Afectado= 'Otro' THEN Otro_Nom_Afectado ELSE dominio.Nom_Afectado END )as Nom_Afectado,(CASE WHEN  dominio.Nom_Afectado= 'Otro' THEN Otro_CC_Afectado ELSE ACC17.Nom_Afectado END )as CC_Afectado,Cargo_Afectado, Parte_Cuerpo,ESTADO_AFEC,EVACUADO,DESC_ACC 
													from
														(SELECT 
															_TOP_LEVEL_AURI as llave5,_ORDINAL_NUMBER as NUM_Afectado, GRUPO10_NOM_AFEC as Nom_Afectado, GRUPO10_CC_AFEC_OTRO as Otro_CC_Afectado,GRUPO10_NOM_AFEC_OTRO as Otro_Nom_Afectado,GRUPO10_CARGO_AFEC as Cargo_Afectado,CUERPO_AFEC as Parte_Cuerpo,ESTADO_AFEC,EVACUADO,DESC_ACC 
																FROM 
																	formulario_accidentes_trabajo_v2_f2_2014_c2_r1
														)as ACC17
													left join
														(select label as Nom_Afectado,name from `dominio` where `list name`='todos'
														)as dominio on ACC17.Nom_Afectado=dominio.name
											)as ACC18
										left join
											(select label as Cargo_Afectado,name from `dominio` where `list name`='cargo'
											)as dominio on ACC18.Cargo_Afectado=dominio.name
								)as ACC19
							left join
								(select label as ESTADO_AFEC,name from `dominio` where `list name`='estado'
								)as dominio on ACC19.ESTADO_AFEC=dominio.name
					)as ACC20
				left join
					(select label as EVACUADO,name from `dominio` where `list name`='si_no'
					)as dominio on ACC20.EVACUADO=dominio.name
			)AS ACC21 on 	ACC21.llave5=ACC16.llave
					
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
			->setSubject("Datos_Reporte_de_accidentes")
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
	header('Content-Disposition: attachment;filename="Datos_Reporte_de_accidentes.xls"');//nombre del arrchivo de salida
	header('Cache-Control: max-age=0');
	 
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//crea el excel definitivo
	$objWriter->save('php://output');//expoporta o descarga el excel 
	exit;
	mysql_close($db);	
	
		
?> 	


