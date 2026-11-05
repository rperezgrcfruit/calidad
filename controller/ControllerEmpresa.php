<?php



class ControllerEmpresa{
    

    public function GetEmpresa($conexion){
        $empresa = new ModelEmpresa($conexion);
        $result = $empresa->GetEmpresa();

       return $result;
    }

    /**
     * Cambia la empresa (cliente) y/o temporada activa en la sesión,
     * sin cerrar sesión. Valida CSRF, empresa activa y URL de retorno.
     */
    public function cambiarContexto($conexion)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['username'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/seleccion-modulo');
            exit;
        }

        // URL de retorno segura (solo rutas internas de la aplicación)
        $volver = (string) ($_POST['volver'] ?? '/calidad/seleccion-modulo');
        if (!preg_match('#^/calidad/[A-Za-z0-9\-_/?=&%\.]*$#', $volver) || strpos($volver, '//') !== false) {
            $volver = '/calidad/seleccion-modulo';
        }

        $mensaje = '';
        $tipo = 'success';

        if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
            $mensaje = 'Sesión expirada. Intente nuevamente.';
            $tipo = 'danger';
        } else {
            $empresa = trim((string) ($_POST['empresa'] ?? ''));
            $temporada = trim((string) ($_POST['temporada'] ?? ''));

            $modeloEmpresa = new ModelEmpresa($conexion);

            if ($empresa === '' || !$modeloEmpresa->existeActiva($empresa)) {
                $mensaje = 'La empresa seleccionada no es válida o está inactiva.';
                $tipo = 'danger';
            } else {
                // Validar temporada contra el catálogo
                $temporadas = (new ModelTemporada())->GetTemporada($conexion) ?: [];
                $temporadaValida = '';
                foreach ($temporadas as $t) {
                    if ((string) $t['temporada'] === $temporada) {
                        $temporadaValida = (string) $t['temporada'];
                        break;
                    }
                }

                if ($temporadaValida === '') {
                    $mensaje = 'La temporada seleccionada no es válida.';
                    $tipo = 'danger';
                } else {
                    // Nuevo id de sesión al cambiar el contexto
                    session_regenerate_id(true);

                    $_SESSION['empresa'] = $empresa;
                    $_SESSION['temporada'] = $temporadaValida;
                    $_SESSION['contexto_cambiado'] = date('H:i');

                    $mensaje = 'Cliente actualizado: ' . $empresa . ' · Temporada ' . $temporadaValida;
                }
            }
        }

        $_SESSION['flash_contexto'] = ['mensaje' => $mensaje, 'tipo' => $tipo];

        header('Location: ' . BASE_URL . $volver);
        exit;
    }
}