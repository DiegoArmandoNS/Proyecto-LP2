<?php
require_once "../app/models/Insumo.php";
require_once "../app/models/MovimientoInventario.php";
require_once "../app/services/InventarioService.php";

class InventarioController
{
    public function listar()
    {
        $modelo = new Insumo();
        $titulo = 'Inventario de insumos';
        $insumos = $modelo->listar();
        require "../app/views/inventario/listar.php";
    }

    public function formulario()
    {
        $modelo = new Insumo();
        $id = (int) recibido('id', 0);
        $insumo = $id > 0 ? $modelo->buscar($id) : null;
        if ($id > 0 && $insumo === null) {
            redirigir('inventario/listar', 'El insumo no existe.', 'error');
        }
        $titulo = $insumo ? 'Editar insumo' : 'Nuevo insumo';
        $datos = $insumo;
        $error = '';
        require "../app/views/inventario/formulario.php";
    }

    public function guardar()
    {
        $modelo = new Insumo();
        $id = (int) enviado('id_insumo', 0);
        $datos = array(
            'nombre' => enviado('nombre'),
            'unidad_medida' => enviado('unidad_medida'),
            'stock_actual' => enviado('stock_actual', '0'),
            'stock_minimo' => enviado('stock_minimo', '0'),
            'estado' => enviado('estado', '1') === '0' ? 0 : 1,
        );
        $servicio = new InventarioService();
        $errores = $servicio->validarInsumo($datos, $id === 0);
        if (count($errores) > 0) {
            $insumo = $id > 0 ? $modelo->buscar($id) : null;
            $titulo = $id > 0 ? 'Editar insumo' : 'Nuevo insumo';
            $error = implode(' ', $errores);
            require "../app/views/inventario/formulario.php";
            return;
        }
        if ($id > 0) {
            $modelo->actualizar($id, $datos);
            redirigir('inventario/listar', 'Insumo actualizado.');
        }
        $servicio->crearInsumo($datos, idUsuario());
        redirigir('inventario/listar', 'Insumo registrado.');
    }

    public function movimientos()
    {
        $insumo = new Insumo();
        $movimiento = new MovimientoInventario();
        $idInsumo = (int) recibido('id_insumo', 0);
        $tipo = recibido('tipo');
        $fecha = recibido('fecha');
        $titulo = 'Movimientos de inventario';
        $insumos = $insumo->listar(true);
        $movimientos = $movimiento->listar($idInsumo, $tipo, $fecha);
        $filtro_insumo = $idInsumo;
        $filtro_tipo = $tipo;
        $filtro_fecha = $fecha;
        require "../app/views/inventario/movimientos.php";
    }

    public function registrarMovimiento()
    {
        $idInsumo = (int) enviado('id_insumo', 0);
        try {
            $servicio = new InventarioService();
            $servicio->registrarMovimiento($idInsumo, enviado('tipo'), enviado('cantidad'), enviado('motivo'), idUsuario());
            redirigir('inventario/movimientos', 'Movimiento registrado.');
        } catch (Exception $e) {
            redirigir('inventario/movimientos', $e->getMessage(), 'error');
        }
    }

    public function alertas()
    {
        $insumo = new Insumo();
        $titulo = 'Alertas de stock mínimo';
        $alertas = $insumo->alertas();
        require "../app/views/inventario/alertas.php";
    }
}
