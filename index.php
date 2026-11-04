<?php

session_start();

require_once 'config/config.php';
require_once 'config/pagination.php';
require_once 'config/db.php';
require_once 'controller/ControllerMainCalidad.php';
require_once 'controller/ControllerLogin.php';
require_once 'controller/ControllerEmpresa.php';
require_once 'model/ModelEmpresa.php';
require_once 'model/ModelLogin.php';
require_once 'controller/ControllerTemporada.php';
require_once 'model/ModelTemporada.php';
require_once 'controller/ControllerRecepcionCalidad.php';
require_once 'model/ModelRecepcionCalidad.php';
require_once 'model/ModelDefectos.php';
require_once 'controller/ControllerDefectos.php';
require_once 'model/ModeloBodegaQuimicos.php';
require_once 'controller/ControllerBodegaQuimicos.php';

$recepcionCalidad = new ControllerRecepcionCalidad();
$defectos = new ControllerDefectos();
$bodegaQuimicos = new ControllerBodegaQuimicos();

// Obtener la URL de la solicitud
$url = parse_url($_SERVER['REQUEST_URI']);
$request_uri = $url;
$request_uri = preg_replace('/\/$/', '', $request_uri);

// Ruta actual del archivo (index.php está en /calidad/)
$current_path = '/calidad';

// Si la URL es solo '/calidad' o '/', redirigir al login o dashboard según sesión
if (!isset($_SESSION['username'])) {
    // Sin sesión - mostrar login
    $empresa = new ControllerEmpresa();
    $result = $empresa->GetEmpresa($conexion);

    $temporada = new ControllerTemporada();
    $resultT = $temporada->GetTemporada($conexion);

    // Incluir vista del login directamente
    include_once 'view/login/login.php';
    exit;
}

// Si está loggeado y va a la raíz, ir a la selección de módulo
if ($request_uri['path'] === '' || $request_uri['path'] === '/calidad') {
    $request_uri['path'] = '/calidad/seleccion-modulo';
}

switch ($request_uri['path']) {
    case '/calidad/seleccion-modulo':
        include_once 'view/seleccion-modulo.php';
        break;
    
    case '/calidad/main':
        include_once 'view/main/main.php';
        break;

    // =======================
    // RECEPCIÓN CALIDAD
    // =======================
    case '/calidad/recepcion-calidad':
        $index = $recepcionCalidad->index($conexion);
        break;

    case '/calidad/recepcion-calidad/nueva-recepcion':
        include_once 'view/calidad/recepcion-calidad-nuevo.php';
        break;

    case '/calidad/recepcion-calidad/defectos':
        $defectos->index($conexion);
        break;

    case '/calidad/recepcion-calidad/tipos-defectos':
        $defectos->tipos($conexion);
        break;

    case '/calidad/recepcion-calidad/eliminar-recepcion':
        if (isset($request_uri['query']) && $request_uri['query'] != null) {
            $nrecepcion = $request_uri['query'];
            $delete = $recepcionCalidad->DeleteRecepcion($conexion, $nrecepcion);
            if ($delete) {
                header('Location: ' . BASE_URL . '/recepcion-calidad');
            }
        }
        break;

    case '/calidad/recepcion-calidad/ver-recepcion':
        $url = $_SERVER['QUERY_STRING'];

        parse_str($url, $output);
        $lote = $output['lote'];

        $validarLote = $recepcionCalidad->ValidarLoteRecepcion($conexion, $lote);

        if ($validarLote) {
            $showRecepcion = $recepcionCalidad->GetShowRecepcion($conexion, $lote);
        } else {
            header('Location: ' . BASE_URL . '/recepcion-calidad');
        }

        break;

    case '/calidad/recepcion-calidad/ingreso':
        if (isset($_POST) && !empty($_POST) && $_POST != null) {
            $recepcion = new ControllerRecepcionCalidad();
            $ingreso = $recepcion->InsertRecepcion($conexion, $_POST);

            if ($ingreso) {
                header('location:/calidad/recepcion-calidad');
            } else {
                echo json_encode([
                    'respuesta' => '0',
                    'mensaje' => 'Lote no existe o ya se encuentra en otra recepcion de calidad'
                ]);
            }
        } else {
            header('Location: ' . BASE_URL . '/recepcion-calidad');
        }
        break;

    // =======================
    // BODEGA QUIMICOS
    // =======================
    case '/calidad/bodega-quimicos':
        $bodegaQuimicos->dashboard($conexion);
        break;
    
    case '/calidad/bodega-quimicos/recepciones':
        $bodegaQuimicos->listaRecepciones($conexion);
        break;
    
    case '/calidad/bodega-quimicos/recepciones/nuevo':
        $bodegaQuimicos->nuevoRegistro($conexion);
        break;
    
    case '/calidad/bodega-quimicos/recepciones/guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bodegaQuimicos->guardarRecepcion($conexion);
        }
        break;
    
    case '/calidad/bodega-quimicos/recepciones/detalle':
        $bodegaQuimicos->verDetalle($conexion);
        break;

    case '/calidad/bodega-quimicos/recepciones/guia/subir':
        $bodegaQuimicos->subirGuia($conexion);
        break;

    case '/calidad/bodega-quimicos/recepciones/guia/ver':
        $bodegaQuimicos->verGuia($conexion);
        break;

    case '/calidad/bodega-quimicos/quimicos':
        $bodegaQuimicos->listaQuimicos($conexion);
        break;

    case '/calidad/bodega-quimicos/quimicos/guardar':
        $bodegaQuimicos->guardarQuimico($conexion);
        break;

    case '/calidad/bodega-quimicos/buscar':
        $codigo = $_GET['codigo'] ?? '';
        if ($codigo) {
            $quimico = $bodegaQuimicos->buscarQuimico($conexion, $codigo);
            header('Content-Type: application/json');
            echo json_encode($quimico ?: ['error' => 'No encontrado']);
        }
        break;
    
    case '/calidad/bodega-quimicos/bodegas':
        $bodegaQuimicos->listaBodegas($conexion);
        break;

    case '/calidad/bodega-quimicos/bodegas/guardar':
        $bodegaQuimicos->guardarBodega($conexion);
        break;
    
    case '/calidad/bodega-quimicos/proveedores':
        $bodegaQuimicos->listaProveedores($conexion);
        break;

    case '/calidad/bodega-quimicos/proveedores/guardar':
        $bodegaQuimicos->guardarProveedor($conexion);
        break;

    // =======================
    // CIERRE DE SESIÓN
    // =======================
    case '/calidad/close':
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        break;

    default:
        header('Location: ' . BASE_URL . '/recepcion-calidad');
        break;
}