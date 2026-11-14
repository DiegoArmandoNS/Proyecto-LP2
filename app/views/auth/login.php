<?php require "../app/views/layouts/header.php"; ?>
<div>
    <?php if ($error !== '') { ?>
        <div class="alerta error"><?php echo e($error); ?></div>
    <?php } ?>
    <form method="post" action="index.php?ruta=login">
        <label for="username">Usuario</label>
        <input id="username" name="username" value="<?php echo e($username); ?>" autofocus required>
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required>
        <p><button type="submit">Entrar</button></p>
    </form>
</div>
<?php require "../app/views/layouts/footer.php"; ?>
