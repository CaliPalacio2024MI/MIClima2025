<?php

namespace Model;

class Preguntas extends ActiveRecord {
    protected static $tabla = 'preguntas';
    protected static $columnasDB = ['id', 'idResultado', 'pregunta', 'idpropiedades', 'idperiodo'];

    public $id;
    public $idResultado;
    public $pregunta;
    public $idpropiedades;
    public $idperiodo;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->idResultado = $args['idResultado'] ?? '';
      $this->pregunta = $args['pregunta'] ?? '';
      $this->idpropiedades = $args['idpropiedades'] ?? '';
      $this->idperiodo = $args['idperiodo'] ?? '';
    } 

}