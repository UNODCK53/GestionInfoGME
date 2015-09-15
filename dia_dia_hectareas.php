
<?php
$ano =$_GET['ano'];
$fase =$_GET['fase'];
$punto =$_GET['punto'];
valores($ano,$fase,$punto);
function valores ($ano,$fase,$punto) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	//condicionale que complemente las sentencias sql si se escoge un punto de erradicacion especifico
	if ($punto==99){
		$sql_PE="";
	}else
	{
		$sql_PE=" and (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )='".$punto."' ";
	}

	if ($fase!=99 && $ano != 99){
				
				$fechas=mysql_query("select CAST(CONCAT(SUBSTR(`FECHA_REPORT`,1,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fechas FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano.$sql_PE."  and `FASE`='".$fase."' group by fechas ORDER BY `fechas` ASC");
				$PE=mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PEs FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano."  and `FASE`='".$fase."'".$sql_PE."  group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) ORDER BY `NOM_PEs` ASC");
				$ha = mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,CAST(CONCAT(SUBSTR(`FECHA_REPORT`,1,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fecha,sum(T_erradi) as ha FROM gestioninfogme.info_diario  WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano."  and `FASE`='".$fase."'".$sql_PE."  group by NOM_PE,fecha order by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) asc,fecha asc");
				$num_fechas = mysql_query("select count(fechas)as num_fecha from (select CAST(CONCAT(SUBSTR(`FECHA_REPORT`,1,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fechas FROM gestioninfogme.info_diario  WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano."  and `FASE`='".$fase."'".$sql_PE."  group by fechas) as t_fechas");
				$num_fechas_dato= mysql_result($num_fechas, 0);
				$num_PE = mysql_query("select count(NOM_PEs) as num_pe from (select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PEs FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano." and `FASE`='".$fase."'".$sql_PE."  group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as t_Nom_PE");
				$num_PE_dato= mysql_result($num_PE, 0);

				$ha_array= array();
				$ha_PE_array= array();
				$ha_fecha_array= array();
				while ($fila1 = mysql_fetch_array($ha, MYSQL_ASSOC)) {
					$ha_array[]=$fila1['ha'];
					$ha_PE_fecha_array[]=$fila1['NOM_PE'].$fila1['fecha'];
				}

				$result = array_combine($ha_array,$ha_PE_fecha_array);

				$fechas_array= array();
				while ($fila2 = mysql_fetch_array($fechas, MYSQL_ASSOC)) {
					$fechas_array[]=$fila2['fechas'];
				}

				$PE_array= array();
				while ($fila3 = mysql_fetch_array($PE, MYSQL_ASSOC)) {
					$PE_array[]=$fila3['NOM_PEs'];
				}

				$resultado= array();
				$dato="";

				for ($i = 0; $i < $num_fechas_dato; $i++) {
					
					for ($j = 0; $j < $num_PE_dato; $j++) {
						$d=$PE_array[$j].$fechas_array[$i];
						if (in_array($d, $ha_PE_fecha_array)) {
							foreach ( $ha_PE_fecha_array as $posicion => $fecha ) 
							{
								if($d==$fecha){
									$posicion1 = array_search( $fecha, $ha_PE_fecha_array );
									$dato= $dato."[".$i.",".$j.",". $ha_array[$posicion1]."],";
								}
							} 	
						}else
						{
							$dato=$dato."[".$i.",".$j.",0],";
						}
					}
				}
			}else{	
				$fechas=mysql_query("select CAST(CONCAT(SUBSTR(`FECHA_REPORT`,1,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fechas FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano.$sql_PE." group by fechas ORDER BY `fechas` ASC");
				$PE=mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PEs FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano.$sql_PE." group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) ORDER BY `NOM_PEs` ASC");
				$ha = mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,CAST(CONCAT(SUBSTR(`FECHA_REPORT`,1,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fecha,sum(T_erradi) as ha FROM gestioninfogme.info_diario  WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano.$sql_PE." group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ),fecha order by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) asc,fecha asc");
				$num_fechas = mysql_query("select count(fechas)as num_fecha from (select CAST(CONCAT(SUBSTR(`FECHA_REPORT`,1,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fechas FROM gestioninfogme.info_diario  WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano.$sql_PE." group by fechas) as t_fechas");
				$num_fechas_dato= mysql_result($num_fechas, 0);
				$num_PE = mysql_query("select count(NOM_PEs) as num_pe from (select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PEs FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano.$sql_PE." group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as t_Nom_PE");
				$num_PE_dato= mysql_result($num_PE, 0);

				$ha_array= array();
				$ha_PE_array= array();
				$ha_fecha_array= array();
				while ($fila1 = mysql_fetch_array($ha, MYSQL_ASSOC)) {
					$ha_array[]=$fila1['ha'];
					$ha_PE_fecha_array[]=$fila1['NOM_PE'].$fila1['fecha'];
				}

				$result = array_combine($ha_array,$ha_PE_fecha_array);

				$fechas_array= array();
				while ($fila2 = mysql_fetch_array($fechas, MYSQL_ASSOC)) {
					$fechas_array[]=$fila2['fechas'];
				}

				$PE_array= array();
				while ($fila3 = mysql_fetch_array($PE, MYSQL_ASSOC)) {
					$PE_array[]=$fila3['NOM_PEs'];
				}

				$resultado= array();
				$dato="";

				for ($i = 0; $i < $num_fechas_dato; $i++) {
					
					for ($j = 0; $j < $num_PE_dato; $j++) {
						$d=$PE_array[$j].$fechas_array[$i];
						if (in_array($d, $ha_PE_fecha_array)) {
							foreach ( $ha_PE_fecha_array as $posicion => $fecha ) 
							{
								if($d==$fecha){
									$posicion1 = array_search( $fecha, $ha_PE_fecha_array );
									$dato= $dato."[".$i.",".$j.",". $ha_array[$posicion1]."],";
								}
							} 	
						}else
						{
							$dato=$dato."[".$i.",".$j.",0],";
						}
					}
				}	
			}
	$datofinal="[".substr($dato,0, -1)."]";
	echo$datofinal;
mysql_close($db);

}
?>

