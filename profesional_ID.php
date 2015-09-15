
<?php 	

$ano =$_GET['ano'];
$fase =$_GET['fase'];
$profesional =$_GET['profesional'];
valores($ano,$fase,$profesional);
function valores ($ano,$fase,$profesional) {
	include ("coneccion.php");
		mysql_select_db($base_datos,$db);
		if($ano==99){
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
		}elseif($fase==99 && $ano != 99){
		
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
			$sql=mysql_query("select (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end) as NOM_GE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano." group by NOM_PGE order by NOM_PGE asc");
			while($row=mysql_fetch_array($sql))
				{
					//crea la lista de opciones segun la sentencia sql 2012
					$id=$row['NOM_GE'];
					$data=$row['NOM_GE'];
					echo '<option value="'.$id.'">'.$data.'</option>';
				}
		}else{
			echo '<option value="">Seleccione uno</option>';
			echo '<option value="99">Todos los Profesionales</option>';
			$sql=mysql_query("select (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end) as NOM_GE FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,1,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."'  group by NOM_PGE order by NOM_PGE asc");
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