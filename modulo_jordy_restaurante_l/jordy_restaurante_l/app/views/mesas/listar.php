<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mesas - Restaurante L</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Administración de mesas</h2>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Número de mesa</th><th>Capacidad</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>Mesa 01</td><td>4 personas</td><td><span class="badge bg-success">Libre</span></td><td><button class="btn btn-sm btn-outline-primary">Cambiar estado</button></td></tr>
                    <tr><td>2</td><td>Mesa 02</td><td>6 personas</td><td><span class="badge bg-danger">Ocupada</span></td><td><button class="btn btn-sm btn-outline-primary">Cambiar estado</button></td></tr>
                    <tr><td>3</td><td>Mesa 03</td><td>2 personas</td><td><span class="badge bg-warning text-dark">Por cobrar</span></td><td><button class="btn btn-sm btn-outline-primary">Cambiar estado</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>