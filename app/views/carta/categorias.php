<?php require "../app/views/layouts/header.php"; ?>
<table>
    <tr><th>Categoría</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($categorias as $c) { ?>
        <tr>
            <td><?php echo e($c['nombre']); ?></td>
            <td><?php echo e($c['descripcion']); ?></td>
            <td><?php echo $c['estado'] == 1 ? 'Activa' : '<span class="rojo">Inactiva</span>'; ?></td>
            <td>
                <a href="index.php?ruta=carta/categorias&id=<?php echo $c['id_categoria']; ?>">Editar</a>
                <?php if ($c['estado'] == 1) { ?>
                    <a href="index.php?ruta=carta/categoria_estado&id=<?php echo $c['id_categoria']; ?>&estado=0">Desactivar</a>
                <?php } else { ?>
                    <a href="index.php?ruta=carta/categoria_estado&id=<?php echo $c['id_categoria']; ?>&estado=1">Activar</a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
<h2><?php echo $editar ? 'Editar categoría' : 'Nueva categoría'; ?></h2>
<form method="post" action="index.php?ruta=carta/categoria_guardar">
    <input type="hidden" name="id_categoria" value="<?php echo $editar ? (int) $editar['id_categoria'] : 0; ?>">
    <label>Nombre</label>
    <input name="nombre" value="<?php echo $editar ? e($editar['nombre']) : ''; ?>" required>
    <label>Descripción</label>
    <input name="descripcion" value="<?php echo $editar ? e($editar['descripcion']) : ''; ?>">
    <p><button type="submit">Guardar</button> <a href="index.php?ruta=carta/listar">Volver a la carta</a></p>
</form>
<?php require "../app/views/layouts/footer.php"; ?>
