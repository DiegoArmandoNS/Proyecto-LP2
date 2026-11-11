<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de usuario - Restaurante L</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width:650px;">
        <div class="card-header"><h4 class="mb-0">Registrar usuario</h4></div>
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de usuario</label>
                    <input type="text" name="username" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select">
                        <option value="">Seleccione un rol</option>
                        <option value="administrador">Administrador</option>
                        <option value="mesero">Mesero</option>
                        <option value="cocinero">Cocinero</option>
                        <option value="cajero">Cajero</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>