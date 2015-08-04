
<?php 	

$ano =$_GET['ano'];
valores($ano);
function valores ($ano) {
	include ("coneccion.php");
		mysql_select_db($base_datos,$db);

		$sql=mysql_query("SELECT Fase FROM `info_diario` WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and  Fase !='' GROUP BY Fase ORDER BY fase DESC");
		echo '<option value="">Seleccione una:</option>';
		while($row=mysql_fetch_array($sql))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$id=$row['Fase'];
				$data=$row['Fase'];
				echo '<option value="'.$id.'">'.$data.'</option>';
			}
		
	mysql_close($db);
}
?>