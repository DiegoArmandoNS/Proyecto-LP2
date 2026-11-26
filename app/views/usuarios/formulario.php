<?php require "../app/views/layouts/header.php"; ?>
<div>
    <?php if ($error !== '') { ?>
        <div class="alerta error"><?php echo e($error); ?></div>
    <?php } ?>
    <form method="post" action="index.php?ruta=usuarios/guardar">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario ? (int) $usuario['id_usuario'] : 0; ?>">
        <label>Nombres</label>
        <input name="nombre" value="<?php echo e(valor($datos, 'nombre')); ?>" required>
        <label>Apellidos</label>
        <input name="apellido" value="<?php echo e(valor($datos, 'apellido')); ?>" required>
        <label>Usuario (para entrar al sistema)</label>
        <input name="username" value="<?php echo e(valor($datos, 'username')); ?>" required>
        <label>Contraseña <?php echo $usuario ? '(déjala vacía para no cambiarla)' : ''; ?></label>
        <input name="password" type="password" <?php echo $usuario ? '' : 'required'; ?>>
        <label>Rol</label>
        <select name="rol" required>
            <option value="">Elige un rol</option>
            <?php foreach ($roles as $clave => $texto) { ?>
                <option value="<?php echo e($clave); ?>" <?php echo valor($datos, 'rol') === $clave ? 'selected' : ''; ?>><?php echo e($texto); ?></option>
            <?php } ?>
        </select>
        <p><button type="submit">Guardar</button> <a href="index.php?ruta=usuarios/listar">Cancelar</a></p>
    </form>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
