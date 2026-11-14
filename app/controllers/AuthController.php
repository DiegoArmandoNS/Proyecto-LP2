<?php
require_once "../app/services/AuthService.php";

class AuthController
{
    public function login()
    {
        if (usuarioActual()) {
            redirigir('inicio');
        }
        $error = '';
        $username = '';
        if (recibido('expirada') === '1') {
            $error = 'Tu sesión se cerró por inactividad.';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = enviado('username');
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            try {
                $servicio = new AuthService();
                $servicio->login($username, $password);
                redirigir('inicio');
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        $titulo = 'Iniciar sesión';
        require "../app/views/auth/login.php";
    }

    public function logout()
    {
        cerrarSesion();
    }

    public function inicio()
    {
        $servicio = new AuthService();
        $resumen = $servicio->resumenInicio(rolUsuario());
        $alertas = $resumen['alertas'];
        $ventas_total = $resumen['ventas_total'];
        $ventas_cantidad = $resumen['ventas_cantidad'];
        $titulo = 'Inicio';
        require "../app/views/inicio/inicio.php";
    }
}
