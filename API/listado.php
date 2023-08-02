<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require '../util/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fecha'])) {
    $fecha_seleccionada = $_POST['fecha'];
    if (isset($_POST['potencia'])) { // Verificar si se pidió la potencia
        obtenerDatosPotenciaParaGrafica($fecha_seleccionada);
    } else {
        obtenerDatosEnergiaParaGrafica($fecha_seleccionada);
    }
} else {
    echo json_encode(array('error' => 'Fecha no recibida.'));
}

//Función energía x fecha
function obtenerDatosEnergiaParaGrafica($fechaSeleccionada)
{
    global $conexion;
    // Consulta para obtener los datos necesarios para la gráfica
    $query = "SELECT date_time, energy FROM d_meter WHERE DATE(date_time) = '$fechaSeleccionada'";
    $resultado = $conexion->query($query);

    if ($resultado) {
        if ($resultado->num_rows > 0) {
            $data = array();
            while ($row = $resultado->fetch_assoc()) {
                $date_time = $row['date_time'];
                $energy = floatval($row['energy']); // Convertir a número flotante
                // Agregar los datos a la estructura del arreglo
                $data[] = array(strtotime($date_time) * 1000, $energy); // Convertir la fecha a milisegundos para Highcharts
            }
            // Enviar los datos en formato JSON al cliente
            echo json_encode($data);
        } else {
            echo json_encode(array('error' => 'No se encontraron datos para la fecha seleccionada.'));
        }
    } else {
        echo json_encode(array('error' => 'Error en la consulta: ' . $conexion->error));
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}

//Función power x energía
function obtenerDatosPotenciaParaGrafica($fechaSeleccionada)
{
    global $conexion;
    $query = "SELECT date_time, power1, power2 FROM d_meter WHERE DATE(date_time) = '$fechaSeleccionada'";

    $resultado = $conexion->query($query);

    if ($resultado) {
        if ($resultado->num_rows > 0) {
            $dataPower1 = array();
            $dataPower2 = array();
            while ($row = $resultado->fetch_assoc()) {
                $date_time = $row['date_time'];
                $power1 = floatval($row['power1']); // Convertir a número flotante
                $power2 = floatval($row['power2']); // Convertir a número flotante
                // Agregar los datos a la estructura del arreglo para power1 y power2
                $dataPower1[] = array(strtotime($date_time) * 1000, $power1); // Convertir la fecha a milisegundos para Highcharts
                $dataPower2[] = array(strtotime($date_time) * 1000, $power2); // Convertir la fecha a milisegundos para Highcharts
            }
            // Enviar los datos en formato JSON al cliente
            $data = array('power1' => $dataPower1, 'power2' => $dataPower2);
            echo json_encode($data);
        } else {
            echo json_encode(array('error' => 'No se encontraron datos de potencia para la fecha seleccionada.'));
        }
    } else {
        echo json_encode(array('error' => 'Error en la consulta: ' . $conexion->error));
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}