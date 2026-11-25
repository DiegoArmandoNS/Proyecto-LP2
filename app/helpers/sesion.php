<?php
function usuarioActual()
{
    return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
}

function idUsuario()
{
    return isset($_SESSION['usuario']) ? (int) $_SESSION['usuario']['id_usuario'] : 0;
}

function rolUsuario()
{
    return isset($_SESSION['usuario']) ? $_SESSION['usuario']['rol'] : '';
}

function iniciarSesion($usuario)
{
    session_regenerate_id(true);
    $_SESSION['usuario'] = array(
        'id_usuario' => $usuario['id_usuario'],
        'nombre' => $usuario['nombre'],
        'apellido' => $usuario['apellido'],
        'rol' => $usuario['rol']
    );
    $_SESSION['ultimo_acceso'] = time();
}

function cerrarSesion()
{
    $_SESSION = array();
    session_destroy();
    header('Location: index.php?ruta=login');
    exit;
}

function requiereRol($roles)
{
    $limite = 1800;
    if (!isset($_SESSION['usuario'])) {
        redirigir('login', 'Inicia sesión para continuar.', 'error');
    }
    if (isset($_SESSION['ultimo_acceso']) && time() - $_SESSION['ultimo_acceso'] > $limite) {
        session_unset();
        session_destroy();
        header('Location: index.php?ruta=login&expirada=1');
        exit;
    }
    $_SESSION['ultimo_acceso'] = time();
    if (count($roles) > 0 && !in_array(rolUsuario(), $roles)) {
        mostrarError('No tienes permiso para entrar a esta página.', 403);
    }
}

function redirigir($ruta, $mensaje = null, $tipo = 'ok')
{
    if ($mensaje !== null) {
        $_SESSION['flash'] = array('mensaje' => $mensaje, 'tipo' => $tipo);
    }
    header('Location: index.php?ruta=' . $ruta);
    exit;
}

function mostrarError($mensaje, $codigo = 400)
{
    http_response_code($codigo);
    $titulo = 'Error';
    require "../app/views/layouts/header.php";
    echo '<div class="alerta error">' . e($mensaje) . '</div>';
    echo '<p><a href="index.php?ruta=inicio">Volver al inicio</a></p>';
    require "../app/views/layouts/footer.php";
    exit;
}
