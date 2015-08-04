<?php

	$formulario=$_GET['formulario'];
	valores($formulario);

function valores ($formulario) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	
	if($formulario=="Informe_Diario"){
	$sql ="SELECT MAX(`_SUBMISSION_DATE`) as f_final,MIN(`_SUBMISSION_DATE`) as f_inicial  FROM informe_apoyo_zonal_diario_v2_f2_2014_core";
	
	$result = mysql_query($sql);
		
	while($row=mysql_fetch_array($result))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$f_final=$row['f_final'];
				$f_inicial=$row['f_inicial'];
			}
	$a = array();
		array_push($a,$f_final);
		array_push($a,$f_inicial);
		print json_encode($a, JSON_NUMERIC_CHECK);
	}
	elseif($formulario=="Informe_Area_Vivac"){
	$sql ="SELECT MAX(`_SUBMISSION_DATE`) as f_final,MIN(`_SUBMISSION_DATE`) as f_inicial  FROM control_areas_vivac_v2_f2_2014_core";
	
	$result = mysql_query($sql);
		
	while($row=mysql_fetch_array($result))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$f_final=$row['f_final'];
				$f_inicial=$row['f_inicial'];
			}
	$a = array();
		array_push($a,$f_final);
		array_push($a,$f_inicial);
		print json_encode($a, JSON_NUMERIC_CHECK);
	}
	elseif($formulario=="Informe_Inventario"){
	$sql ="SELECT CONVERT_TZ (MAX(`_SUBMISSION_DATE`),'+00:00','-05:00') as f_final,MIN(`_SUBMISSION_DATE`) as f_inicial  FROM formulario_inventario_v2_f2_2014_core";
	
	$result = mysql_query($sql);
		
	while($row=mysql_fetch_array($result))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$f_final=$row['f_final'];
				$f_inicial=$row['f_inicial'];
			}
	$a = array();
		array_push($a,$f_final);
		array_push($a,$f_inicial);
		print json_encode($a, JSON_NUMERIC_CHECK);
	}
	elseif($formulario=="Enlace_Novedad"){
	$sql ="SELECT CONVERT_TZ (MAX(`_SUBMISSION_DATE`),'+00:00','-05:00') as f_final,MIN(`_SUBMISSION_DATE`) as f_inicial  FROM informe_de_enlace_y_novedad_v2_f2_2014_core";
	
	$result = mysql_query($sql);
		
	while($row=mysql_fetch_array($result))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$f_final=$row['f_final'];
				$f_inicial=$row['f_inicial'];
			}
	$a = array();
		array_push($a,$f_final);
		array_push($a,$f_inicial);
		print json_encode($a, JSON_NUMERIC_CHECK);
	}
	elseif($formulario=="Reporte_Accidentes"){
	$sql ="SELECT MAX(`_SUBMISSION_DATE`) as f_final,MIN(`_SUBMISSION_DATE`) as f_inicial  FROM formulario_accidentes_trabajo_v_prueba_core";
	
	$result = mysql_query($sql);
		
	while($row=mysql_fetch_array($result))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$f_final=$row['f_final'];
				$f_inicial=$row['f_inicial'];
			}
	$a = array();
		array_push($a,$f_final);
		array_push($a,$f_inicial);
		print json_encode($a, JSON_NUMERIC_CHECK);
	}
	mysql_close($db);	
	
}		
?> 	


