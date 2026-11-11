<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Restaurante L</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Administración de usuarios</h2>
        <a href="formulario.php" class="btn btn-primary">Nuevo usuario</a>
    </div>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Usuario</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>Juan</td><td>Pérez</td><td>jperez</td><td>Mesero</td><td>Activo</td><td><button class="btn btn-sm btn-warning">Editar</button></td></tr>
                    <tr><td>2</td><td>María</td><td>López</td><td>mlopez</td><td>Cajero</td><td>Activo</td><td><button class="btn btn-sm btn-warning">Editar</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>