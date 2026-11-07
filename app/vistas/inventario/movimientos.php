<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Movimientos</title>
</head>
<body>
    <h2>Movimientos de Inventario</h2>
    <form action="" method="POST">
        <label>Tipo:</label>
        <select name="tipo">
            <option value="INGRESO">Ingreso</option>
            <option value="SALIDA">Salida</option>
            <option value="MERMA">Merma</option>
        </select><br>
        <label>Cantidad:</label><br><input type="number" name="cantidad"><br>
        <label>Motivo:</label><br><input type="text" name="motivo"><br><br>
        <button type="submit">Registrar Movimiento</button>
    </form>
</body>
</html>