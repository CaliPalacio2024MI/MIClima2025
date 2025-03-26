<?php

namespace Model;

class Antiguedades extends ActiveRecord {
    protected static $tabla = 'antiguedades';
    protected static $columnasDB = ['id', 'antiguedad'];

    public $id;
    public $antiguedad;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->antiguedad = $args['antiguedad'] ?? '';
    } 

}