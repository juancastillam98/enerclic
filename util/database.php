<?php
$hostname="127.0.0.1";
$username="root";
$password="";
$database="db_enerclic";
//PDO
$conexion=mysqli_connect($hostname, $username, $password, $database);

/*if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
} else {
    echo 'Conexión exitosa a la base de datos.';
}*/
//var_dump($conexion);


