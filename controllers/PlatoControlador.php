<?php
require_once "../app/models/Plato.php";
require_once "../app/models/Categoria.php";
require_once "../app/models/Insumo.php";
require_once "../app/models/RecetaItem.php";
require_once "../app/services/CartaService.php";
class PlatoController
{
    public function listar()
    {
        $modelo = new Plato();
        $titulo = 'Carta';
        $platos = $modelo->listar();
        require "../app/views/carta/listar.php";
    }

    public function formulario()
    {
        $plato = new Plato();
        $categoria = new Categoria();
        $id = (int) recibido('id', 0);
        $fila = $id > 0 ? $plato->buscar($id) : null;
        if ($id > 0 && $fila === null) {
            redirigir('carta/listar', 'El plato no existe.', 'error');
        }
        $titulo = $fila ? 'Editar plato' : 'Nuevo plato';
        $plato = $fila;
        $datos = $fila;
        $categorias = $categoria->listar(true);
        $error = '';
        require "../app/views/carta/formulario.php";
    }
    public function guardar()
    {
        $plato = new Plato();
        $categoria = new Categoria();
        $id = (int) enviado('id_plato', 0);
        $datos = array(
            'id_categoria' => enviado('id_categoria'),
            'nombre' => enviado('nombre'),
            'descripcion' => enviado('descripcion'),
            'precio' => enviado('precio'),
        );
        $servicio = new CartaService();
        $errores = $servicio->validarPlato($datos);
        if (count($errores) > 0) {
            $fila = $id > 0 ? $plato->buscar($id) : null;
            $titulo = $id > 0 ? 'Editar plato' : 'Nuevo plato';
            $plato = $fila;
            $categorias = $categoria->listar(true);
            $error = implode(' ', $errores);
            require "../app/views/carta/formulario.php";
            return;
        }
        if ($id > 0) {
            $plato->actualizar($id, $datos);
            redirigir('carta/listar', 'Plato actualizado.');
        }
        $plato->crear($datos);
        redirigir('carta/listar', 'Plato creado. Ahora puedes agregarle su receta.');
    }

    public function disponibilidad()
    {
        $plato = new Plato();
        $id = (int) recibido('id', 0);
        if ($plato->buscar($id) === null) {
            redirigir('carta/listar', 'El plato no existe.', 'error');
        }
        $disponible = (int) recibido('disponible', 1) === 1 ? 1 : 0;
        $plato->cambiarDisponible($id, $disponible);
        redirigir('carta/listar', $disponible === 1 ? 'Plato disponible.' : 'Plato marcado como no disponible.');}
    public function categorias()
    {
        $categoria = new Categoria();
        $id = (int) recibido('id', 0);
        $editar = $id > 0 ? $categoria->buscar($id) : null;
        $titulo = 'Categorías';
        $categorias = $categoria->listar();
        require "../app/views/carta/categorias.php";
    }

    public function categoriaGuardar()
    {
        $categoria = new Categoria();
        $id = (int) enviado('id_categoria', 0);
        $nombre = enviado('nombre');
        $descripcion = enviado('descripcion');
        if ($nombre === '') {
            redirigir('carta/categorias', 'El nombre de la categoría es obligatorio.', 'error');
        }
        if ($id > 0) {
            $categoria->actualizar($id, $nombre, $descripcion);
            redirigir('carta/categorias', 'Categoría actualizada.');
        }
        $categoria->crear($nombre, $descripcion);
        redirigir('carta/categorias', 'Categoría creada.');}
    public function categoriaEstado()
    {
        $categoria = new Categoria();
        $id = (int) recibido('id', 0);
        if ($categoria->buscar($id) === null) {
            redirigir('carta/categorias', 'La categoría no existe.', 'error');
        }
        $estado = (int) recibido('estado', 1) === 1 ? 1 : 0;
        $categoria->cambiarEstado($id, $estado);
        redirigir('carta/categorias', $estado === 1 ? 'Categoría activada.' : 'Categoría desactivada.');
    }
    public function recetas()
    {
        $plato = new Plato();
        $insumo = new Insumo();
        $receta = new RecetaItem();
        $id = (int) recibido('id', 0);
        $fila = $plato->buscar($id);
        if ($fila === null) {
            redirigir('carta/listar', 'El plato no existe.', 'error');
        }
        $titulo = 'Receta de ' . $fila['nombre'];
        $plato = $fila;
        $items = $receta->porPlato($id);
        $insumos = $insumo->listar(true);
        $porciones = $receta->porcionesDisponibles($id);
        require "../app/views/carta/recetas.php";}
    public function recetaAgregar()
    {
        $plato = new Plato();
        $receta = new RecetaItem();
        $idPlato = (int) enviado('id_plato', 0);
        $idInsumo = (int) enviado('id_insumo', 0);
        $cantidad = enviado('cantidad');
        if ($plato->buscar($idPlato) === null) {
            redirigir('carta/listar', 'El plato no existe.', 'error');
        }
        try {
            $servicio = new CartaService();
            $servicio->validarIngrediente($idInsumo, $cantidad);
        } catch (Exception $e) {
            redirigir('carta/recetas&id=' . $idPlato, $e->getMessage(), 'error');
        }
        $receta->agregar($idPlato, $idInsumo, round((float) $cantidad, 3));
        redirigir('carta/recetas&id=' . $idPlato, 'Ingrediente guardado en la receta.');}
    public function recetaQuitar()
    {
        $receta = new RecetaItem();
        $idPlato = (int) recibido('id_plato', 0);
        $receta->quitar((int) recibido('id', 0));
        redirigir('carta/recetas&id=' . $idPlato, 'Ingrediente quitado de la receta.');}}