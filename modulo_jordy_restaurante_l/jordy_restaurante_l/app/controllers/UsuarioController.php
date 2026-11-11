<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Models\Usuario;
use App\Services\AuthService;
use Throwable;

final class UsuarioController
{
    private const ROLES = ['administrador', 'mesero', 'cocinero', 'cajero'];

    public function __construct(
        private Usuario $usuarios,
        private AuthService $auth
    ) {}

    public function listar(): void
    {
        $this->protegerAdministrador();
        $usuarios = $this->usuarios->listar();
        $mensaje = $_SESSION['_flash'] ?? null;
        unset($_SESSION['_flash']);
        require dirname(__DIR__) . '/views/usuarios/listar.php';
    }

    public function formulario(): void
    {
        $this->protegerAdministrador();

        $id = (int)($_GET['id'] ?? $_POST['idUsuarios'] ?? 0);
        $usuario = $id > 0 ? $this->usuarios->buscarPorId($id) : null;
        $error = null;

        if ($id > 0 && !$usuario) {
            http_response_code(404);
            exit('Usuario no encontrado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validar($_POST['_token'] ?? null)) {
                http_response_code(419);
                $error = 'Formulario vencido. Recarga la página.';
            } else {
                $datos = [
                    'nombres' => trim($_POST['nombres'] ?? ''),
                    'apellidos' => trim($_POST['apellidos'] ?? ''),
                    'username' => trim($_POST['username'] ?? ''),
                    'rol' => strtolower(trim($_POST['rol'] ?? '')),
                ];
                $password = $_POST['password'] ?? '';

                if (
                    $datos['nombres'] === '' ||
                    $datos['apellidos'] === '' ||
                    $datos['username'] === '' ||
                    !in_array($datos['rol'], self::ROLES, true)
                ) {
                    $error = 'Completa todos los campos obligatorios y selecciona un rol válido.';
                } elseif ($id === 0 && strlen($password) < 8) {
                    $error = 'La contraseña debe tener al menos 8 caracteres.';
                } elseif ($password !== '' && strlen($password) < 8) {
                    $error = 'La nueva contraseña debe tener al menos 8 caracteres.';
                } elseif ($this->usuarios->usernameExiste($datos['username'], $id ?: null)) {
                    $error = 'Ese nombre de usuario ya está registrado.';
                } else {
                    try {
                        if ($password !== '') {
                            $datos['password'] = password_hash($password, PASSWORD_DEFAULT);
                        }

                        if ($id > 0) {
                            $this->usuarios->actualizar($id, $datos);
                            $_SESSION['_flash'] = 'Usuario actualizado correctamente.';
                        } else {
                            $this->usuarios->crear($datos);
                            $_SESSION['_flash'] = 'Usuario registrado correctamente.';
                        }

                        header('Location: ?r=usuarios');
                        exit;
                    } catch (Throwable $e) {
                        error_log($e->getMessage());
                        $error = 'No se pudo guardar el usuario. Revisa los datos e inténtalo nuevamente.';
                    }
                }
            }
        }

        require dirname(__DIR__) . '/views/usuarios/formulario.php';
    }

    public function cambiarEstado(): void
    {
        $this->protegerAdministrador();

        if (!Csrf::validar($_POST['_token'] ?? null)) {
            http_response_code(419);
            exit('Solicitud no válida.');
        }

        $id = (int)($_POST['idUsuarios'] ?? 0);
        $usuario = $this->usuarios->buscarPorId($id);

        if (!$usuario) {
            $_SESSION['_flash'] = 'El usuario no existe.';
        } elseif ($id === (int)$this->auth->usuarioActual()['idUsuarios']) {
            $_SESSION['_flash'] = 'No puedes desactivar tu propia cuenta.';
        } else {
            $nuevoEstado = strcasecmp($usuario['estado'], 'Activo') === 0
                ? 'Inactivo'
                : 'Activo';

            $this->usuarios->cambiarEstado($id, $nuevoEstado);
            $_SESSION['_flash'] = 'Estado del usuario actualizado.';
        }

        header('Location: ?r=usuarios');
        exit;
    }

    private function protegerAdministrador(): void
    {
        if (!$this->auth->estaAutenticado()) {
            header('Location: ?r=login');
            exit;
        }

        if (!$this->auth->tieneRol('administrador')) {
            http_response_code(403);
            exit('No tienes permiso para acceder a esta sección.');
        }
    }
}
