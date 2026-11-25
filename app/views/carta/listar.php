<?php require "../app/views/layouts/header.php"; ?>
<?php $admin = rolUsuario() === 'administrador'; ?>
<?php if ($admin) { ?>
    <p>
        <a href="index.php?ruta=carta/formulario">Nuevo plato</a>
        <a href="index.php?ruta=carta/categorias">Categorías</a>
    </p>
<?php } ?>
<table>
    <tr><th>Categoría</th><th>Plato</th><th class="der">Precio</th><th>Disponible</th><?php if ($admin) { ?><th>Acciones</th><?php } ?></tr>
    <?php foreach ($platos as $p) { ?>
        <tr>
            <td><?php echo e($p['categoria']); ?></td>
            <td><b><?php echo e($p['nombre']); ?></b><br><small><?php echo e($p['descripcion']); ?></small></td>
            <td class="der"><?php echo soles($p['precio']); ?></td>
            <td><?php echo $p['disponible'] == 1 ? 'Sí' : '<span class="rojo">No</span>'; ?></td>
            <?php if ($admin) { ?>
                <td>
                    <a href="index.php?ruta=carta/formulario&id=<?php echo $p['id_plato']; ?>">Editar</a>
                    <a href="index.php?ruta=carta/recetas&id=<?php echo $p['id_plato']; ?>">Receta</a>
                    <?php if ($p['disponible'] == 1) { ?>
                        <a href="index.php?ruta=carta/disponibilidad&id=<?php echo $p['id_plato']; ?>&disponible=0">Desactivar</a>
                    <?php } else { ?>
                        <a href="index.php?ruta=carta/disponibilidad&id=<?php echo $p['id_plato']; ?>&disponible=1">Activar</a>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>
<?php require "../app/views/layouts/footer.php"; ?>
