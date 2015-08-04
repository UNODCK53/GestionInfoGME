
<?php 	
$ano =$_GET['ano'];
$fase =$_GET['fase'];

valores($ano,$fase);
function valores ($ano,$fase) {
	include ("coneccion.php");
	mysql_select_db($base_datos,$db);

	if($ano==99){
			$deptos=mysql_query("SELECT Departamento FROM gestioninfogme.info_diario where Departamento is not null group by Departamento ORDER BY Departamento");//sql que trae los departamentos con erradicacion en el historico
			
			$muni=mysql_query("SELECT Departamento,Muncipio FROM gestioninfogme.info_diario where Departamento is not null group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc");//sql que trae los municipio con erradicacion en el historico
			
			$ha = mysql_query("select Departamento,Muncipio,(CASE WHEN Ha_Coca>0 then Ha_Coca else 0 end ) as Ha_Coca,(CASE WHEN Ha_Amapola>0 then Ha_Amapola else 0 end ) as Ha_Amapola,(CASE WHEN Ha_Marihuana>0 then Ha_Marihuana else 0 end ) as Ha_Marihuana from (SELECT Departamento,Muncipio,CAST(sum(Ha_Coca) AS DECIMAL(4,2))as Ha_Coca,CAST(sum(Ha_Amapola) AS DECIMAL(4,2))as Ha_Amapola,CAST(sum(Ha_Marihuana) AS DECIMAL(4,2))as Ha_Marihuana,CAST(sum(T_erradi) AS DECIMAL(4,2))as T_erradi FROM gestioninfogme.info_diario where Departamento is not null group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc) as todo");//sql que trae los departamentos,municipios, total erradicado y la desagrgacion por tipo de cultivo
			
			//dos sql que traen el numero total de departamentos
			$num_deptos = mysql_query("select count(Departamento) from (SELECT Departamento FROM gestioninfogme.info_diario group by Departamento) as depto ");
			$num_deptos_dato= mysql_result($num_deptos, 0);
			
			//dos sql que traen el numero total de municipios
			$num_muni = mysql_query("select count(Muncipio) from (SELECT Departamento,Muncipio FROM gestioninfogme.info_diario group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc)as mun");
			$num_muni_dato= mysql_result($num_muni, 0);
			
			//crea un arreglo 'deptos_array' con los departamentos a partir del sql a la base
			$deptos_array= array();
			while ($fila1 = mysql_fetch_array($deptos, MYSQL_ASSOC)) {
				$deptos_array[]=$fila1['Departamento'];
			}
			
			//crea un arreglo 'muni_array' con los municipios a partir del sql a la base
			$muni_array= array();
			while ($fila3 = mysql_fetch_array($muni, MYSQL_ASSOC)) {
				$muni_array[]=$fila3['Muncipio'];
			}
			
			//crea 4 arreglos: 'ha_coca' -> vector con hectareas de coca, 'Ha_Amapola'-> vector con hectareasde amapola,'Ha_Marihuana' -> vector con hectareas de marihuana y 'ha_concat_array'-> vector que concatena del departamento y municipio para una proxima busqueta en otro vector
			while ($fila2 = mysql_fetch_array($ha, MYSQL_ASSOC)) {
				$ha_coca[]=$fila2['Ha_Coca'];
				$Ha_Amapola[]=$fila2['Ha_Amapola'];
				$Ha_Marihuana[]=$fila2['Ha_Marihuana'];
				$ha_concat_array[]=$fila2['Departamento'].$fila2['Muncipio'];
			}

			
				$muni = array();
				$coca = array();
				$amapola = array();
				$marihuana = array();
				$parte1 = array();
				$parte2 = array();
				$parte3 = array();	

			//inicia una sere de ciclos para obtener los valores necesarios par ala grafica	
			for ($i = 0; $i < $num_deptos_dato; $i++) {//ciclo en el vector depratmaneto, la variable $i representa la posicion del departamento
				$suma=0;
				for ($j = 0; $j < $num_muni_dato; $j++) {//ciclo en el vector municipio, la variable $j representa la posicion del municipio
					$suma2=0;
					$d=$deptos_array[$i].$muni_array[$j];//variable $d que concatenga el departamento y el municipio para compararlo con el vector 'ha_concat_array'
					
					
					if (in_array($d, $ha_concat_array)) {// busca la concatencaion del departamento($i) y municipio($j) en el vector 'ha_concat_array' si lo encuentra ingresa al condicional
						
						foreach ( $ha_concat_array as $posicion => $deptos ) //realiza un ciclo para los datos del vector 'ha_concat_array' 
						{
							if($d==$deptos){// compara si las dos concatenaciones de departamento y municipio son iguales
								
								$posicion1 = array_search( $deptos, $ha_concat_array );//asigna la posicion del dato 'deptos' en el vector 'ha_concat_array' a la variable '$posicion1'
								
								$suma=$suma+$ha_coca[$posicion1]+$Ha_Amapola[$posicion1]+$Ha_Marihuana[$posicion1];//realiza la suma total erradicadas por tipo de cultivo por departamento
								$suma2=$suma2+$ha_coca[$posicion1]+$Ha_Amapola[$posicion1]+$Ha_Marihuana[$posicion1];//realiza la suma total erradicadas por tipo de cultivo por municipio
								
								//estructura para generacion de la grafica
								
								$coca['id'] = 'id_'.$i.'_'.$j.'_0';//id:id_(posici-deparatmento)_(posici-municipio)_0. el cero hace referencia al cultivo de coca. este id pertenece al cultivo de coca para el municipio
								$coca['name']= 'Cultivo de Coca'; 
								$coca['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$coca['value']= $ha_coca[$posicion1];//valor de Ha erradicadas en coca
								
								$amapola['id'] = 'id_'.$i.'_'.$j.'_1';//id:id_(posici-deparatmento)_(posici-municipio)_1. el uno hace referencia al cultivo de amapola.este id pertenece al cultivo de amapola para el municipio 
								$amapola['name']= 'Cultivo de Amapola';
								$amapola['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$amapola['value']=$Ha_Amapola[$posicion1];//valor de Ha erradicadas en amapola
								
								$marihuana['id'] = 'id_'.$i.'_'.$j.'_2';//id:id_(posici-deparatmento)_(posici-municipio)_2. el dos hace referencia al cultivo de marihuna.este id pertenece al marihuana de coca para el municipio 
								$marihuana['name']= 'Cultivo de Marihuana';
								$marihuana['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$marihuana['value']=$Ha_Marihuana[$posicion1];//valor de Ha erradicadas en marihuana
								array_push($parte1,$coca,$amapola,$marihuana);//crea un soslo arreglo 'parte1' con los vectores creados '$coca,$amapola,$marihuana'

							}
						} 	
					}
					$muni['id']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
					$muni['name']= $muni_array[$j];
					$muni['parent']= 'id_'.$i;	//id:id_(posici-deparatmento). este id pertence al departamento 						
					$muni['value']=$suma2;//valor total de Ha erradicadas por municipio
					array_push($parte1,$muni);//al vetor '$parte1' se le agrega '$muni'
					
				}
				
				$randomcolor = '#' . strtoupper(dechex(rand(0,10000000)));//genera un codigo de color html aleatorio
				$depto['color']=$randomcolor;
				$depto['id'] = 'id_'.$i;//id:id_(posici-deparatmento). este id pertence al departamento 				
				$depto['name']= $deptos_array[$i];	//dombre del departamento					
				$depto['value']= $suma;//valor total de Ha erradicadas por departamento
				array_push($parte1,$depto);//al vetor '$parte1' se le agrega '$depto'
				
			}	
		}elseif($fase==99 && $ano != 99){//codigo que filtra por año
			$deptos=mysql_query("SELECT Departamento FROM gestioninfogme.info_diario where Departamento is not null and SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by Departamento ORDER BY Departamento");//sql que trae los departamentos con erradicacion en el historico
			
			$muni=mysql_query("SELECT Departamento,Muncipio FROM gestioninfogme.info_diario where Departamento is not null and SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc");//sql que trae los municipio con erradicacion en el historico
			
			$ha = mysql_query("select Departamento,Muncipio,(CASE WHEN Ha_Coca>0 then Ha_Coca else 0 end ) as Ha_Coca,(CASE WHEN Ha_Amapola>0 then Ha_Amapola else 0 end ) as Ha_Amapola,(CASE WHEN Ha_Marihuana>0 then Ha_Marihuana else 0 end ) as Ha_Marihuana from (SELECT Departamento,Muncipio,CAST(sum(Ha_Coca) AS DECIMAL(4,2))as Ha_Coca,CAST(sum(Ha_Amapola) AS DECIMAL(4,2))as Ha_Amapola,CAST(sum(Ha_Marihuana) AS DECIMAL(4,2))as Ha_Marihuana,CAST(sum(T_erradi) AS DECIMAL(4,2))as T_erradi FROM gestioninfogme.info_diario where Departamento is not null and SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc) as todo");//sql que trae los departamentos,municipios, total erradicado y la desagrgacion por tipo de cultivo
			
			//dos sql que traen el numero total de departamentos
			$num_deptos = mysql_query("select count(Departamento) from (SELECT Departamento FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by Departamento) as depto ");
			$num_deptos_dato= mysql_result($num_deptos, 0);
			
			//dos sql que traen el numero total de municipios
			$num_muni = mysql_query("select count(Muncipio) from (SELECT Departamento,Muncipio FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc)as mun");
			$num_muni_dato= mysql_result($num_muni, 0);
			
			//crea un arreglo 'deptos_array' con los departamentos a partir del sql a la base
			$deptos_array= array();
			while ($fila1 = mysql_fetch_array($deptos, MYSQL_ASSOC)) {
				$deptos_array[]=$fila1['Departamento'];
			}
			
			//crea un arreglo 'muni_array' con los municipios a partir del sql a la base
			$muni_array= array();
			while ($fila3 = mysql_fetch_array($muni, MYSQL_ASSOC)) {
				$muni_array[]=$fila3['Muncipio'];
			}
			
			//crea 4 arreglos: 'ha_coca' -> vector con hectareas de coca, 'Ha_Amapola'-> vector con hectareasde amapola,'Ha_Marihuana' -> vector con hectareas de marihuana y 'ha_concat_array'-> vector que concatena del departamento y municipio para una proxima busqueta en otro vector
			while ($fila2 = mysql_fetch_array($ha, MYSQL_ASSOC)) {
				$ha_coca[]=$fila2['Ha_Coca'];
				$Ha_Amapola[]=$fila2['Ha_Amapola'];
				$Ha_Marihuana[]=$fila2['Ha_Marihuana'];
				$ha_concat_array[]=$fila2['Departamento'].$fila2['Muncipio'];
			}

			
				$muni = array();
				$coca = array();
				$amapola = array();
				$marihuana = array();
				$parte1 = array();
				$parte2 = array();
				$parte3 = array();	

			//inicia una sere de ciclos para obtener los valores necesarios par ala grafica	
			for ($i = 0; $i < $num_deptos_dato; $i++) {//ciclo en el vector depratmaneto, la variable $i representa la posicion del departamento
				$suma=0;
				for ($j = 0; $j < $num_muni_dato; $j++) {//ciclo en el vector municipio, la variable $j representa la posicion del municipio
					$suma2=0;
					$d=$deptos_array[$i].$muni_array[$j];//variable $d que concatenga el departamento y el municipio para compararlo con el vector 'ha_concat_array'
					
					
					if (in_array($d, $ha_concat_array)) {// busca la concatencaion del departamento($i) y municipio($j) en el vector 'ha_concat_array' si lo encuentra ingresa al condicional
						
						foreach ( $ha_concat_array as $posicion => $deptos ) //realiza un ciclo para los datos del vector 'ha_concat_array' 
						{
							if($d==$deptos){// compara si las dos concatenaciones de departamento y municipio son iguales
								
								$posicion1 = array_search( $deptos, $ha_concat_array );//asigna la posicion del dato 'deptos' en el vector 'ha_concat_array' a la variable '$posicion1'
								
								$suma=$suma+$ha_coca[$posicion1]+$Ha_Amapola[$posicion1]+$Ha_Marihuana[$posicion1];//realiza la suma total erradicadas por tipo de cultivo por departamento
								$suma2=$suma2+$ha_coca[$posicion1]+$Ha_Amapola[$posicion1]+$Ha_Marihuana[$posicion1];//realiza la suma total erradicadas por tipo de cultivo por municipio
								
								//estructura para generacion de la grafica
								
								$coca['id'] = 'id_'.$i.'_'.$j.'_0';//id:id_(posici-deparatmento)_(posici-municipio)_0. el cero hace referencia al cultivo de coca. este id pertenece al cultivo de coca para el municipio
								$coca['name']= 'Cultivo de Coca'; 
								$coca['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$coca['value']= $ha_coca[$posicion1];//valor de Ha erradicadas en coca
								
								$amapola['id'] = 'id_'.$i.'_'.$j.'_1';//id:id_(posici-deparatmento)_(posici-municipio)_1. el uno hace referencia al cultivo de amapola.este id pertenece al cultivo de amapola para el municipio 
								$amapola['name']= 'Cultivo de Amapola';
								$amapola['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$amapola['value']=$Ha_Amapola[$posicion1];//valor de Ha erradicadas en amapola
								
								$marihuana['id'] = 'id_'.$i.'_'.$j.'_2';//id:id_(posici-deparatmento)_(posici-municipio)_2. el dos hace referencia al cultivo de marihuna.este id pertenece al marihuana de coca para el municipio 
								$marihuana['name']= 'Cultivo de Marihuana';
								$marihuana['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$marihuana['value']=$Ha_Marihuana[$posicion1];//valor de Ha erradicadas en marihuana
								array_push($parte1,$coca,$amapola,$marihuana);//crea un soslo arreglo 'parte1' con los vectores creados '$coca,$amapola,$marihuana'

							}
						} 	
					}
					$muni['id']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
					$muni['name']= $muni_array[$j];
					$muni['parent']= 'id_'.$i;	//id:id_(posici-deparatmento). este id pertence al departamento 						
					$muni['value']=$suma2;//valor total de Ha erradicadas por municipio
					array_push($parte1,$muni);//al vetor '$parte1' se le agrega '$muni'
					
				}
				
				$randomcolor = '#' . strtoupper(dechex(rand(0,10000000)));//genera un codigo de color html aleatorio
				$depto['color']=$randomcolor;
				$depto['id'] = 'id_'.$i;//id:id_(posici-deparatmento). este id pertence al departamento 				
				$depto['name']= $deptos_array[$i];	//dombre del departamento					
				$depto['value']= $suma;//valor total de Ha erradicadas por departamento
				array_push($parte1,$depto);//al vetor '$parte1' se le agrega '$depto'
				
			}
		}else{//codigo que filtra por año y fase
			$deptos=mysql_query("SELECT Departamento FROM gestioninfogme.info_diario where Departamento is not null and SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and `FASE`='".$fase."' group by Departamento ORDER BY Departamento");//sql que trae los departamentos con erradicacion para una fase de un año espesifico
			
			$muni=mysql_query("SELECT Departamento,Muncipio FROM gestioninfogme.info_diario where Departamento is not null and SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and `FASE`='".$fase."' group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc");//sql que trae los municipio con erradicacion para una fase de un año espesifico
			
			$ha = mysql_query("select Departamento,Muncipio,(CASE WHEN Ha_Coca>0 then Ha_Coca else 0 end ) as Ha_Coca,(CASE WHEN Ha_Amapola>0 then Ha_Amapola else 0 end ) as Ha_Amapola,(CASE WHEN Ha_Marihuana>0 then Ha_Marihuana else 0 end ) as Ha_Marihuana from (SELECT Departamento,Muncipio,CAST(sum(Ha_Coca) AS DECIMAL(4,2))as Ha_Coca,CAST(sum(Ha_Amapola) AS DECIMAL(4,2))as Ha_Amapola,CAST(sum(Ha_Marihuana) AS DECIMAL(4,2))as Ha_Marihuana,CAST(sum(T_erradi) AS DECIMAL(4,2))as T_erradi FROM gestioninfogme.info_diario where Departamento is not null and SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and `FASE`='".$fase."' group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc) as todo");//sql que trae los departamentos,municipios, total erradicado y la desagrgacion por tipo de cultivo
			
			//dos sql que traen el numero total de departamentos
			$num_deptos = mysql_query("select count(Departamento) from (SELECT Departamento FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and `FASE`='".$fase."' group by Departamento) as depto ");
			$num_deptos_dato= mysql_result($num_deptos, 0);
			
			//dos sql que traen el numero total de municipios
			$num_muni = mysql_query("select count(Muncipio) from (SELECT Departamento,Muncipio FROM gestioninfogme.info_diario WHERE SUBSTR(`FECHA_REPORT`,7,4)=".$ano." and `FASE`='".$fase."' group by concat(Departamento,Muncipio) ORDER BY Departamento,Muncipio asc)as mun");
			$num_muni_dato= mysql_result($num_muni, 0);
			
			//crea un arreglo 'deptos_array' con los departamentos a partir del sql a la base
			$deptos_array= array();
			while ($fila1 = mysql_fetch_array($deptos, MYSQL_ASSOC)) {
				$deptos_array[]=$fila1['Departamento'];
			}
			
			//crea un arreglo 'muni_array' con los municipios a partir del sql a la base
			$muni_array= array();
			while ($fila3 = mysql_fetch_array($muni, MYSQL_ASSOC)) {
				$muni_array[]=$fila3['Muncipio'];
			}
			
			//crea 4 arreglos: 'ha_coca' -> vector con hectareas de coca, 'Ha_Amapola'-> vector con hectareasde amapola,'Ha_Marihuana' -> vector con hectareas de marihuana y 'ha_concat_array'-> vector que concatena del departamento y municipio para una proxima busqueta en otro vector
			while ($fila2 = mysql_fetch_array($ha, MYSQL_ASSOC)) {
				$ha_coca[]=$fila2['Ha_Coca'];
				$Ha_Amapola[]=$fila2['Ha_Amapola'];
				$Ha_Marihuana[]=$fila2['Ha_Marihuana'];
				$ha_concat_array[]=$fila2['Departamento'].$fila2['Muncipio'];
			}

			
				$muni = array();
				$coca = array();
				$amapola = array();
				$marihuana = array();
				$parte1 = array();
				$parte2 = array();
				$parte3 = array();	

			//inicia una sere de ciclos para obtener los valores necesarios par ala grafica	
			for ($i = 0; $i < $num_deptos_dato; $i++) {//ciclo en el vector depratmaneto, la variable $i representa la posicion del departamento
				$suma=0;
				for ($j = 0; $j < $num_muni_dato; $j++) {//ciclo en el vector municipio, la variable $j representa la posicion del municipio
					$suma2=0;
					$d=$deptos_array[$i].$muni_array[$j];//variable $d que concatenga el departamento y el municipio para compararlo con el vector 'ha_concat_array'
					
					
					if (in_array($d, $ha_concat_array)) {// busca la concatencaion del departamento($i) y municipio($j) en el vector 'ha_concat_array' si lo encuentra ingresa al condicional
						
						foreach ( $ha_concat_array as $posicion => $deptos ) //realiza un ciclo para los datos del vector 'ha_concat_array' 
						{
							if($d==$deptos){// compara si las dos concatenaciones de departamento y municipio son iguales
								
								$posicion1 = array_search( $deptos, $ha_concat_array );//asigna la posicion del dato 'deptos' en el vector 'ha_concat_array' a la variable '$posicion1'
								
								$suma=$suma+$ha_coca[$posicion1]+$Ha_Amapola[$posicion1]+$Ha_Marihuana[$posicion1];//realiza la suma total erradicadas por tipo de cultivo por departamento
								$suma2=$suma2+$ha_coca[$posicion1]+$Ha_Amapola[$posicion1]+$Ha_Marihuana[$posicion1];//realiza la suma total erradicadas por tipo de cultivo por municipio
								
								//estructura para generacion de la grafica
								
								$coca['id'] = 'id_'.$i.'_'.$j.'_0';//id:id_(posici-deparatmento)_(posici-municipio)_0. el cero hace referencia al cultivo de coca. este id pertenece al cultivo de coca para el municipio
								$coca['name']= 'Cultivo de Coca'; 
								$coca['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$coca['value']= $ha_coca[$posicion1];//valor de Ha erradicadas en coca
								
								$amapola['id'] = 'id_'.$i.'_'.$j.'_1';//id:id_(posici-deparatmento)_(posici-municipio)_1. el uno hace referencia al cultivo de amapola.este id pertenece al cultivo de amapola para el municipio 
								$amapola['name']= 'Cultivo de Amapola';
								$amapola['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$amapola['value']=$Ha_Amapola[$posicion1];//valor de Ha erradicadas en amapola
								
								$marihuana['id'] = 'id_'.$i.'_'.$j.'_2';//id:id_(posici-deparatmento)_(posici-municipio)_2. el dos hace referencia al cultivo de marihuna.este id pertenece al marihuana de coca para el municipio 
								$marihuana['name']= 'Cultivo de Marihuana';
								$marihuana['parent']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
								$marihuana['value']=$Ha_Marihuana[$posicion1];//valor de Ha erradicadas en marihuana
								array_push($parte1,$coca,$amapola,$marihuana);//crea un soslo arreglo 'parte1' con los vectores creados '$coca,$amapola,$marihuana'

							}
						} 	
					}
					$muni['id']= 'id_'.$i.'_'.$j;//id:id_(posici-deparatmento)_(posici-municipio). este id pertence al municipio 
					$muni['name']= $muni_array[$j];
					$muni['parent']= 'id_'.$i;	//id:id_(posici-deparatmento). este id pertence al departamento 						
					$muni['value']=$suma2;//valor total de Ha erradicadas por municipio
					array_push($parte1,$muni);//al vetor '$parte1' se le agrega '$muni'
					
				}
				
				$randomcolor = '#' . strtoupper(dechex(rand(0,10000000)));//genera un codigo de color html aleatorio
				$depto['color']=$randomcolor;
				$depto['id'] = 'id_'.$i;//id:id_(posici-deparatmento). este id pertence al departamento 				
				$depto['name']= $deptos_array[$i];	//dombre del departamento					
				$depto['value']= $suma;//valor total de Ha erradicadas por departamento
				array_push($parte1,$depto);//al vetor '$parte1' se le agrega '$depto'
				
			}
		}
	
	
		
	
		print json_encode($parte1,JSON_NUMERIC_CHECK);
		mysql_close($db);
}
?>