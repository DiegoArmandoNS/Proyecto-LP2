<?php
function e($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function soles($numero)
{
    return 'S/ ' . number_format((float) $numero, 2);
}

function cantidad($numero)
{
    $texto = number_format((float) $numero, 3, '.', '');
    $texto = rtrim(rtrim($texto, '0'), '.');
    return $texto === '' ? '0' : $texto;
}

function textoEstado($estado)
{
    $textos = array(
        'pendiente' => 'Pendiente',
        'en_preparacion' => 'En preparación',
        'listo' => 'Listo',
        'cancelado' => 'Cancelado',
        'pagado' => 'Pagado',
        'libre' => 'Libre',
        'ocupada' => 'Ocupada',
        'por_cobrar' => 'Por cobrar',
    );
    return isset($textos[$estado]) ? $textos[$estado] : $estado;
}

function enviado($campo, $defecto = '')
{
    return isset($_POST[$campo]) ? trim($_POST[$campo]) : $defecto;
}

function recibido($campo, $defecto = '')
{
    return isset($_GET[$campo]) ? trim($_GET[$campo]) : $defecto;
}

function esNumero($valor)
{
    return filter_var($valor, FILTER_VALIDATE_FLOAT) !== false;
}

function valor($datos, $campo)
{
    return $datos && isset($datos[$campo]) ? $datos[$campo] : '';
}

function listaRoles()
{
    return array('administrador' => 'Administrador', 'mesero' => 'Mesero', 'cocinero' => 'Cocinero', 'cajero' => 'Cajero');
}

function listaMetodos()
{
    return array('efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia', 'yape' => 'Yape', 'plin' => 'Plin');
}
