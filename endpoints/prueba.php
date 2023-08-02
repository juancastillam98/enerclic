<?php
echo 'cholaaaa';
function saludar()
{
    $contador = 0;
    while ($contador < 5) {
        echo 'Hola!';
        ob_flush();    // Liberar el búfer de salida
        flush();       // Enviar el contenido al navegador
        sleep(5);
        $contador++;
    }
}

saludar();
ob_end_flush();