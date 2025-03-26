<?php

namespace Model;


use phpseclib3\Crypt\Hash;
class Master extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'master';
    protected static $columnasDB = ['id', 'contraseña', 'superUsuario'];

    public $id;
    public $contraseña;
    public $superUsuario;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->contraseña = $args['contraseña'] ?? '';
        $this->superUsuario = $args['superUsuario'] ?? '';
    }

    // Validar inicio de sesión
    public function validarLogin() {
        if(!$this->id) {
            self::$alertas['error'][] = 'El Nombre de Usuario es Obligatorio';
        }
        if(!$this->contraseña) {
            self::$alertas['error'][] = 'La Contraseña es Obligatoria';
        }

        return self::$alertas;
    }

    public function comprobarPasswordMaster($contraseña) {
        // Comparación directa de las contraseñas
        if(!password_verify ( $contraseña, $this->contraseña)) {
            self::$alertas['error'][] = 'Contraseña Incorrecta';
        }
            return true;
        }
}