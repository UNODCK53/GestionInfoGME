<?php



	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	$sql =mysql_query("select viejos_reg,nuevos_reg  from new_accidentes");
	$nuevos_actuales= array();
	while ($fila1 = mysql_fetch_array($sql, MYSQL_ASSOC)) {
					$nuevos_actuales[0]=$fila1['nuevos_reg'];
					$nuevos_actuales[1]=$fila1['viejos_reg'];
				}
				
if ($nuevos_actuales[1]<$nuevos_actuales[0])
	{	
	$registros_nuevos= $nuevos_actuales[0] - $nuevos_actuales[1];

	if ($registros_nuevos==1){
		$to = "GestionGME@consolidacion.gov.co, rafael.vargas@unodc.org";

		$subject = "Alerta: ".$registros_nuevos." registro nuevo de accidentes en GME";
		$msg = "Existe ".$registros_nuevos. " nuevo registro en el formulario de accidentes laborales de GME. Este módulo está en prueba";
		// send email
		mail($to,$subject,$msg);
	}else{
		$to = "GestionGME@consolidacion.gov.co, rafael.vargas@unodc.org";
		$subject = "Alerta: ".$registros_nuevos." registros nuevos de accidentes en GME";
		$msg = "Existen ".$registros_nuevos. " nuevos registros en el formulario de accidentes laborales de GME";
		// send email
		mail($to,$subject,$msg);
	}
	
	
	print json_encode($nuevos_actuales, JSON_NUMERIC_CHECK);
	$sql2 =mysql_query("update new_accidentes set viejos_reg=".$nuevos_actuales[0]);
	}else
	{print json_encode($nuevos_actuales, JSON_NUMERIC_CHECK);
	}
	mysql_close($db);	
	
		
?> 	


