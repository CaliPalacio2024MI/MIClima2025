<?php

namespace Model;

class Generos extends ActiveRecord {
    protected static $tabla = 'generos';
    protected static $columnasDB = ['id', 'genero'];

    public $id;
    public $genero;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->genero = $args['genero'] ?? '';
    } 

}