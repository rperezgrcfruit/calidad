<?php
// ============================================================
// HEADERS DE SEGURIDAD - Login page
// Se aplican automáticamente en producción (HTTPS)
// ============================================================
if (!function_exists('is_production')) {
    require_once __DIR__ . '/../../config/config.php';
}

$isProduction = is_production();

if ($isProduction) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// La sesión ya la inicia index.php; solo se inicia si se accede directo al archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF
$csrf_token = generateCSRFToken();

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === VALIDACIÓN CSRF ===
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $mensaje = "Error de seguridad. Intenta nuevamente.";
    } else {
        $usuario   = trim($_POST['username'] ?? '');
        $password  = $_POST['password'] ?? '';
        $empresa   = $_POST['empresa'] ?? '';
        $temporada = $_POST['temporada'] ?? '';

        // === VALIDACIÓN DE ENTEROS ===
        if ($usuario === '' || $password === '' || $empresa === '' || $temporada === '') {
            $mensaje = "Se deben completar todos los campos solicitados";
        } elseif (strlen($password) > 100) {
            $mensaje = "Datos inválidos";
        } elseif (strlen($usuario) > 50) {
            $mensaje = "Datos inválidos";
        } else {
            // === LOGIN ===
            $login   = new ModelLogin();
            $validar = $login->ValidarLogin($conexion, $usuario);

            if ($validar) {
                // Verificar que el usuario está activo (si hay columna status)
                // if (isset($validar['status']) && $validar['status'] !== 'active') {
                //     $mensaje = "Usuario inactivo";
                //     return;
                // }

                // Hashear contraseña con bcrypt (mejorado respecto a MD5)
                // Nota: La BD sigue usando MD5 por compatibilidad
                // Futuro: migrar a password_verify() con bcrypt
                if ($validar['password'] !== md5($password)) {
                    $mensaje = "Contraseña incorrecta";
                } else {
                    // === LOGIN EXITOSO ===
                    // Regenerar ID de sesión para prevenir session fixation
                    session_regenerate_id(true);
                    
                    $_SESSION['username']  = $validar['nombre'];
                    $_SESSION['empresa']   = $empresa;
                    $_SESSION['temporada'] = $temporada;
                    $_SESSION['ip']          = $_SERVER['REMOTE_ADDR'] ?? '';
                    $_SESSION['user_agent']  = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
                    
                    // Registro de acceso (opcional)
                    // error_log("LOGIN SUCCESS: {$usuario} from {$_SERVER['REMOTE_ADDR']} at " . date('c'));
                    
                    // Redirigir a la selección de módulo
                    header('Location: ' . BASE_URL . '/seleccion-modulo');
                    exit;
                }
            } else {
                // No revelar si el usuario existe o no
                $mensaje = "Usuario o contraseña incorrectos";
            }
        }
    }
}

// Limpiar mensaje de errores del servidor
if (isset($mensaje) && strlen($mensaje) > 200) {
    $mensaje = substr($mensaje, 0, 200);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistema de Calidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="/calidad/view/assets/css/styles.css?v=<?php echo @filemtime(RUTA . '/calidad/view/assets/css/styles.css') ?: '1'; ?>">
    <link rel="icon" href="/calidad/favicon.ico" type="image/x-icon">
</head>
<body class="login-page">
    <div id="global-spinner"></div>
    <div id="overlay"></div>

    <div class="login-wrapper">
        <div class="login-image">
            <img src="/calidad/view/assets/images/icons/grc_sf.png" alt="GRCFRUIT SPA" title="GRCFRUIT SPA - Sistema de Calidad">
        </div>

        <div class="login-form">
            <div class="login-form-inner">
                <h1>Acceso al Sistema</h1>
                <p class="subtitle">Gestión de Calidad · Ingreso de usuarios autorizados</p>

                <?php if ($mensaje !== ''): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($mensaje); ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" id="form-login" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su usuario" required maxlength="50" autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control password" id="password" name="password" placeholder="Ingrese su contraseña" required maxlength="100">
                            <span class="toggle-password"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <select class="form-select" name="empresa" id="empresa" required>
                            <option value="">Seleccionar Empresa</option>
                            <?php if (!empty($result) && is_array($result)): ?>
                                <?php foreach ($result as $emp): ?>
                                    <option value="<?php echo htmlspecialchars($emp['R_SOCIAL']); ?>"><?php echo htmlspecialchars($emp['R_SOCIAL']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="temporada">Temporada</label>
                        <select class="form-select" name="temporada" id="temporada" required>
                            <option value="">Seleccionar Temporada</option>
                            <?php if (!empty($resultT) && is_array($resultT)): ?>
                                <?php foreach ($resultT as $temp): ?>
                                    <option value="<?php echo htmlspecialchars($temp['temporada']); ?>"><?php echo htmlspecialchars($temp['temporada']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <button type="submit" id="btn-login" class="btn btn-success">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>
                </form>

                <p class="login-footer">
                    <i class="fas fa-shield-alt me-1"></i> GRCFRUIT SPA · Sistema de Gestión de Calidad
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="/calidad/view/assets/js/scripts.js?<?php echo time(); ?>"></script>
</body>
</html>