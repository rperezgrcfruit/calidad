<?php

class ModelRecepcionCalidad{


    public function GetRecepciones($conexion, $empresa, $temporada){
        $sql = mysqli_prepare($conexion, 'SELECT recepcion_calidad.lote, recepcion_calidad.fecha_recepcion, recepcion_calidad.fecha_cosecha, recepcion_calidad.hora_control, recepcion_calidad.fecha_inspeccion, recepcion_calidad.cantidad_frutos, recepcion_granel.especie, recepcion_granel.variedad, cantidad_frutos, recepcion_granel.productor,
        recepcion_granel.cant_bins, recepcion_granel.guia, productores.r_social, productores.rut, productores.ggn
         FROM recepcion_calidad INNER JOIN recepcion_granel ON recepcion_calidad.lote = recepcion_granel.lote INNER JOIN productores ON recepcion_granel.productor = productores.codigo WHERE recepcion_calidad.cliente = ?
        and recepcion_granel.exportadora = ? and recepcion_calidad.temporada = ? and recepcion_granel.temporada = ? and productores.empresa = ? ORDER BY recepcion_calidad.lote');
        mysqli_stmt_bind_param($sql, 'sssss',$empresa, $empresa, $temporada, $temporada, $empresa);
        mysqli_stmt_execute($sql);

        $result = mysqli_stmt_get_result($sql);

        if ($result->num_rows > 0){
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

            mysqli_stmt_close($sql);

            return $data;

            
        }
    }

    public function ContarRecepciones($conexion, $empresa, $temporada){
        $sql = mysqli_prepare($conexion, 'SELECT COUNT(*) AS total
         FROM recepcion_calidad INNER JOIN recepcion_granel ON recepcion_calidad.lote = recepcion_granel.lote INNER JOIN productores ON recepcion_granel.productor = productores.codigo WHERE recepcion_calidad.cliente = ?
        and recepcion_granel.exportadora = ? and recepcion_calidad.temporada = ? and recepcion_granel.temporada = ? and productores.empresa = ?');
        mysqli_stmt_bind_param($sql, 'sssss', $empresa, $empresa, $temporada, $temporada, $empresa);
        mysqli_stmt_execute($sql);

        $result = mysqli_stmt_get_result($sql);
        $total = 0;

        if ($row = mysqli_fetch_assoc($result)) {
            $total = (int) $row['total'];
        }

        mysqli_stmt_close($sql);

        return $total;
    }

    public function GetRecepcionesPaginadas($conexion, $empresa, $temporada, $limit, $offset){
        $sql = mysqli_prepare($conexion, 'SELECT recepcion_calidad.lote, recepcion_calidad.fecha_recepcion, recepcion_calidad.fecha_cosecha, recepcion_calidad.hora_control, recepcion_calidad.fecha_inspeccion, recepcion_calidad.cantidad_frutos, recepcion_granel.especie, recepcion_granel.variedad, cantidad_frutos, recepcion_granel.productor,
        recepcion_granel.cant_bins, recepcion_granel.guia, productores.r_social, productores.rut, productores.ggn
         FROM recepcion_calidad INNER JOIN recepcion_granel ON recepcion_calidad.lote = recepcion_granel.lote INNER JOIN productores ON recepcion_granel.productor = productores.codigo WHERE recepcion_calidad.cliente = ?
        and recepcion_granel.exportadora = ? and recepcion_calidad.temporada = ? and recepcion_granel.temporada = ? and productores.empresa = ? ORDER BY recepcion_calidad.lote LIMIT ? OFFSET ?');

        $limit  = (int) $limit;
        $offset = (int) $offset;

        mysqli_stmt_bind_param($sql, 'sssssii', $empresa, $empresa, $temporada, $temporada, $empresa, $limit, $offset);
        mysqli_stmt_execute($sql);

        $result = mysqli_stmt_get_result($sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_stmt_close($sql);

        return $data;
    }

    public function GetRecepcion($conexion, $empresa, $temporada, $lote){
        $sql = mysqli_prepare($conexion, 'SELECT recepcion_calidad.lote, recepcion_calidad.fecha_recepcion, recepcion_calidad.fecha_cosecha, recepcion_calidad.hora_control, recepcion_calidad.fecha_inspeccion, recepcion_calidad.cantidad_frutos, recepcion_granel.especie, recepcion_granel.variedad, cantidad_frutos, recepcion_granel.productor,
        recepcion_granel.cant_bins, recepcion_granel.guia, productores.r_social, productores.rut, productores.ggn, recepcion_calidad.observaciones, recepcion_calidad.recepcionista
         FROM recepcion_calidad INNER JOIN recepcion_granel ON recepcion_calidad.lote = recepcion_granel.lote INNER JOIN productores ON recepcion_granel.productor = productores.codigo WHERE recepcion_calidad.cliente = ? and recepcion_calidad.lote = ?
        and recepcion_granel.exportadora = ? and recepcion_calidad.temporada = ? and recepcion_granel.temporada = ? and productores.empresa = ? ORDER BY recepcion_calidad.lote');
        mysqli_stmt_bind_param($sql, 'ssssss',$empresa, $lote, $empresa, $temporada, $temporada, $empresa);
        mysqli_stmt_execute($sql);

        $result = mysqli_stmt_get_result($sql);

        if ($result->num_rows > 0){
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

            mysqli_stmt_close($sql);

            return $data;

            
        }
    }

    
    public function InsertRecepcion($conexion, $datos){
    $empresa = $_SESSION['empresa'];
    $temporada = $_SESSION['temporada'];
    $user = $_SESSION['username'];

        $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad (fecha_recepcion, fecha_cosecha, fecha_inspeccion, cliente, temporada,
         lote, hora_control, recepcionista, cantidad_frutos) VALUES (?,?,?,?,?,?,?,?,?)');
        mysqli_stmt_bind_param($sql, 'sssssssss',$datos['fecha_recepcion'], $datos['dateFechaCosecha'],$datos['dateFechaInspeccion'],
        $empresa, $temporada, $datos['txtlote'], $datos['horaControl'], $user, $datos['txtFrutos'],
        );
        $result = mysqli_stmt_execute($sql);
        mysqli_stmt_close($sql);

        return $result;
        
    }

    public function ActualizarObservaciones($conexion, $lote, $observaciones){
        $empresa = $_SESSION['empresa'];
        $temporada = $_SESSION['temporada'];

        $sql = mysqli_prepare($conexion, 'UPDATE recepcion_calidad SET observaciones = ? WHERE lote = ? and cliente = ? and temporada = ?');
        mysqli_stmt_bind_param($sql, 'ssss', $observaciones, $lote, $empresa, $temporada);
        $result = mysqli_stmt_execute($sql);
        mysqli_stmt_close($sql);

        return $result;
    }

    public function BuscarLote($conexion, $lote){
        $empresa = $_SESSION['empresa'];
        $temporada = $_SESSION['temporada'];
    
            $sql = mysqli_prepare($conexion, 'SELECT * FROM recepcion_granel INNER JOIN productores ON recepcion_granel.productor = productores.codigo WHERE recepcion_granel.lote = ? and recepcion_granel.exportadora = ? and recepcion_granel.temporada = ? and productores.empresa = ?');
            mysqli_stmt_bind_param($sql, 'ssss', $lote, $empresa, $temporada, $empresa);
            mysqli_stmt_execute($sql);
            
            $result = mysqli_stmt_get_result($sql);
            
            if ($result->num_rows > 0 ){
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return $data;
            }else{
                return false;
            }
            mysqli_stmt_close($sql);
    }

    public function ValidarLoteRecepcion($conexion, $lote){
        $empresa = $_SESSION['empresa'];
        $temporada = $_SESSION['temporada'];
    
            $sql = mysqli_prepare($conexion, 'SELECT * FROM recepcion_calidad WHERE lote = ? and cliente = ? and temporada = ?');
            mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
            mysqli_stmt_execute($sql);
            
            $result = mysqli_stmt_get_result($sql);
            
            if ($result->num_rows > 0) {
                return true;
            } else {
                return false;
            }
            
            
            mysqli_stmt_close($sql);
    }


    public function DeleteRecepcion($conexion, $lote){
        $empresa = $_SESSION['empresa'];
        $temporada = $_SESSION['temporada'];

        $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad WHERE lote = ? and cliente = ? and temporada = ?');
        mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
        mysqli_stmt_execute($sql);

        $result = mysqli_stmt_affected_rows($sql);

        mysqli_stmt_close($sql);

        return $result;
    }


    public function InsertRecepcionCalibre($conexion, $datos){
        $empresa = $_SESSION['empresa'];
        $temporada = $_SESSION['temporada'];
        
            $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad_calibre (lote, calibre, cantidad, porcentaje, empresa, temporada) VALUES (?,?,?,?,?,?)');
            mysqli_stmt_bind_param($sql, 'ssisss',$datos['lote'], $datos['calibre'],$datos['cantidad'], $datos['porcentaje'], $empresa, $temporada);
            $result = mysqli_stmt_execute($sql);
            mysqli_stmt_close($sql);
    
            return $result;
            
        }


        public function GetRecepcionCalidadCalibre($conexion, $lote){
            $empresa = $_SESSION['empresa'];
            $temporada = $_SESSION['temporada'];

            $sql = mysqli_prepare($conexion, "SELECT * FROM recepcion_calidad_calibre WHERE lote = ? and empresa = ? and temporada = ? ORDER BY calibre");
            mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
            mysqli_stmt_execute($sql);

            $result = mysqli_stmt_get_result($sql);

            if ($result->num_rows > 0) {
                $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return $datos;
            } else {
                return false;
            }
            
        }

        public function DeleteRecepcionCalibre($conexion, $id){
    
            $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad_calibre WHERE id = ?');
            mysqli_stmt_bind_param($sql, 's', $id);
            mysqli_stmt_execute($sql);
    
            $result = mysqli_stmt_affected_rows($sql);
    
            mysqli_stmt_close($sql);
    
            return $result;
        }
    
        public function ObtenerDefectos($conexion, $tipoDefecto){
         
            $sql = mysqli_prepare($conexion, "SELECT * FROM defectos WHERE tipo_defecto = ?");
            mysqli_stmt_bind_param($sql, 's', $tipoDefecto);
            mysqli_stmt_execute($sql);

            $result = mysqli_stmt_get_result($sql);

            if ($result->num_rows > 0) {
                $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return $datos;
            } else {
                return false;
            }
            
        }

        
        public function GetRecepcionCalidadDefecto($conexion, $lote){
            $empresa = $_SESSION['empresa'];
            $temporada = $_SESSION['temporada'];

            $sql = mysqli_prepare($conexion, "SELECT * FROM recepcion_calidad_defectos WHERE lote = ? and empresa = ? and temporada = ?");
            mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
            mysqli_stmt_execute($sql);

            $result = mysqli_stmt_get_result($sql);

            if ($result->num_rows > 0) {
                $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return $datos;
            } else {
                return false;
            }
            
        }

        public function InsertRecepcionDefecto($conexion, $datos){
            $empresa = $_SESSION['empresa'];
            $temporada = $_SESSION['temporada'];
            
                $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad_defectos (lote, tipo_defecto, defecto, cantidad, porcentaje, empresa, temporada) VALUES (?,?,?,?,?,?,?)');
                mysqli_stmt_bind_param($sql, 'sssiiss',$datos['lote'], $datos['tipoDefecto'],$datos['defecto'],$datos['cantidad'], $datos['porcentaje'], $empresa, $temporada);
                $result = mysqli_stmt_execute($sql);
                mysqli_stmt_close($sql);
        
                return $result;
                
            }

            public function DeleteRecepcionDefecto($conexion, $id){
    
                $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad_defectos WHERE id = ?');
                mysqli_stmt_bind_param($sql, 's', $id);
                mysqli_stmt_execute($sql);
        
                $result = mysqli_stmt_affected_rows($sql);
        
                mysqli_stmt_close($sql);
        
                return $result;
            }

            public function saveRouteImageRecepcionCalidad($conexion,$route, $lote, $nombre){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];

                $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad_imagenes(lote, nombre, ruta, empresa, temporada)
                                                            VALUES(?,?,?,?,?)');
                mysqli_stmt_bind_param($sql, 'sssss', $lote, $nombre, $route, $empresa, $temporada);
                $result = mysqli_stmt_execute($sql);
        
                mysqli_stmt_close($sql);
        
                return $result;

            }


            public function GetImagesRecepcionCalidad($conexion, $lote){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
    
                $sql = mysqli_prepare($conexion, "SELECT * FROM recepcion_calidad_imagenes WHERE lote = ? and empresa = ? and temporada = ?");
                mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
                mysqli_stmt_execute($sql);
    
                $result = mysqli_stmt_get_result($sql);
    
                if ($result->num_rows > 0) {
                    $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    return $datos;
                } else {
                    return false;
                }
                
            }

            public function GetRecepcionCalidadSemilla($conexion, $lote){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
    
                $sql = mysqli_prepare($conexion, "SELECT * FROM recepcion_calidad_semillas WHERE lote = ? and empresa = ? and temporada = ? ORDER BY id");
                mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
                mysqli_stmt_execute($sql);
    
                $result = mysqli_stmt_get_result($sql);
    
                if ($result->num_rows > 0) {
                    $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    return $datos;
                } else {
                    return false;
                }
                
            }

            public function InsertRecepcionSemilla($conexion, $datos){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
                
                    $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad_semillas (lote,semillas, cantidad, porcentaje, empresa, temporada) VALUES (?,?,?,?,?,?)');
                    mysqli_stmt_bind_param($sql, 'ssiiss',$datos['lote'], $datos['semillas'], $datos['cantidad'], $datos['porcentaje'], $empresa, $temporada);
                    $result = mysqli_stmt_execute($sql);
                    mysqli_stmt_close($sql);
            
                    return $result;
                    
                }
    
            public function DeleteRecepcionSemilla($conexion, $id){
        
                $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad_semillas WHERE id = ?');
                mysqli_stmt_bind_param($sql, 's', $id);
                mysqli_stmt_execute($sql);
        
                $result = mysqli_stmt_affected_rows($sql);
        
                mysqli_stmt_close($sql);
        
                return $result;
            }

            public function GetRecepcionCalidadJugo($conexion, $lote){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
    
                $sql = mysqli_prepare($conexion, "SELECT * FROM recepcion_calidad_jugo WHERE lote = ? and empresa = ? and temporada = ? ORDER BY id");
                mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
                mysqli_stmt_execute($sql);
    
                $result = mysqli_stmt_get_result($sql);
    
                if ($result->num_rows > 0) {
                    $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    return $datos;
                } else {
                    return false;
                }
                
            }

            public function InsertRecepcionJugo($conexion, $datos){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
                
                    $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad_jugo (lote, porcentaje, acidez, brix, peso_fruta, peso_jugo,
                    gasto_naoh, calculos, empresa, temporada) VALUES (?,?,?,?,?,?,?,?,?,?)');
                    mysqli_stmt_bind_param($sql, 'sdddddddss',$datos['lote'], $datos['porcentaje'],$datos['acidez'], $datos['brix'],
                    $datos['pesoFruta'], $datos['pesoJugo'], $datos['gastoNaoh'], $datos['calculos'], $empresa, $temporada);
                    $result = mysqli_stmt_execute($sql);
                    mysqli_stmt_close($sql);
            
                    return $result;
                    
                }
    
            public function DeleteRecepcionJugo($conexion, $id){
        
                $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad_jugo WHERE id = ?');
                mysqli_stmt_bind_param($sql, 's', $id);
                mysqli_stmt_execute($sql);
        
                $result = mysqli_stmt_affected_rows($sql);
        
                mysqli_stmt_close($sql);
        
                return $result;
            }

             public function DeletePesoCitrico($conexion, $id){
        
                $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad_pesos_citricos WHERE id = ?');
                mysqli_stmt_bind_param($sql, 's', $id);
                mysqli_stmt_execute($sql);
        
                $result = mysqli_stmt_affected_rows($sql);
        
                mysqli_stmt_close($sql);
        
                return $result;
            }

            public function DeleteFoto($conexion, $id){
                $sql = mysqli_prepare($conexion, 'DELETE FROM recepcion_calidad_imagenes WHERE id = ?');
                mysqli_stmt_bind_param($sql, 's', $id);
                mysqli_stmt_execute($sql);
        
                $result = mysqli_stmt_affected_rows($sql);
        
                mysqli_stmt_close($sql);
        
                return $result;
                } 


                public function GetRecepcionCalidadPesosCitricos($conexion, $lote){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
    
                $sql = mysqli_prepare($conexion, "SELECT * FROM recepcion_calidad_pesos_citricos WHERE lote = ? and empresa = ? and temporada = ? ORDER BY id");
                mysqli_stmt_bind_param($sql, 'sss', $lote, $empresa, $temporada);
                mysqli_stmt_execute($sql);
    
                $result = mysqli_stmt_get_result($sql);
    
                if ($result->num_rows > 0) {
                    $datos = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    return $datos;
                } else {
                    return false;
                }


               }

               public function InsertRecepcionPesoCitrico($conexion, $datos){
                $empresa = $_SESSION['empresa'];
                $temporada = $_SESSION['temporada'];
                
                    $sql = mysqli_prepare($conexion, 'INSERT INTO recepcion_calidad_pesos_citricos (lote, cantidad_frutos, medicion_1, medicion_2, promedio_presion, empresa, temporada) VALUES (?,?,?,?,?,?,?)');
                    mysqli_stmt_bind_param($sql, 'sdddsds',$datos['lote'], $datos['cantidadFrutos'], $datos['medicion1'], $datos['medicion2'], $datos['promedio'], $empresa, $temporada);
                    $result = mysqli_stmt_execute($sql);
                    mysqli_stmt_close($sql);
            
                    return $result;
                    
                }

           
}