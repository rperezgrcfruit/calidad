<?php

class ModelDesverdizado
{
    public function IngresarDesverdizado($conexion, $lote, $temporada = TEMPORADA_ACTUAL)
    {
        $sql = "UPDATE existencia_calibrado SET desverdizado = 1, ingreso_desverdizado = CURRENT_TIMESTAMP 
        WHERE folio = ? and empresa = ? and temporada = ? and desverdizado = 0";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $lote, $_SESSION['empresa'], $temporada);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            return true;
        } else {
            return false;
        }
    }

        public function LiberarDesverdizado($conexion, $lote, $temporada = TEMPORADA_ACTUAL)
    {
        $sql = "UPDATE existencia_calibrado SET desverdizado = 0, salida_desverdizado = CURRENT_TIMESTAMP 
        WHERE folio = ? and empresa = ? and temporada = ? and desverdizado = 1";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $lote, $_SESSION['empresa'], $temporada);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
