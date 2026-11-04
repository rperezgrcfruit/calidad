<?php



class ControllerEmpresa{
    

    public function GetEmpresa($conexion){
        $empresa = new ModelEmpresa($conexion);
        $result = $empresa->GetEmpresa();

       return $result;
    }
}