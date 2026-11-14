<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar plato</title>
</head>
<body>
    <h2>Restaurante L</h2>
    <h3>Formulario de platos</h3>
    <form method="POST" action="#">
        <label>Nombre del plato:</label><br>
        <input type="text" name="nombre" placeholder="Ingrese nombre del plato">
        <br><br>
        <label>Descripcion:</label><br>
        <textarea name="descripcion" placeholder="Ingrese descripcion"></textarea>
        <br><br>
        <label>Precio:</label><br>
        <input type="number" name="precio" step="0.01" placeholder="Ingrese precio"><br><br>
        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Seleccione una categoria</option>
            <option value="1">Entradas</option>
            <option value="2">Platos principales</option>
            <option value="3">Bebidas</option>
            <option value="4">Postres</option>
        </select>
        <br><br>
        <label>Disponibilidad:</label><br>
        <select name="disponible">
            <option value="1">Disponible</option>
            <option value="0">No disponible</option>
        </select>
        <br><br>
        <input type="submit" value="Guardar plato">
        <a href="listar.php">Volver</a>
    </form></body></html>
