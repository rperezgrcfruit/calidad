<?php

class ControllerRecepcionCalidad
{


    public function index($conexion)
    {
        $empresa = $_SESSION['empresa'];
        $temporada = $_SESSION['temporada'];

        $model = new ModelRecepcionCalidad();
        $result = $model->GetRecepciones($conexion, $empresa, $temporada);

        include_once RUTA . '/calidad/view/calidad/recepcion_calidad.php';
    }


    public function GetAllRecepiones($conexion, $empresa, $temporada)
    {
        $model = new ModelRecepcionCalidad();
        $result = $model->GetRecepciones($conexion, $empresa, $temporada);

        return $result;
    }


    public function InsertRecepcion($conexion, $datos)
    {
        $model = new ModelRecepcionCalidad();

        $validarLote = $model->ValidarLoteRecepcion($conexion, $datos['txtlote']);

        if ($validarLote) {

            return false;
            exit;
        } else {

            $insert = $model->InsertRecepcion($conexion, $datos);

            return $insert;
        }
    }

    public function DeleteRecepcion($conexion, $nrecepcion)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeleteRecepcion($conexion, $nrecepcion);

        return $delete;
    }

    public function GetShowRecepcion($conexion, $lote)
    {
        $model = new ModelRecepcionCalidad();
        $calibres = $model->GetRecepcionCalidadCalibre($conexion, $lote);    
        $defectos = $model->GetRecepcionCalidadDefecto($conexion, $lote);
        $images = $model->GetImagesRecepcionCalidad($conexion, $lote);
        $jugos = $model->GetRecepcionCalidadJugo($conexion, $lote);
        $semillas = $model->GetRecepcionCalidadSemilla($conexion, $lote);
        $pesosCitricos = $model->GetRecepcionCalidadPesosCitricos($conexion, $lote);
      
        include_once RUTA . '/calidad/view/calidad/recepcion-calidad-view.php';
    }

    public function GetRecepcionCalidadCalibre($conexion, $lote){
        $model = new ModelRecepcionCalidad();
        $calibres = $model->GetRecepcionCalidadCalibre($conexion, $lote);    
        
        return $calibres;
    }

    public function GetRecepcionCalidadDefectos($conexion, $lote){
        $model = new ModelRecepcionCalidad();
        $defectos = $model->GetRecepcionCalidadDefecto($conexion, $lote);    
        
        return $defectos;
    }

    public function BuscarLote($conexion, $lote)
    {

        $model = new ModelRecepcionCalidad();
        $validarLote = $model->ValidarLoteRecepcion($conexion, $lote);

        //print_r($validarLote);
        if ($validarLote) {

            return false;
        } else {

            $model = new ModelRecepcionCalidad();
            $result = $model->BuscarLote($conexion, $lote);

            return $result;
        }
    }

    public function ValidarLoteRecepcion($conexion, $lote)
    {

        $model = new ModelRecepcionCalidad();
        $validarLote = $model->ValidarLoteRecepcion($conexion, $lote);

        //print_r($validarLote);
        if ($validarLote) {

            return true;
        } else {

            return false;
        }
    }

    public function InsertRecepcionCalibre($conexion, $datos)
    {
        $model = new ModelRecepcionCalidad();

        $insert = $model->InsertRecepcionCalibre($conexion, $datos);

        return $insert;
    }

    public function DeleteRecepcionCalibre($conexion, $id)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeleteRecepcionCalibre($conexion, $id);

        return $delete;
    }

    public function ObtenerDefectos($conexion, $tipoDefecto)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->ObtenerDefectos($conexion, $tipoDefecto);

        return $delete;
    }

    public function ObtenerSemillasJugo($conexion, $lote)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->ObtenerDefectos($conexion, $lote);

        return $delete;
    }

    public function InsertRecepcionDefecto($conexion, $datos)
    {
        $model = new ModelRecepcionCalidad();

        $insert = $model->InsertRecepcionDefecto($conexion, $datos);

        return $insert;
    }

    public function DeleteRecepcionDefecto($conexion, $id)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeleteRecepcionDefecto($conexion, $id);

        return $delete;
    }

    public function InsertRecepcionJugo($conexion, $datos)
    {
        $model = new ModelRecepcionCalidad();

        $insert = $model->InsertRecepcionJugo($conexion, $datos);

        return $insert;
    }

    public function DeleteRecepcionJugo($conexion, $id)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeleteRecepcionJugo($conexion, $id);

        return $delete;
    }

    public function InsertRecepcionSemilla($conexion, $datos)
    {
        $model = new ModelRecepcionCalidad();

        $insert = $model->InsertRecepcionSemilla($conexion, $datos);

        return $insert;
    }

    public function DeleteRecepcionSemilla($conexion, $id)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeleteRecepcionSemilla($conexion, $id);

        return $delete;
    }

    public function GetRecepcionCalidadSemilla($conexion, $lote)
    {
        $model = new ModelRecepcionCalidad();
        $semillas = $model->GetRecepcionCalidadSemilla($conexion, $lote);

        return $semillas;
    }

    public function GetRecepcionCalidadJugo($conexion, $lote){
        $model = new ModelRecepcionCalidad();
        $jugos = $model->GetRecepcionCalidadJugo($conexion, $lote);    
        
        return $jugos;
    }

    public function SaveImageRecepcionCalidad($conexion, $imagesData, $lote)
    {
        for ($i = 0; $i < count($imagesData['files']['name']); $i++) {
            $target_path = RUTA . "/calidad/view/assets/images/recepcion_calidad_images/";
            $ext = explode('.', basename($imagesData['files']['name'][$i]));
            if ($ext[1] <> 'png') {
                $ext[1] = 'png';
            }
            $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
            $target_path_save = substr($target_path, 30, strlen($target_path));
            
            
            if (move_uploaded_file($imagesData['files']['tmp_name'][$i], $target_path)) {

                $nombre = $imagesData['files']['tmp_name'][$i];
                $route = $target_path_save;

                $model = new ModelRecepcionCalidad();
                $saveImage = $model->saveRouteImageRecepcionCalidad($conexion,$route, $lote, $nombre);

                if ($saveImage) {
                    echo "Ruta Imagenes subidas correctamente <br />";
                }else{
                    echo "Error al subir rutas de las imagenes <br />";
                }
                
            } else {
                echo "There was an error uploading the file, please try again! <br />";
            }
        }
    }

    public function DeleteFoto($conexion, $id)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeleteFoto($conexion, $id);

        return $delete;
    }

    public function InsertRecepcionObservaciones($conexion, $lote,$observaciones)
    {
        $model = new ModelRecepcionCalidad();

        $insert = $model->ActualizarObservaciones($conexion, $lote, $observaciones);

        return $insert;
    }

    public function tipoDefectosMain($conexion){
        $model = new ModelDefectos();

        $tipoDefectos = $model->GetAllTipoDefectos($conexion);

        include_once RUTA . '/calidad/view/calidad/tipo-defectos.php';

    }

    public function ingresarTipoDefecto($conexion, $tipoDefecto) {
        $model = new ModelDefectos();

        $insert = $model->insertTipoDefecto($conexion, $tipoDefecto);

        return $insert;
    }

    public function deleteTipoDefecto($conexion, $id) {
        $model = new ModelDefectos();

        $insert = $model->deleteTipoDefecto($conexion, $id);

        return $insert;
    }

    public function DefectosMain($conexion){
        $model = new ModelDefectos();

        $Defectos = $model->GetAllDefectos($conexion);

        include_once RUTA . '/calidad/view/calidad/defectos.php';

    }

    public function ingresarDefecto($conexion, $tipoDefecto, $defecto) {
        $model = new ModelDefectos();

        $insert = $model->insertDefecto($conexion, $tipoDefecto, $defecto);

        return $insert;
    }

    public function deleteDefecto($conexion, $id) {
        $model = new ModelDefectos();

        $insert = $model->deleteDefecto($conexion, $id);

        return $insert;
    }

    public function GetRecepcionCalidadPesosCitricos($conexion, $lote){
        $model = new ModelRecepcionCalidad();
        $pesos = $model->GetRecepcionCalidadPesosCitricos($conexion, $lote);    
        
        return $pesos;
    }

    public function InsertRecepcionPresion($conexion, $datos)
    {
        $model = new ModelRecepcionCalidad();

        $insert = $model->InsertRecepcionPesoCitrico($conexion, $datos);
        $da = '<script>alert("Resultado InsertPesoCitrico: ' . $insert . '");</script>';
        return $da;
    }

    public function DeletePesoCitrico($conexion, $id)
    {
        $model = new ModelRecepcionCalidad();
        $delete = $model->DeletePesoCitrico($conexion, $id);

        return $delete;
    }

}
