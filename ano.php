
<?php 	

	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
				$sql=mysql_query("SELECT SUBSTR(`FECHA_REPORT`,7,4) as año FROM `info_diario` group by SUBSTR(`FECHA_REPORT`,7,4) ORDER BY `año` DESC");
				echo '<option value="">Seleccione uno:</option>';
				echo '<option value="99">Todos los años</option>';
				while($row=mysql_fetch_array($sql))
			{
				//crea la lista de opciones segun la sentencia sql 2012
				$id=$row['año'];
				$data=$row['año'];
				echo '<option value="'.$id.'">'.$data.'</option>';
			}
	mysql_close($db);

?>