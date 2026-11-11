<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recetas - Restaurante L</title>
</head>
<body>
    <h2>Restaurante L</h2>
    <h3>Gestion de recetas</h3>
    <h4>Ingredientes del plato</h4>
    <form method="POST" action="#">
        <label>Seleccione un plato:</label><br>
        <select name="id_plato">
            <option value="">Seleccione un plato</option>
            <option value="1">Arroz con pollo</option>
            <option value="2">Lomo saltado</option>
            <option value="3">Pollo a la plancha</option>
        </select>
        <br><br>
        <label>Seleccione un ingrediente:</label><br>
        <select name="id_insumo">
            <option value="">Seleccione un ingrediente</option>
            <option value="1">Arroz</option>
            <option value="2">Pollo</option>
            <option value="3">Aceite</option>
            <option value="4">Papa</option>
        </select>
        <br><br>
        <label>Cantidad necesaria:</label><br>
        <input type="number" name="cantidad" step="0.001"
               placeholder="Ingrese cantidad">
        <br><br>
        <input type="submit" value="Agregar ingrediente">
    </form>
    <hr>
    <h4>Lista de ingredientes de la receta</h4>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Plato</th>
            <th>Ingrediente</th>
            <th>Cantidad</th>
            <th>Unidad de medida</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Arroz con pollo</td>
            <td>Arroz</td>
            <td>0.250</td>
            <td>Kg</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Arroz con pollo</td>
            <td>Pollo</td>
            <td>0.200</td>
            <td>Kg</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Lomo saltado</td>
            <td>Papa</td>
            <td>0.300</td>
            <td>Kg</td>
        </tr>
    </table>
    <hr>
    <h4>Consumo de ingredientes</h4>
    <label>Cantidad de platos a preparar:</label><br>
    <input type="number" name="cantidad_platos" placeholder="Ingrese cantidad">
    <br><br>
    <button>Calcular consumo</button>
</body>
</html>
