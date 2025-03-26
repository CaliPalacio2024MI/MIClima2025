<?php

namespace Model;

class TipoPuestos extends ActiveRecord {
    protected static $tabla = 'tipopuestos';
    protected static $columnasDB = ['id', 'tipoPuesto'];

    public $id;
    public $tipoPuesto;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->tipoPuesto = $args['tipoPuesto'] ?? '';
    } 

}