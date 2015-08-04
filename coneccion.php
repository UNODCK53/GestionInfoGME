<?php
$servidor = "10.1.2.17";
$usuario = "gestioninfogme";
$contrasena = "gestioninfogme";
$base_datos="gestioninfogme";
$db=mysql_connect($servidor,$usuario,$contrasena);
if (!$db)
  {
  die('No se pudo conectar a la base: ' . mysql_error($db));
  }
?>