<?php

class ControllerTemporada{


    public function GetTemporada($conexionP){
     $temporada = new ModelTemporada();
     $result = $temporada->GetTemporada($conexionP);
     return $result;
    }
}