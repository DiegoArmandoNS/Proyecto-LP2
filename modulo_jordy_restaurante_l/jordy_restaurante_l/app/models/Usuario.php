<?php

class Usuario
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listar(): array
    {
        $sql = "SELECT idUsuarios, nombres, apellidos,
                       username, rol, estado, fecha_registro
                FROM Usuarios
                ORDER BY idUsuarios DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT *
                FROM Usuarios
                WHERE idUsuarios = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    public function buscarPorUsername(string $username): ?array
    {
        $sql = "SELECT *
                FROM Usuarios
                WHERE username = :username
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    public function registrar(array $datos): bool
    {
        $sql = "INSERT INTO Usuarios
                (nombres, apellidos, username, password,
                 rol, estado, fecha_registro)
                VALUES
                (:nombres, :apellidos, :username, :password,
                 :rol, 'Activo', NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':username' => $datos['username'],
            ':password' => password_hash(
                $datos['password'],
                PASSWORD_DEFAULT
            ),
            ':rol' => $datos['rol']
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "UPDATE Usuarios
                SET nombres = :nombres,
                    apellidos = :apellidos,
                    username = :username,
                    rol = :rol
                WHERE idUsuarios = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':username' => $datos['username'],
            ':rol' => $datos['rol'],
            ':id' => $id
        ]);
    }

    public function cambiarPassword(int $id, string $password): bool
    {
        $sql = "UPDATE Usuarios
                SET password = :password
                WHERE idUsuarios = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            ':id' => $id
        ]);
    }

    public function desactivar(int $id): bool
    {
        $sql = "UPDATE Usuarios
                SET estado = 'Inactivo'
                WHERE idUsuarios = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function activar(int $id): bool
    {
        $sql = "UPDATE Usuarios
                SET estado = 'Activo'
                WHERE idUsuarios = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}