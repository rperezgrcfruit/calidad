<?php

class ModelDefectos{


    public function GetAllTipoDefectos($conexion){

        $sql = mysqli_query($conexion, 'SELECT * FROM tipo_defectos ORDER BY tipo_defecto');
        $data = mysqli_fetch_all($sql, MYSQLI_ASSOC);

        return $data;
    }

    public function insertTipoDefecto($conexion, $tipoDefecto){

        $sql = mysqli_prepare($conexion, 'INSERT INTO tipo_defectos (tipo_defecto) VALUES (?) ');
        mysqli_stmt_bind_param($sql, 's', $tipoDefecto);
        $result = mysqli_stmt_execute($sql);

        mysqli_stmt_close($sql);

        return $result;
        
    }

    public function deleteTipoDefecto($conexion, $id){

        $sql = mysqli_prepare($conexion, 'DELETE FROM tipo_defectos WHERE id = ?');
        mysqli_stmt_bind_param($sql, 's', $id);
        $result = mysqli_stmt_execute($sql);

        mysqli_stmt_close($sql);

        return $result;
    }

    public function GetAllDefectos($conexion){

        $sql = mysqli_query($conexion, 'SELECT * FROM defectos ORDER BY descripcion');
        $data = mysqli_fetch_all($sql, MYSQLI_ASSOC);

        return $data;
    }

    public function ContarDefectos($conexion){

        $sql = mysqli_query($conexion, 'SELECT COUNT(*) AS total FROM defectos');
        $total = 0;

        if ($sql && $row = mysqli_fetch_assoc($sql)) {
            $total = (int) $row['total'];
        }

        return $total;
    }

    public function GetDefectosPaginados($conexion, $limit, $offset){

        $limit  = (int) $limit;
        $offset = (int) $offset;

        $sql = mysqli_prepare($conexion, 'SELECT * FROM defectos ORDER BY descripcion LIMIT ? OFFSET ?');
        mysqli_stmt_bind_param($sql, 'ii', $limit, $offset);
        mysqli_stmt_execute($sql);

        $result = mysqli_stmt_get_result($sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_stmt_close($sql);

        return $data;
    }

    public function insertDefecto($conexion, $tipoDefecto, $defecto){

        $sql = mysqli_prepare($conexion, 'INSERT INTO defectos (descripcion, tipo_defecto) VALUES (?, ?) ');
        mysqli_stmt_bind_param($sql, 'ss', $defecto, $tipoDefecto);
        $result = mysqli_stmt_execute($sql);

        mysqli_stmt_close($sql);

        return $result;
        
    }

    public function deleteDefecto($conexion, $id){

        $sql = mysqli_prepare($conexion, 'DELETE FROM defectos WHERE id = ?');
        mysqli_stmt_bind_param($sql, 's', $id);
        $result = mysqli_stmt_execute($sql);

        mysqli_stmt_close($sql);

        return $result;
    }
}