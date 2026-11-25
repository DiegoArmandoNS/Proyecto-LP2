<?php
date_default_timezone_set('America/Lima');
require_once "../app/helpers/funciones.php";
require_once "../app/helpers/sesion.php";
session_start();

$ruta = isset($_GET['ruta']) ? trim($_GET['ruta'], '/') : 'inicio';

try {
    require_once "../routes/web.php";
} catch (PDOException $e) {
    mostrarError('No se pudo completar la operación. Intenta de nuevo.', 500);
} catch (Exception $e) {
    mostrarError('Ocurrió un error en el sistema: ' . $e->getMessage(), 500);
}
