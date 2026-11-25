<?php
require_once "../app/models/Mesa.php";

class MesaController
{
    public function listar()
    {
        $modelo = new Mesa();
        $titulo = 'Mesas';
        $mesas = $modelo->listar();
        require "../app/views/mesas/listar.php";
    }

    public function guardar()
    {
        $modelo = new Mesa();
        $numero = enviado('numero_mesa');
        $capacidad = enviado('capacidad');
        if (filter_var($numero, FILTER_VALIDATE_INT) === false || $numero < 1 || filter_var($capacidad, FILTER_VALIDATE_INT) === false || $capacidad < 1) {
            redirigir('mesas/listar', 'El número de mesa y la capacidad deben ser enteros mayores a 0.', 'error');
        }
        if ($modelo->numeroExiste($numero)) {
            redirigir('mesas/listar', 'Ya existe la mesa ' . $numero . '.', 'error');
        }
        $modelo->crear($numero, $capacidad);
        redirigir('mesas/listar', 'Mesa ' . $numero . ' creada.');
    }

    public function estado()
    {
        $modelo = new Mesa();
        $id = (int) recibido('id', 0);
        $estado = recibido('estado');
        if (!in_array($estado, array('libre', 'ocupada', 'por_cobrar')) || $modelo->buscar($id) === null) {
            redirigir('mesas/listar', 'Datos no válidos.', 'error');
        }
        $modelo->cambiarEstado($id, $estado);
        redirigir('mesas/listar', 'Estado de la mesa actualizado.');
    }
}
