<?php

class ModelTemporada{


    public function GetTemporada($conexionP){
        $sql = mysqli_query($conexionP, 'SELECT * FROM temporadas ORDER BY temporada');
        if ($sql->num_rows >0) {
            $data = mysqli_fetch_all($sql, MYSQLI_ASSOC);
            return $data;
        }
    }
}