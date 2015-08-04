
<?php 	

$ano =$_GET['ano'];
$fase =$_GET['fase'];
$punto =$_GET['punto'];
valores($ano,$fase,$punto );
function valores ($ano,$fase,$punto ) {
	include ("coneccion.php");
		mysql_select_db($base_datos,$db);
		if($ano==99){
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
		}elseif($fase==99 && $ano != 99 && $punto != 99){
		
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
			$sql=mysql_query("select (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end) as NOM_GE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and NOM_PE='".$punto."' group by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) order by (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_GE'];
					$data=$row['NOM_GE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}elseif($fase==99 && $ano != 99 && $punto == 99){
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
			$sql=mysql_query("select (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end) as NOM_GE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by NOM_PGE order by NOM_PGE asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_GE'];
					$data=$row['NOM_GE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}elseif($fase!=99 && $ano != 99 && $punto == 99){
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
			$sql=mysql_query("select (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end) as NOM_GE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."'  group by NOM_PGE order by NOM_PGE asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_GE'];
					$data=$row['NOM_GE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}elseif($fase!=99 && $ano != 99 && $punto != 99){
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
			$sql=mysql_query("select (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end) as NOM_GE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."'  and NOM_PE='".$punto."' group by NOM_PGE order by NOM_PGE asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_GE'];
					$data=$row['NOM_GE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}
	mysql_close($db);
}
?>