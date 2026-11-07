<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorias - Restaurante L</title>
</head>
<body>
    <h2>Restaurante L</h2>
    <h3>Gestion de categorias</h3>
    <h4>Registrar categoria</h4>
    <form method="POST" action="#">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" placeholder="Ingrese categoria"><br><br>
        <label>Descripcion:</label><br>
        <textarea name="descripcion" placeholder="Ingrese descripcion"></textarea><br><br>
        <input type="submit" value="Guardar categoria">
    </form>
    <hr>
    <h4>Lista de categorias</h4>
    <table border="1" cellpadding="8">
    <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Estado</th></tr><tr>
            <td>1</td>
            <td>Entradas</td>
            <td>Platos de entrada</td>
            <td>Activa</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Platos principales</td>
            <td>Platos fuertes</td>
            <td>Activa</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Bebidas</td>
            <td>Bebidas frias y calientes</td>
            <td>Activa</td>
        </tr>
    </table></body></html>
