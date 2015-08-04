
<?php 	
$ano =$_GET['ano'];
$fase =$_GET['fase'];
$profesional =$_GET['profesional'];


valores($ano,$fase,$profesional);
function valores ($ano,$fase,$profesional) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);
		
		if($ano==99){
			$result =mysql_query("SELECT (case WHEN Nom_Per_Evacu='Otro' then `Nom_Otro_Per_Evacu` else `Nom_Per_Evacu` end),Cargo_Per_EVA,Motivo_Eva, count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as cuenta FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 GROUP by concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)");
			$sum =mysql_query("SELECT count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as suma FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 ");
			$cuenta= mysql_result($sum, 0);
			$rows = array();
			while($r = mysql_fetch_array($result)) {
				$row[0] = $r['Motivo_Eva'];
				$row[1] = $r['cuenta'];
				array_push($rows,$row);
			}

		}elseif($fase==99 && $ano != 99 && $profesional==99){
			
			$result =mysql_query("SELECT (case WHEN Nom_Per_Evacu='Otro' then `Nom_Otro_Per_Evacu` else `Nom_Per_Evacu` end),Cargo_Per_EVA,Motivo_Eva, count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as cuenta FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." GROUP by concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)");
			$sum =mysql_query("SELECT count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as suma FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano);
			$cuenta= mysql_result($sum, 0);

			$rows = array();
			while($r = mysql_fetch_array($result)) {
				$row[0] = $r['Motivo_Eva'];
				$row[1] = $r['cuenta'];
				array_push($rows,$row);
			}
			$suma=count ($cuenta);
			
		}
		elseif($fase==99 && $ano != 99 && $profesional!=99){
			
			$result =mysql_query("SELECT (case WHEN Nom_Per_Evacu='Otro' then `Nom_Otro_Per_Evacu` else `Nom_Per_Evacu` end),Cargo_Per_EVA,Motivo_Eva, count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as cuenta FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." and (case when `NOM_GE`='Otros' then `Otro_PGE`else `NOM_GE` end)='".$profesional."' GROUP by concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)");
			$rows = array();
			$sum =mysql_query("SELECT count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as suma FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." and (case when `NOM_GE`='Otros' then `Otro_PGE`else `NOM_GE` end)='".$profesional."'");
			$cuenta= mysql_result($sum, 0);
			while($r = mysql_fetch_array($result)) {
				$row[0] = $r['Motivo_Eva'];
				$row[1] = $r['cuenta'];
				array_push($rows,$row);
			}

			
		}elseif($fase!=99 && $ano != 99 && $profesional!=99){
			
			$result =mysql_query("SELECT (case WHEN Nom_Per_Evacu='Otro' then `Nom_Otro_Per_Evacu` else `Nom_Per_Evacu` end),Cargo_Per_EVA,Motivo_Eva, count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as cuenta FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."' and (case when `NOM_GE`='Otros' then `Otro_PGE`else `NOM_GE` end)='".$profesional."' GROUP by concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)");
			$rows = array();
			$sum =mysql_query("SELECT count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as suma FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."' and (case when `NOM_GE`='Otros' then `Otro_PGE`else `NOM_GE` end)='".$profesional."'");
			$cuenta= mysql_result($sum, 0);
			while($r = mysql_fetch_array($result)) {
				$row[0] = $r['Motivo_Eva'];
				$row[1] = $r['cuenta'];
				array_push($rows,$row);
			}
			

		}else{
			$result =mysql_query("SELECT (case WHEN Nom_Per_Evacu='Otro' then `Nom_Otro_Per_Evacu` else `Nom_Per_Evacu` end),Cargo_Per_EVA,Motivo_Eva, count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as cuenta FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."' GROUP by concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)");
			$rows = array();
			$sum =mysql_query("SELECT count(concat(Nom_Per_Evacu,Cargo_Per_EVA,Motivo_Eva)) as suma FROM gestioninfogme.enlace_novedad WHERE`Num_Evacua`>0 and  SUBSTR(`FECHA_NOVEDAD`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."'");
			$cuenta= mysql_result($sum, 0);
			while($r = mysql_fetch_array($result)) {
				$row[0] = $r['Motivo_Eva'];
				$row[1] = $r['cuenta'];
				array_push($rows,$row);
			}

		}
		

		echo json_encode(array("a"=>$rows,"b"=>$cuenta),JSON_NUMERIC_CHECK);
		
		mysql_close($db);
}
?>