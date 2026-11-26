<?php require "../app/views/layouts/header.php"; ?>
<p><a href="index.php?ruta=usuarios/formulario">Nuevo usuario</a></p>
<table>
    <tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($usuarios as $u) { ?>
        <tr>
            <td><?php echo e($u['nombre'] . ' ' . $u['apellido']); ?></td>
            <td><?php echo e($u['username']); ?></td>
            <td><?php echo e($u['rol']); ?></td>
            <td><?php echo $u['estado'] == 1 ? 'Activo' : '<span class="rojo">Inactivo</span>'; ?></td>
            <td>
                <a href="index.php?ruta=usuarios/formulario&id=<?php echo $u['id_usuario']; ?>">Editar</a>
                <?php if ($u['estado'] == 1) { ?>
                    <a href="index.php?ruta=usuarios/estado&id=<?php echo $u['id_usuario']; ?>&estado=0" onclick="return confirm('¿Desactivar este usuario?')">Desactivar</a>
                <?php } else { ?>
                    <a href="index.php?ruta=usuarios/estado&id=<?php echo $u['id_usuario']; ?>&estado=1">Activar</a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
<?php require "../app/views/layouts/footer.php"; ?>
