<?php
    // Obtener el cuerpo de la solicitud
    $request_body = file_get_contents('php://input');

    // Verificar si se recibió un cuerpo de solicitud válido
    if ($request_body === false) {
        echo "Error al leer el cuerpo de la solicitud";
    } else {
        // Decodificar los datos JSON
        $data = json_decode($request_body, true);

        // Verificar si se pudo decodificar el JSON correctamente
        if ($data === null) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
        } else {
            // Mostrar los datos recibidos
            if ($data['tipoDato'] == 'buscarlote') {
                echo 'buscar lote';
            }
        }
    }


   
?>


