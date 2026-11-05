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
        return [];
    }

    /**
     * Verifica que la empresa exista y esté activa (estado = 1)
     */
    public function existeActiva($nombre)
    {
        $sql = mysqli_prepare($this->conexion, 'SELECT 1 FROM empresa WHERE R_SOCIAL = ? AND estado = 1 LIMIT 1');
        mysqli_stmt_bind_param($sql, 's', $nombre);
        mysqli_stmt_execute($sql);
        mysqli_stmt_store_result($sql);

        $existe = mysqli_stmt_num_rows($sql) > 0;
        mysqli_stmt_close($sql);

        return $existe;
    }
}