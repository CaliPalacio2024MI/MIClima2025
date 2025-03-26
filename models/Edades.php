<?php

namespace Model;

class Edades extends ActiveRecord {
    protected static $tabla = 'edades';
    protected static $columnasDB = ['id', 'edad'];

    public $id;
    public $edad;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->edad = $args['edad'] ?? '';
    } 

}