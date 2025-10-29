<?php

function conectar()
{
	global $conexion;
	$conexion = mysqli_connect("localhost","root","","halloween");
		/* comprobar la conexión */
		if (mysqli_connect_errno()) 
		{
		    printf("Falló la conexión: %s\n", mysqli_connect_error());
		    exit();
		}
			else
			{
				$conexion -> set_charset("utf8");
				$ret=true;
			}
		
	return $ret;
}
function desconectar()
{
	global $conexion;
	mysqli_close($conexion);
}
?>