<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO; 

final class Mesa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listar(): array
    {
        $sql = "SELECT idMesas, numero_mesa, capacidad, estado
                FROM Mesas ORDER BY numero_mesa ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT idMesas, numero_mesa, capacidad, estado
             FROM Mesas WHERE idMesas = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $mesa = $stmt->fetch();
        return $mesa ?: null;
    }

    public function cambiarEstado(int $id, string $estado): bool
    {
        $permitidos = ['Libre', 'Ocupada', 'Por cobrar'];
        if (!in_array($estado, $permitidos, true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE Mesas SET estado = :estado WHERE idMesas = :id"
        );
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }
}
