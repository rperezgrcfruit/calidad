<?php

class ControllerDesverdizado
{
    public function Index()
    {
        include_once RUTA . '/calidad/view/calidad/desverdizado/gestion_desverdizado.php';
    }
    public function IngresarDesverdizado($conexion, $lote)
    {
        $model = new ModelDesverdizado();
        $result = $model->IngresarDesverdizado($conexion, $lote);

        if ($result) {
            return json_encode(['message' => 'Desverdizado ingresado correctamente.']);
        } else {
            return json_encode(['message' => 'Error al ingresar desverdizado.']);
        }
    }

    public function LiberarDesverdizado($conexion, $lote)
    {
        $model = new ModelDesverdizado();
        $result = $model->LiberarDesverdizado($conexion, $lote);

        if ($result) {
            return json_encode(['message' => 'Desverdizado liberado correctamente.']);
        } else {
            return json_encode(['message' => 'Error al liberar desverdizado.']);
        }
    }
}

