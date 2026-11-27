<?php require "../app/views/layouts/header.php"; ?>
<div>
    <?php if ($error !== '') { ?>
        <div class="alerta error"><?php echo e($error); ?></div>
    <?php } ?>
    <form method="post" action="index.php?ruta=carta/guardar">
        <input type="hidden" name="id_plato" value="<?php echo $plato ? (int) $plato['id_plato'] : 0; ?>">
        <label>Categoría</label>
        <select name="id_categoria" required>
            <option value="">Elige una categoría</option>
            <?php foreach ($categorias as $c) { ?>
                <option value="<?php echo $c['id_categoria']; ?>" <?php echo valor($datos, 'id_categoria') == $c['id_categoria'] ? 'selected' : ''; ?>><?php echo e($c['nombre']); ?></option>
            <?php } ?>
        </select>
        <label>Nombre del plato</label>
        <input name="nombre" value="<?php echo e(valor($datos, 'nombre')); ?>" required>
        <label>Descripción</label>
        <textarea name="descripcion" rows="3"><?php echo e(valor($datos, 'descripcion')); ?></textarea>
        <label>Precio (S/)</label>
        <input name="precio" type="number" step="0.01" min="0.01" value="<?php echo e(valor($datos, 'precio')); ?>" required>
        <p><button type="submit">Guardar</button> <a href="index.php?ruta=carta/listar">Cancelar</a></p>
    </form>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
