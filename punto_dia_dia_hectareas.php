
<?php
$ano =$_GET['ano'];
$fase =$_GET['fase'];
$punto =$_GET['punto'];
valores($ano,$fase,$punto);
function valores ($ano,$fase,$punto) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
	if ($punto==99){
		$sql_PE="";
	}else
	{
		$sql_PE=" and (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )='".$punto."' ";
	}
	if ($fase==99){
		$sql_fase="";
	}else
	{
		$sql_fase=" and `FASE`='".$fase."' ";
	}
	if ($ano==99){
		$sql_ano="";
	}else
	{
		$sql_ano=" where SUBSTR(`FECHA_REPORT`,7,4)=".$ano."";
	}
	
	$fechas=mysql_query("select CAST(CONCAT(SUBSTR(`FECHA_REPORT`,7,4),'-',`MES`,'-',`DIA`,' 00:00:00')as date) as fechas FROM gestioninfogme.info_diario " .$sql_ano.$sql_fase.$sql_PE." group by fechas ORDER BY `fechas` ASC");
	$PE=mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PEs FROM gestioninfogme.info_diario ".$sql_ano.$sql_fase.$sql_PE." group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) ORDER BY `NOM_PEs` ASC");
	$fechas_array= array();
	while ($fila2 = mysql_fetch_array($fechas, MYSQL_ASSOC)) {
		$fechas_array[]=$fila2['fechas'];
	}
	$PE_array= array();
	while ($fila3 = mysql_fetch_array($PE, MYSQL_ASSOC)) {
		$PE_array[]=$fila3['NOM_PEs'];
	}
	$vector=array();
	$vector=array($fechas_array,$PE_array);	
	print json_encode($vector,JSON_NUMERIC_CHECK);
	mysql_close($db);
}
?>

