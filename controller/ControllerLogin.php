<?php

class ControllerLogin
{
    /**
     * Login del módulo Recepción Calidad: empresa + temporada + usuario + clave
     */
    public function calidad($conexion)
    {
        $errores = [];
        $old = [];
        $csrf_token = generateCSRFToken();

        // Si ya está autenticado en este módulo, no mostrar el formulario
        if (isset($_SESSION['username']) && ($_SESSION['modulo'] ?? '') === 'calidad'
            && !empty($_SESSION['empresa']) && !empty($_SESSION['temporada'])) {
            header('Location: ' . BASE_URL . '/recepcion-calidad');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST;

            $usuario   = trim((string) ($_POST['username'] ?? ''));
            $password  = (string) ($_POST['password'] ?? '');
            $empresa   = trim((string) ($_POST['empresa'] ?? ''));
            $temporada = trim((string) ($_POST['temporada'] ?? ''));

            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                $errores[] = 'Sesión expirada. Intente nuevamente.';
            } elseif ($usuario === '' || $password === '' || $empresa === '' || $temporada === '') {
                $errores[] = 'Debe completar todos los campos.';
            } elseif (strlen($usuario) > 50 || strlen($password) > 100) {
                $errores[] = 'Datos inválidos.';
            } else {
                // Validar empresa activa y temporada existente
                $modeloEmpresa = new ModelEmpresa($conexion);

                if (!$modeloEmpresa->existeActiva($empresa)) {
                    $errores[] = 'La empresa seleccionada no es válida o está inactiva.';
                } else {
                    $temporadaOk = false;
                    foreach ((new ModelTemporada())->GetTemporada($conexion) ?: [] as $t) {
                        if ((string) $t['temporada'] === $temporada) {
                            $temporadaOk = true;
                            break;
                        }
                    }

                    if (!$temporadaOk) {
                        $errores[] = 'La temporada seleccionada no es válida.';
                    } else {
                        $validar = (new ModelLogin())->ValidarLogin($conexion, $usuario);

                        if (!$validar || $validar['password'] !== md5($password)) {
                            $errores[] = 'Usuario o contraseña incorrectos.';
                        } else {
                            session_regenerate_id(true);

                            $_SESSION['username']  = $validar['nombre'];
                            $_SESSION['empresa']   = $empresa;
                            $_SESSION['temporada'] = $temporada;
                            $_SESSION['modulo']    = 'calidad';
                            $_SESSION['ip']        = $_SERVER['REMOTE_ADDR'] ?? '';
                            $_SESSION['user_agent'] = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

                            header('Location: ' . BASE_URL . '/recepcion-calidad');
                            exit;
                        }
                    }
                }
            }
        }

        $empresas = (new ModelEmpresa($conexion))->GetEmpresa() ?: [];
        $temporadas = (new ModelTemporada())->GetTemporada($conexion) ?: [];

        include RUTA . '/calidad/view/login/login-calidad.php';
    }

    /**
     * Login del módulo Bodega Químicos: solo usuario + clave
     * (no depende de empresa ni temporada)
     */
    public function quimicos($conexion)
    {
        $errores = [];
        $old = [];
        $csrf_token = generateCSRFToken();

        if (isset($_SESSION['username']) && ($_SESSION['modulo'] ?? '') === 'quimicos') {
            header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST;

            $usuario  = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                $errores[] = 'Sesión expirada. Intente nuevamente.';
            } elseif ($usuario === '' || $password === '') {
                $errores[] = 'Debe ingresar usuario y contraseña.';
            } elseif (strlen($usuario) > 50 || strlen($password) > 100) {
                $errores[] = 'Datos inválidos.';
            } else {
                $validar = (new ModelLogin())->ValidarLogin($conexion, $usuario);

                if (!$validar || $validar['password'] !== md5($password)) {
                    $errores[] = 'Usuario o contraseña incorrectos.';
                } else {
                    session_regenerate_id(true);

                    $_SESSION['username']   = $validar['nombre'];
                    $_SESSION['modulo']     = 'quimicos';
                    $_SESSION['ip']         = $_SERVER['REMOTE_ADDR'] ?? '';
                    $_SESSION['user_agent'] = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

                    // Químicos no depende de empresa ni temporada
                    unset($_SESSION['empresa'], $_SESSION['temporada']);

                    header('Location: ' . BASE_URL . '/bodega-quimicos/recepciones');
                    exit;
                }
            }
        }

        include RUTA . '/calidad/view/login/login-quimicos.php';
    }
}
