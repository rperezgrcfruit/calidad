<?php


class ModelLogin
{
    public function ValidarLogin($conexion, $username)
    {
        $sql = mysqli_prepare($conexion,"SELECT * FROM usuarios WHERE user = ?");
        mysqli_stmt_bind_param($sql, 's', $username);
        mysqli_stmt_execute($sql);
        $result = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_assoc($result);
        return $row;

       
    }
}