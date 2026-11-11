<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Services\AuthService;

final class AuthController
{
    public function __construct(private AuthService $auth) {}

    public function login(): void
    {
        if ($this->auth->estaAutenticado()) {
            header('Location: ?r=mesas');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validar($_POST['_token'] ?? null)) {
                http_response_code(419);
                $error = 'La sesión del formulario venció. Recarga la página e inténtalo nuevamente.';
            } else {
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';

                if ($username === '' || $password === '') {
                    $error = 'Ingresa tu usuario y contraseña.';
                } elseif ($this->auth->login($username, $password)) {
                    header('Location: ?r=mesas');
                    exit;
                } else {
                    $error = 'Credenciales incorrectas o usuario inactivo.';
                }
            }
        }

        require dirname(__DIR__) . '/views/auth/login.php';
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !Csrf::validar($_POST['_token'] ?? null)) {
            http_response_code(419);
            exit('Solicitud no válida.');
        }

        $this->auth->logout();
        header('Location: ?r=login');
        exit;
    }
}
