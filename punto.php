
<?php 	

$ano =$_GET['ano'];
$fase =$_GET['fase'];

valores($ano,$fase);
function valores ($ano,$fase) {
	include ("coneccion.php");
		mysql_select_db($base_datos,$db);
		if($ano==99){
			echo '<option value="">Seleccione uno:</option>';
			echo '<option value="99">Todos los puntos de erradicación</option>';
		}elseif($fase==99 && $ano != 99){
		
			echo '<option value="">Seleccione uno:</option>';
			echo '<option value="99">Todos los puntos de erradicación</option>';
			$sql=mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )order by  (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_PE'];
					$data=$row['NOM_PE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}else{
			echo '<option value="">Seleccione uno:</option>';
			echo '<option value="99">Todos los puntos de erradicación</option>';
			$sql=mysql_query("select (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and `FASE`='".$fase."' group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )order by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )  asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_PE'];
					$data=$row['NOM_PE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}
	mysql_close($db);
}
?>