<?php

namespace Model;

use phpseclib3\Crypt\Hash;

class Admin extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'administrador';
    protected static $columnasDB = ['id', 'nombreAnfitrion', 'contraseña', 'propiedades_id'];

    public $id;
    public $nombreAnfitrion;
    public $contraseña;
    public $propiedades_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombreAnfitrion = $args['nombreAnfitrion'] ?? '';
        $this->contraseña = $args['contraseña'] ?? '';
        $this->propiedades_id = $args['propiedades_id'] ?? '';
    }

    // Mensajes de validación para la creación de nuevos Administradores
    public function validar() {
        if (!$this->id) {
            self::$alertas['error'][] = 'El ID del Administrador es Obligatorio';
        }
        if (strlen($this->id) != 13) {
            self::$alertas['error'][] = 'El ID del Administrador debe ser de 13 caracteres';
        }
        if (!$this->nombreAnfitrion) {
            self::$alertas['error'][] = 'El Nombre del Administrador es Obligatorio';
        }
        if (!$this->contraseña) {
            self::$alertas['error'][] = 'La contraseña del Administrador es Obligatoria';
        }
        return self::$alertas;
    }

    // Validar inicio de sesión
    public function validarLogin() {
        if (!$this->id) {
            self::$alertas['error'][] = 'El Nombre de usuario es Obligatorio';
        }
        if (!$this->contraseña) {
            self::$alertas['error'][] = 'La contraseña es Obligatoria';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE id = '" . $this->id . "' LIMIT 1";
        $resultado = self::$db->query($query);
        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El Administrador ya está registrado';
        }
        return $resultado;
    }

    public function comprobarPassword($contraseña) {
        // Comparación directa de las contraseñas
        if (!password_verify($contraseña, $this->contraseña)) {
            self::$alertas['error'][] = 'Contraseña Incorrecta';
        }
        return true;
    }
}