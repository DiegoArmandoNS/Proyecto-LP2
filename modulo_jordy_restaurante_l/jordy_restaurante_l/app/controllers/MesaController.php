<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Csrf;
use App\Models\Mesa;
use App\Services\AuthService;

final class MesaController
{
    public function __construct(
        private Mesa $mesas,
        private AuthService $auth
    ) {}

    public function listar(): void
    {
        $this->proteger();
        $mesas = $this->mesas->listar();
        $mensaje = $_SESSION['_flash'] ?? null;
        unset($_SESSION['_flash']);
        require dirname(__DIR__) . '/views/mesas/listar.php';
    }

    public function cambiarEstado(): void
    {
        $this->proteger();

        if (!Csrf::validar($_POST['_token'] ?? null)) {
            http_response_code(419);
            exit('Solicitud no válida.');
        }

        $id = (int)($_POST['idMesas'] ?? 0);
        $estado = trim($_POST['estado'] ?? '');

        if (!$this->mesas->buscarPorId($id)) {
            $_SESSION['_flash'] = 'La mesa indicada no existe.';
        } elseif ($this->mesas->cambiarEstado($id, $estado)) {
            $_SESSION['_flash'] = 'Estado de la mesa actualizado.';
        } else {
            $_SESSION['_flash'] = 'No se pudo actualizar. Verifica el estado seleccionado.';
        }

        header('Location: ?r=mesas');
        exit;
    }

    private function proteger(): void
    {
        if (!$this->auth->estaAutenticado()) {
            header('Location: ?r=login');
            exit;
        }

        if (!$this->auth->tieneRol(['administrador', 'mesero', 'cajero'])) {
            http_response_code(403);
            exit('No tienes permiso para gestionar las mesas.');
        }
    }
}
