<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Pedido</title>
    </head>
    <body>
        <?php
            foreach ($pedido as $pedido) {
                echo $pedido["nombre"]."<br/>";
            }
        ?>
    </body>
</html>
