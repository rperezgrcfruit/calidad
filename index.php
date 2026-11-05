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

$login           = new ControllerLogin();
$recepcionCalidad = new ControllerRecepcionCalidad();
$defectos        = new ControllerDefectos();
$bodegaQuimicos  = new ControllerBodegaQuimicos();

// URL solicitada
$request_uri = parse_url($_SERVER['REQUEST_URI']);
$request_uri = preg_replace('/\/$/', '', $request_uri);
$path = $request_uri['path'] ?? '';

// Estado de sesión
$logueado = isset($_SESSION['username']);
$modulo   = $_SESSION['modulo'] ?? '';

// ============================================================
// PANTALLA PÚBLICA: SELECTOR DE MÓDULO
// ============================================================
if ($path === '' || $path === '/calidad') {
    if ($logueado && $modulo === 'calidad') {
        header('Location: ' . BASE_URL . '/recepcion-calidad');
        exit;
    }
    if ($logueado && $modulo === 'quimicos') {
        header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
        exit;
    }

    include_once 'view/seleccion-modulo.php';
    exit;
}

// ============================================================
// LOGIN POR MÓDULO
// ============================================================
if ($path === '/calidad/login/calidad') {
    $login->calidad($conexion);
    exit;
}

if ($path === '/calidad/login/quimicos') {
    $login->quimicos($conexion);
    exit;
}

// ============================================================
// GUARDAS POR MÓDULO
// ============================================================
$esCalidad = ($path === '/calidad/main')
    || ($path === '/calidad/cambiar-empresa')
    || (strpos($path, '/calidad/recepcion-calidad') === 0);

$esQuimicos = (strpos($path, '/calidad/bodega-quimicos') === 0);

if ($esCalidad) {
    $sesionCalidadOk = $logueado
        && $modulo === 'calidad'
        && !empty($_SESSION['empresa'])
        && !empty($_SESSION['temporada']);

    if (!$sesionCalidadOk) {
        header('Location: ' . BASE_URL . '/login/calidad');
        exit;
    }
}

if ($esQuimicos) {
    $sesionQuimicosOk = $logueado && $modulo === 'quimicos';

    if (!$sesionQuimicosOk) {
        header('Location: ' . BASE_URL . '/login/quimicos');
        exit;
    }
}

// Compatibilidad con el enlace antiguo
if ($path === '/calidad/seleccion-modulo') {
    header('Location: ' . BASE_URL . '/');
    exit;
}

// ============================================================
// RUTAS
// ============================================================
switch ($path) {
    case '/calidad/main':
        include_once 'view/main/main.php';
        break;

    // =======================
    // RECEPCIÓN CALIDAD
    // =======================
    case '/calidad/recepcion-calidad':
        $recepcionCalidad->index($conexion);
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
        parse_str($_SERVER['QUERY_STRING'] ?? '', $output);
        $lote = $output['lote'] ?? '';

        $validarLote = $recepcionCalidad->ValidarLoteRecepcion($conexion, $lote);

        if ($validarLote) {
            $showRecepcion = $recepcionCalidad->GetShowRecepcion($conexion, $lote);
        } else {
            header('Location: ' . BASE_URL . '/recepcion-calidad');
        }
        break;

    case '/calidad/recepcion-calidad/ingreso':
        if (!empty($_POST)) {
            $ingreso = $recepcionCalidad->InsertRecepcion($conexion, $_POST);

            if ($ingreso) {
                header('Location: ' . BASE_URL . '/recepcion-calidad');
                exit;
            }

            echo json_encode([
                'respuesta' => '0',
                'mensaje' => 'Lote no existe o ya se encuentra en otra recepcion de calidad'
            ]);
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
    // CAMBIO DE CLIENTE (módulo Calidad)
    // =======================
    case '/calidad/cambiar-empresa':
        $controllerEmpresa = new ControllerEmpresa();
        $controllerEmpresa->cambiarContexto($conexion);
        break;

    // =======================
    // CIERRE DE SESIÓN
    // =======================
    case '/calidad/close':
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        break;

    default:
        header('Location: ' . BASE_URL . '/');
        break;
}
