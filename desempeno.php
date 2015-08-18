
<?php 	
$ano =$_GET['ano'];
$fase =$_GET['fase'];
$profesional =$_GET['profesional'];
valores($ano,$fase,$profesional);
function valores ($ano,$fase,$profesional) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);

		if($ano==99){
			$a=array();
			$b=array();
			$c=array();
			$d=array();
			$result =mysql_query("select todo.NOM_PE, todo.Dias_contratados, todo.dias_erradicados, todo.T_erradi, CAST((todo.T_erradi/todo.dias_erradicados)as DECIMAL (3,2))as promedio_ha,todo.Ha_Coca,todo.Ha_Amapola,todo.Ha_Marihuana from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,count(CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )as Dias_contratados,sum((case when `T_erradi`=0 then 0 else 1 end))as dias_erradicados,sum(`T_erradi`) as T_erradi,sum(`Ha_Coca`) as Ha_Coca,sum(`Ha_Amapola`) as Ha_Amapola,sum(`Ha_Marihuana`) as Ha_Marihuana FROM `info_diario` GROUP BY (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as todo ORDER BY `todo`.`NOM_PE` ASC");
					$rows = array();
					$rows2 = array();
					$rows3 = array();
					$rows4 = array();
					while($r = mysql_fetch_array($result)) {
						$a = $r['T_erradi'];
						$b=$r['dias_erradicados'];
						$c=$r['NOM_PE'];
						$d=$r['promedio_ha'];
						array_push($rows,$a);
						array_push($rows2,$b);
						array_push($rows3,$c);
						array_push($rows4,$d);
					}
		}elseif($fase==99 && $ano != 99 && $profesional==99){
			$a=array();
			$b=array();
			$c=array();
			$d=array();
			$result =mysql_query("select todo.NOM_PE, todo.Dias_contratados, todo.dias_erradicados, todo.T_erradi, CAST((todo.T_erradi/todo.dias_erradicados)as DECIMAL (3,2))as promedio_ha,todo.Ha_Coca,todo.Ha_Amapola,todo.Ha_Marihuana from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,count((CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ))as Dias_contratados,sum((case when `T_erradi`=0 then 0 else 1 end))as dias_erradicados,sum(`T_erradi`) as T_erradi,sum(`Ha_Coca`) as Ha_Coca,sum(`Ha_Amapola`) as Ha_Amapola,sum(`Ha_Marihuana`) as Ha_Marihuana FROM `info_diario`WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." GROUP BY (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as todo ORDER BY `todo`.`NOM_PE` ASC");
					$rows = array();
					$rows2 = array();
					$rows3 = array();
					$rows4 = array();
					while($r = mysql_fetch_array($result)) {
						$a = $r['T_erradi'];
						$b=$r['dias_erradicados'];
						$c=$r['NOM_PE'];
						$d=$r['promedio_ha'];
						array_push($rows,$a);
						array_push($rows2,$b);
						array_push($rows3,$c);
						array_push($rows4,$d);
					}
		}
		elseif($fase==99 && $ano != 99 && $profesional!=99){
			$a=array();
			$b=array();
			$c=array();
			$d=array();
			$result =mysql_query("select todo.NOM_PE, todo.Dias_contratados, todo.dias_erradicados, todo.T_erradi, CAST((todo.T_erradi/todo.dias_erradicados)as DECIMAL (3,2))as promedio_ha,todo.Ha_Coca,todo.Ha_Amapola,todo.Ha_Marihuana from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ) as NOM_PE,count((CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ))as Dias_contratados,sum((case when `T_erradi`=0 then 0 else 1 end))as dias_erradicados,sum(`T_erradi`) as T_erradi,sum(`Ha_Coca`) as Ha_Coca,sum(`Ha_Amapola`) as Ha_Amapola,sum(`Ha_Marihuana`) as Ha_Marihuana FROM `info_diario`WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end)='".$profesional."' GROUP BY (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as todo ORDER BY `todo`.`NOM_PE` ASC");
					$rows = array();
					$rows2 = array();
					$rows3 = array();
					$rows4 = array();
					while($r = mysql_fetch_array($result)) {
						$a = $r['T_erradi'];
						$b=$r['dias_erradicados'];
						$c=$r['NOM_PE'];
						$d=$r['promedio_ha'];
						array_push($rows,$a);
						array_push($rows2,$b);
						array_push($rows3,$c);
						array_push($rows4,$d);
					}
			
			
		}elseif($fase!=99 && $ano != 99 && $profesional!=99){
			$a=array();
			$b=array();
			$c=array();
			$d=array();
			$result =mysql_query("select todo.NOM_PE, todo.Dias_contratados, todo.dias_erradicados, todo.T_erradi, CAST((todo.T_erradi/todo.dias_erradicados)as DECIMAL (3,2))as promedio_ha,todo.Ha_Coca,todo.Ha_Amapola,todo.Ha_Marihuana from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )as NOM_PE,count((CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ))as Dias_contratados,sum((case when `T_erradi`=0 then 0 else 1 end))as dias_erradicados,sum(`T_erradi`) as T_erradi,sum(`Ha_Coca`) as Ha_Coca,sum(`Ha_Amapola`) as Ha_Amapola,sum(`Ha_Marihuana`) as Ha_Marihuana FROM `info_diario`WHERE  SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."' and (case when `NOM_PGE`='Otros' then `Otro_NOM_PGE`else `NOM_PGE` end)='".$profesional."' GROUP BY (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as todo ORDER BY `todo`.`NOM_PE` ASC");
					$rows = array();
					$rows2 = array();
					$rows3 = array();
					$rows4 = array();
					while($r = mysql_fetch_array($result)) {
						$a = $r['T_erradi'];
						$b=$r['dias_erradicados'];
						$c=$r['NOM_PE'];
						$d=$r['promedio_ha'];
						array_push($rows,$a);
						array_push($rows2,$b);
						array_push($rows3,$c);
						array_push($rows4,$d);
					}
			
			
		}else{
			$a=array();
			$b=array();
			$c=array();
			$d=array();
			$result =mysql_query("select todo.NOM_PE, todo.Dias_contratados, todo.dias_erradicados, todo.T_erradi, CAST((todo.T_erradi/todo.dias_erradicados)as DECIMAL (3,2))as promedio_ha,todo.Ha_Coca,todo.Ha_Amapola,todo.Ha_Marihuana from (SELECT (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )as NOM_PE,count((CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END ))as Dias_contratados,sum((case when `T_erradi`=0 then 0 else 1 end))as dias_erradicados,sum(`T_erradi`) as T_erradi,sum(`Ha_Coca`) as Ha_Coca,sum(`Ha_Amapola`) as Ha_Amapola,sum(`Ha_Marihuana`) as Ha_Marihuana FROM `info_diario`WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and SUBSTR(NOM_PE,3,2)='".$fase."' GROUP BY (CASE NOM_PE WHEN NOM_PE !='Otro' THEN Otro_PE ELSE NOM_PE END )) as todo ORDER BY `todo`.`NOM_PE` ASC");
					$rows = array();
					$rows2 = array();
					$rows3 = array();
					$rows4 = array();
					while($r = mysql_fetch_array($result)) {
						$a = $r['T_erradi'];
						$b=$r['dias_erradicados'];
						$c=$r['NOM_PE'];
						$d=$r['promedio_ha'];
						array_push($rows,$a);
						array_push($rows2,$b);
						array_push($rows3,$c);
						array_push($rows4,$d);
					}
			
			
		}
		
	
		print json_encode(array("a" => $rows, "b" => $rows2, "c" => $rows3, "d" => $rows4), JSON_NUMERIC_CHECK);
		
		mysql_close($db);
}
?>