<?php
require_once "../app/models/Usuario.php";
require_once "../app/models/Insumo.php";
require_once "../app/models/Venta.php";

class AuthService
{
    public function login($username, $password)
    {
        if ($username === '' || $password === '') {
            throw new Exception('Escribe tu usuario y tu contraseña.');
        }
        $modelo = new Usuario();
        $usuario = $modelo->buscarPorUsername($username);
        if ($usuario === null || !password_verify($password, $usuario['password'])) {
            throw new Exception('Usuario o contraseña incorrectos.');
        }
        if ((int) $usuario['estado'] !== 1) {
            throw new Exception('Tu usuario está desactivado. Habla con el administrador.');
        }
        iniciarSesion($usuario);
        return $usuario;
    }

    public function resumenInicio($rol)
    {
        $resumen = array('alertas' => 0, 'ventas_total' => 0, 'ventas_cantidad' => 0);
        if ($rol === 'administrador') {
            $insumo = new Insumo();
            $venta = new Venta();
            $hoy = $venta->resumenDelDia(date('Y-m-d'));
            $resumen['alertas'] = count($insumo->alertas());
            $resumen['ventas_total'] = $hoy['total'];
            $resumen['ventas_cantidad'] = $hoy['cantidad'];
        }
        return $resumen;
    }
}
