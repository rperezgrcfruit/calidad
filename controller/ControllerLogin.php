<?php


class ControllerLogin{


    public function login($conexion, $conexionP, $user, $pass){
        $login = new ModelLogin();
        $validar = $login->ValidarLogin($conexion, $user);
        $empresa = new ControllerEmpresa();
        $result = $empresa->GetEmpresa($conexionP);

        include 'view/login/login.php';
    }
}