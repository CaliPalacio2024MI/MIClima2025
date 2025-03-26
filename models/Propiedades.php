<?php

namespace Model;

class Propiedades extends ActiveRecord {
    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'nombrePropiedad'];

    public $id;
    public $nombrePropiedad;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->nombrePropiedad = $args['nombrePropiedad'] ?? '';
    } 

}