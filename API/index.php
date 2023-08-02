<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../util/database.php';
$energia_actual=0;
global $conexion;
date_default_timezone_set('Europe/Madrid');
$lockFile = __DIR__ . '/index.lock';

// Intenta obtener el bloqueo
$lockHandle = fopen($lockFile, 'w+');
if (!$lockHandle) {
    die('No se pudo obtener el bloqueo.');
}

// Intenta bloquear el archivo
if (!flock($lockHandle, LOCK_EX | LOCK_NB)) {
    fclose($lockHandle);
    die('El script ya se está ejecutando.');
}

//$power = calcular_power();
function calcular_power(){
    return  round(100 / mt_rand(1, 100), 2);
}
function calcular_energia($power, $energia_anterior) {
    $energia = $energia_anterior + round( ($power * 60), 2);
    return round($energia, 2);
}
function insertar_datos($fecha_actual, $powerDevice1, $powerDevice2, $energia_actual)
{
    global $conexion;

    $sql = "INSERT INTO d_meter (date_time, power1, power2, energy) VALUES ('$fecha_actual', $powerDevice1, $powerDevice2, $energia_actual)";
    $res = mysqli_query($conexion, $sql);
    if (!$res) {
        echo 'Error en la consulta: ' . mysqli_error($conexion);
    }
}
function fecha_actual(){
    return date('Y-m-d H:i:s');
}
function calcular_datos($energia_actual)
{
    $energia_anterior = 0;
    while (true) {
        // Obtener la hora y el minuto actual
        $hora_actual = date('H', strtotime(fecha_actual()));
        $minuto_actual = date('i', strtotime(fecha_actual()));

        if ($hora_actual == 0 && $minuto_actual == 0) {
            $energia_actual = 0;
        }

        if ($minuto_actual > 15) {
           // $powerDevice1 = calcular_power();
            //$powerDevice2 = calcular_power();
            // Calcular la energía para cada dispositivo
            $energia1 = calcular_energia(calcular_power(), $energia_anterior);
            $energia2 = calcular_energia(calcular_power(), $energia_anterior);
            $energia_actual = max($energia1, $energia2);
            $energia_anterior = $energia_actual;
            //echo "$hora_minuto_actual | $powerDevice1 | $powerDevice2 | $energia_actual<br/>";
            insertar_datos(fecha_actual(), calcular_power(), calcular_power(), $energia_actual);
            // ob_flush();    // Liberar el búfer de salida
            // flush();
            sleep(60);
        } else {
            //echo "aún no --> $fecha_actual <br/>";
            sleep(1); // Esperar 1 segundo antes de verificar nuevamente
        }
    }
}

calcular_datos($energia_actual);

// Liberar el bloqueo y cerrar el archivo
flock($lockHandle, LOCK_UN);
fclose($lockHandle);
unlink($lockFile);