<?php

class ControllerDefectos
{
    private $model;

    public function __construct()
    {
        $this->model = new ModelDefectos();
    }

    /**
     * Listado y mantenimiento de defectos
     */
    public function index($conexion)
    {
        $mensaje = '';
        $tipoMensaje = 'success';

        // Eliminar defecto (GET ?delete=ID)
        if (isset($_GET['delete']) && $_GET['delete'] !== '') {
            $id = (int) $_GET['delete'];
            if ($id > 0) {
                $ok = $this->model->deleteDefecto($conexion, $id);
                $mensaje = $ok ? 'Defecto eliminado correctamente' : 'No se pudo eliminar el defecto';
                $tipoMensaje = $ok ? 'success' : 'danger';
            }
        }

        // Insertar defecto (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo   = trim($_POST['cbTipoDefecto'] ?? '');
            $defecto = trim($_POST['txtDefecto'] ?? '');

            if ($tipo === '' || $defecto === '') {
                $mensaje = 'Debe completar el tipo y la descripción del defecto';
                $tipoMensaje = 'danger';
            } elseif (mb_strlen($defecto) > 150) {
                $mensaje = 'La descripción no puede superar los 150 caracteres';
                $tipoMensaje = 'danger';
            } else {
                $ok = $this->model->insertDefecto($conexion, $tipo, $defecto);
                $mensaje = $ok ? 'Defecto agregado correctamente' : 'No se pudo agregar el defecto';
                $tipoMensaje = $ok ? 'success' : 'danger';
            }

            // Evitar reenvío del formulario al recargar
            header('Location: ' . BASE_URL . '/recepcion-calidad/defectos?msg=' . urlencode($mensaje) . '&tipo=' . $tipoMensaje);
            exit;
        }

        if (isset($_GET['msg'])) {
            $mensaje = substr((string) $_GET['msg'], 0, 200);
            $tipoMensaje = ($_GET['tipo'] ?? 'success') === 'danger' ? 'danger' : 'success';
        }

        $Defectos = $this->model->GetAllDefectos($conexion);
        $tipoDefectos = $this->model->GetAllTipoDefectos($conexion);

        include_once RUTA . '/calidad/view/calidad/defectos.php';
    }

    /**
     * Listado y mantenimiento de tipos de defectos
     */
    public function tipos($conexion)
    {
        $mensaje = '';
        $tipoMensaje = 'success';

        // Eliminar tipo de defecto (GET ?delete=ID)
        if (isset($_GET['delete']) && $_GET['delete'] !== '') {
            $id = (int) $_GET['delete'];
            if ($id > 0) {
                $ok = $this->model->deleteTipoDefecto($conexion, $id);
                $mensaje = $ok ? 'Tipo de defecto eliminado correctamente' : 'No se pudo eliminar el tipo de defecto';
                $tipoMensaje = $ok ? 'success' : 'danger';
            }
        }

        // Insertar tipo de defecto (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipoDefecto = trim($_POST['txtTipoDefecto'] ?? '');

            if ($tipoDefecto === '') {
                $mensaje = 'Debe ingresar el nombre del tipo de defecto';
                $tipoMensaje = 'danger';
            } elseif (mb_strlen($tipoDefecto) > 60) {
                $mensaje = 'El nombre no puede superar los 60 caracteres';
                $tipoMensaje = 'danger';
            } else {
                $ok = $this->model->insertTipoDefecto($conexion, $tipoDefecto);
                $mensaje = $ok ? 'Tipo de defecto agregado correctamente' : 'No se pudo agregar el tipo de defecto';
                $tipoMensaje = $ok ? 'success' : 'danger';
            }

            header('Location: ' . BASE_URL . '/recepcion-calidad/tipos-defectos?msg=' . urlencode($mensaje) . '&tipo=' . $tipoMensaje);
            exit;
        }

        if (isset($_GET['msg'])) {
            $mensaje = substr((string) $_GET['msg'], 0, 200);
            $tipoMensaje = ($_GET['tipo'] ?? 'success') === 'danger' ? 'danger' : 'success';
        }

        $tipoDefectos = $this->model->GetAllTipoDefectos($conexion);

        include_once RUTA . '/calidad/view/calidad/tipo-defectos.php';
    }
}