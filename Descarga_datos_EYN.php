<?php


	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	$sql =mysql_query("select
	*
		from

(select
	llave as ID_Formulario,F_Sincron,USUARIO,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,dominio.PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata, Muncipio,Departamento,F_llegada,Fecha,'No' as Modificado, (case when Num_Evacua=0 then llave else concat(llave,NUM_Novedad) end ) as llave_2
	from
		(select
			llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata, Muncipio,Departamento,dominio.F_llegada,Fecha
			from
				(select
					llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata, Muncipio,Departamento,F_llegada,F_llegada_a as Fecha
					from
						(select
							llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	dominio.NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata,NOM_MPIO,F_llegada,F_llegada_a
							from
								(Select
									llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,dominio.NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata,NOM_MPIO,F_llegada,F_llegada_a
									from
										(select
											llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, dominio.Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata,NOM_MPIO,F_llegada,F_llegada_a
											from
												(select
													llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, dominio.Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata,NOM_MPIO,F_llegada,F_llegada_a
													from
														(select
														llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,dominio.Nom_Per_Evacu,CC_Pre_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata,NOM_MPIO,F_llegada,F_llegada_a
														from
															(select
																llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE,NUM_Novedad,Nom_Per_Evacu, Nom_Otro_Per_Evacu,CC_Otro_Per_Evacu, Cargo_Per_EVA, Motivo_Eva,OBS_EVA,NOM_PE,Otro_Nom_PE,	NOM_CAPATAZ,Otro_Nom_Capata,Otro_CC_Capata,NOM_MPIO,F_llegada,F_llegada_a
																from
																	(select
																		llave,USUARIO,F_Sincron,Cargo_gme,dominio.NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE
																		from
																			(select
																				llave,USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,dominio.TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE
																				from
																					(select
																						llave,dominio.USUARIO,F_Sincron,Cargo_gme,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE
																						from	
																							(select llave,USUARIO,F_Sincron,NOM_GE,Otro_PGE,Otro_CC_PGE,TIPO_INFORME,FECHA_NOVEDAD,DIA,MES,Num_Evacua,PTO_INCOMU,OBS_punt_inco,OBS_ENLACE
																								from	
																								(select *
																									from

																									(SELECT `_URI` as llave,NOM_GE,USUARIO,_SUBMISSION_DATE as F_Sincron
																										FROM 
																											`informe_de_enlace_y_novedad_v2_f2_2014_core`
																									)as EYN
																								left join 	
																									(SELECT `_PARENT_AURI` as llave2,Grupo2_NOM_GE_NEW as Otro_PGE,Grupo2_CC_GE_NEW as Otro_CC_PGE
																										FROM 
																											`informe_de_enlace_y_novedad_v2_f2_2014_core2`
																									)as EYN2 on EYN2.llave2=EYN.llave
																								)as EYN3
																							left join	
																								(SELECT `_TOP_LEVEL_AURI` as llave3,TIPO_INFORME,DATE_FORMAT(FECHA_NOVEDAD,'%Y/%m/%d') as FECHA_NOVEDAD,DATE_FORMAT(FECHA_NOVEDAD,'%d') as DIA,DATE_FORMAT(FECHA_NOVEDAD,'%m') as MES,EXTRACCION as Num_Evacua,PTO_INCOMU,PUNTOS as OBS_punt_inco,OBS_ENLACE
																									FROM
																										informe_de_enlace_y_novedad_v2_f2_2014_core4
																								)as EYN4 on EYN3.llave=EYN4.llave3
																							) as EYN5 
																						left join
																							(select label as USUARIO,name, departamento as Cargo_gme from `dominio` where `list name`='usuario'
																							)as dominio on EYN5.USUARIO=dominio.name 
																					) as EYN6
																				LEFT join 
																					(select label as TIPO_INFORME,name from `dominio` where `list name`='informe'
																					)as dominio on EYN6.TIPO_INFORME=dominio.name 	
																			) as EYN7
																		left join 
																			(select label as NOM_GE,name from `dominio` where `list name`='GE'
																			)as dominio on EYN7.NOM_GE=dominio.name 
																	)as EYN8
																left join 	
																	(SELECT `_PARENT_AURI` as llave4,_ORDINAL_NUMBER as NUM_Novedad,Grupo4_NOM_AFEC	as Nom_Per_Evacu,Grupo4_NOM_AFEC_OTRO as Nom_Otro_Per_Evacu,Grupo4_CC_AFEC_OTRO	as CC_Otro_Per_Evacu, Grupo4_CARGO_AFEC	as Cargo_Per_EVA, Grupo4_MOTIVO	as Motivo_Eva, Grupo4_OBS_NOVEDA as OBS_EVA,NOM_PE,	NOM_PE_NEW as Otro_Nom_PE,	NOM_CAPATAZ,Grupo5_NOM_CAPATAZ_NEW as Otro_Nom_Capata,	Grupo5_CC_CAPATAZ_NEW as Otro_CC_Capata,NOM_MPIO,	F_llegada,	DATE_FORMAT(F_llegada_a,'%d/%m/%Y') as F_llegada_a
																		froM 
																			`informe_de_enlace_y_novedad_v2_f2_2014_c2_r1` 
																	) as EYN9 on EYN8.llave=EYN9.llave4
															)as EYN10
														left join
															(select label as Nom_Per_Evacu,name as CC_Pre_Evacu from `dominio` where `list name`='todos'
															)as dominio on EYN10.Nom_Per_Evacu=dominio.CC_Pre_Evacu 
														)as EYN11
													left join
														(select label as Cargo_Per_EVA,name from `dominio` where `list name`='cargo'
														)as dominio on EYN11.Cargo_Per_EVA=dominio.name 
												)as EYN12
											left join
												(select label as Motivo_Eva,name from `dominio` where `list name`='motivo'
												)as dominio on EYN12.Motivo_Eva=dominio.name 		
										)as EYN13
									left join 
										(select label as NOM_PE,name from `dominio` where `list name`='punto'
										)as dominio on EYN13.NOM_PE=dominio.name			
								)as EYN14
							LEFT join 
								(select label as NOM_CAPATAZ,name from `dominio` where `list name`='capataz'
								) as dominio on EYN14.NOM_CAPATAZ=dominio.name
						)as EYN14	
					left join 
						(select label as Muncipio, departamento as Departamento,name from `dominio` where `list name`='municipio'
						) as dominio on EYN14.NOM_MPIO=dominio.name
				)as EYN15
			left join 
				(select label as F_llegada,name from `dominio` where `list name`='si_no'
				) as dominio on EYN15.F_llegada=dominio.name	
		)as EYN16
	left join 
		(select label as PTO_INCOMU,name from `dominio` where `list name`='si_no'
		) as dominio on EYN16.PTO_INCOMU=dominio.name		
)as final order by F_Sincron
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
			->setSubject("Datos_Enlace_novedad")
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
	header('Content-Disposition: attachment;filename="Datos_enlace_y_Novedad.xls"');//nombre del arrchivo de salida
	header('Cache-Control: max-age=0');
	 
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');//crea el excel definitivo
	$objWriter->save('php://output');//expoporta o descarga el excel 
	exit;
	mysql_close($db);	
	
		
?> 	


