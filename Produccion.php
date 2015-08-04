
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
		if($ano==99){
			//sentencia para hectareas por dia
			$sql=mysql_query("SELECT UNIX_TIMESTAMP(CONVERT_TZ (CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date),'+00:00','-05:00')) as fecha,sum(T_erradi) as area FROM `info_diario` group by `FECHA_REPORT`ORDER BY `fecha`  ASC");
			while($r = mysql_fetch_array($sql)) {
			
				$datetime = $r['fecha']*1000;
				$out = $r['area'];
				$data[] = [$datetime, $out];
			}
			//sentencia para GME activos por dia
			$sql2=mysql_query("SELECT fecha, sum(dias)as dias from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,UNIX_TIMESTAMP(CONVERT_TZ (CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date),'+00:00','-05:00')) as fecha,count(`llave`) as dias FROM `info_diario` GROUP by fecha)as todo  GROUP by todo.fecha ORDER BY todo.`fecha` ASC");
			while($r2 = mysql_fetch_array($sql2)) {
			
				$datetime = $r2['fecha']*1000;
				$out = $r2['dias'];
				$data2[] = [$datetime, $out];	
			}
			
		}elseif($fase==99 && $ano != 99){
			//sentencia para hectareas por dia
			$sql=mysql_query("SELECT UNIX_TIMESTAMP(CONVERT_TZ (CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date),'+00:00','-05:00')) as fecha,sum(T_erradi) as area FROM `info_diario` WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano.$sql_PE." group by `FECHA_REPORT`ORDER BY `fecha`  ASC");
			while($r = mysql_fetch_array($sql)) {
				$datetime = $r['fecha']*1000;
				$out = $r['area'];
				$data[] = [$datetime, $out];
			}
			//sentencia para GME activos por dia
			$sql2=mysql_query("SELECT fecha, sum(dias)as dias from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,UNIX_TIMESTAMP(CONVERT_TZ (CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date),'+00:00','-05:00')) as fecha,count(`llave`) as dias FROM `info_diario`  WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano.$sql_PE." GROUP by fecha)as todo where todo.NOM_PE !='Ninguno' GROUP by todo.fecha ORDER BY todo.`fecha` ASC" );
			while($r2 = mysql_fetch_array($sql2)) {
				$datetime = $r2['fecha']*1000;
				$out = $r2['dias'];
				$data2[] = [$datetime, $out];	
			}
		}else{
			//sentencia para hectareas por dia

			$sql=mysql_query("SELECT UNIX_TIMESTAMP(CONVERT_TZ (CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date),'+00:00','-05:00')) as fecha,sum(T_erradi) as area FROM `info_diario` WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano.$sql_PE." and `FASE`='".$fase."' group by `FECHA_REPORT`ORDER BY `fecha`  ASC");
			while($r = mysql_fetch_array($sql)) {
			
				$datetime = $r['fecha']*1000;
				$out = $r['area'];
				$data[] = [$datetime, $out];
			}
			//sentencia para GME activos por dia
			$sql2=mysql_query("SELECT fecha, sum(dias)as dias from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,UNIX_TIMESTAMP(CONVERT_TZ (CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date),'+00:00','-05:00')) as fecha,count(`llave`) as dias FROM `info_diario`  WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano.$sql_PE."   and `FASE`='".$fase."'  GROUP by fecha)as todo where todo.NOM_PE !='Ninguno' GROUP by todo.fecha ORDER BY todo.`fecha` ASC");			
			while($r2 = mysql_fetch_array($sql2)) {
			
				$datetime = $r2['fecha']*1000;
				$out = $r2['dias'];
				$data2[] = [$datetime, $out];
			}
		}
			print json_encode(array("a" => $data, "b" => $data2), JSON_NUMERIC_CHECK);



mysql_close($db);	

}
?>