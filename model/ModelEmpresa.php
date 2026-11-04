<?php

class ModelEmpresa{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function GetEmpresa(){

        $sql = mysqli_query($this->conexion, 'SELECT DISTINCT R_SOCIAL from empresa WHERE estado = 1 ORDER BY R_SOCIAL');
        if ($sql->num_rows > 0) {
            $data = mysqli_fetch_all($sql, MYSQLI_ASSOC);
            return $data;
        }
    }
}