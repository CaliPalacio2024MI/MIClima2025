<?php

namespace Model;

class Periodos extends ActiveRecord {
    protected static $tabla = 'periodos';
    protected static $columnasDB = ['id', 'periodo', 'propiedades_id'];

    public $id;
    public $periodo;
    public $propiedades_id;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->periodo = $args['periodo'] ?? '';
      $this->propiedades_id = $args['propiedades_id'] ?? '';
    } 

  }