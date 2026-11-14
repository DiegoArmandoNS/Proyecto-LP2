<?php
require_once "../app/models/Usuario.php";

class UsuarioController
{
    public function listar()
    {
        $modelo = new Usuario();
        $titulo = 'Usuarios';
        $usuarios = $modelo->listar();
        require "../app/views/usuarios/listar.php";
    }

    public function formulario()
    {
        $modelo = new Usuario();
        $id = (int) recibido('id', 0);
        $usuario = $id > 0 ? $modelo->buscar($id) : null;
        if ($id > 0 && $usuario === null) {
            redirigir('usuarios/listar', 'El usuario no existe.', 'error');
        }
        $titulo = $usuario ? 'Editar usuario' : 'Nuevo usuario';
        $roles = listaRoles();
        $datos = $usuario;
        $error = '';
        require "../app/views/usuarios/formulario.php";
    }

    public function guardar()
    {
        $modelo = new Usuario();
        $id = (int) enviado('id_usuario', 0);
        $datos = array(
            'nombre' => enviado('nombre'),
            'apellido' => enviado('apellido'),
            'username' => enviado('username'),
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'rol' => enviado('rol'),
        );
        $errores = array();
        if ($datos['nombre'] === '' || $datos['apellido'] === '') {
            $errores[] = 'El nombre y el apellido son obligatorios.';
        }
        if (strlen($datos['username']) < 4) {
            $errores[] = 'El usuario debe tener al menos 4 caracteres.';
        } elseif ($modelo->usernameExiste($datos['username'], $id)) {
            $errores[] = 'Ese nombre de usuario ya existe.';
        }
        $roles = listaRoles();
        if (!isset($roles[$datos['rol']])) {
            $errores[] = 'Elige un rol.';
        }
        if ($id === 0 && strlen($datos['password']) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        }
        if ($id > 0 && $datos['password'] !== '' && strlen($datos['password']) < 6) {
            $errores[] = 'La contraseña nueva debe tener al menos 6 caracteres.';
        }
        if ($id > 0 && $id === idUsuario() && $datos['rol'] !== 'administrador') {
            $errores[] = 'No puedes quitarte tu propio rol de administrador.';
        }
        if (count($errores) > 0) {
            $usuario = $id > 0 ? $modelo->buscar($id) : null;
            $titulo = $id > 0 ? 'Editar usuario' : 'Nuevo usuario';
            $roles = listaRoles();
            $error = implode(' ', $errores);
            require "../app/views/usuarios/formulario.php";
            return;
        }
        if ($id > 0) {
            $modelo->actualizar($id, $datos);
            redirigir('usuarios/listar', 'Usuario actualizado.');
        }
        $modelo->crear($datos);
        redirigir('usuarios/listar', 'Usuario creado.');
    }

    public function estado()
    {
        $id = (int) recibido('id', 0);
        $estado = (int) recibido('estado', 1) === 1 ? 1 : 0;
        if ($id === idUsuario() && $estado === 0) {
            redirigir('usuarios/listar', 'No puedes desactivar tu propio usuario.', 'error');
        }
        $modelo = new Usuario();
        if ($modelo->buscar($id) === null) {
            redirigir('usuarios/listar', 'El usuario no existe.', 'error');
        }
        $modelo->cambiarEstado($id, $estado);
        redirigir('usuarios/listar', $estado === 1 ? 'Usuario activado.' : 'Usuario desactivado.');
    }
}
