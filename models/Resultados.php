<?php

namespace Model;

class Resultados extends ActiveRecord {
    protected static $tabla = 'resultados';
    protected static $columnasDB = ['id', 'genero', 'edad', 'tipoPuesto', 'antiguedad', 'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8', 'p9', 'p10', 'p11', 'p12', 'p13', 'p14', 'p15', 'p16', 'departamentos_id', 'periodos_id', 'idpropiedades'];

    public $id;
    public $genero;
    public $edad;
    public $tipoPuesto;
    public $antiguedad;
    public $p1;
    public $p2;
    public $p3;
    public $p4;
    public $p5;
    public $p6;
    public $p7;
    public $p8;
    public $p9;
    public $p10;
    public $p11;
    public $p12;
    public $p13;
    public $p14;
    public $p15;
    public $p16;
    public $departamentos_id;
    public $periodos_id;
    public $idpropiedades;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->genero = $args['genero'] ?? '';
        $this->edad = $args['edad'] ?? '';
        $this->tipoPuesto = $args['tipoPuesto'] ?? '';
        $this->antiguedad = $args['antiguedad'] ?? '';
        $this->p1 = $args['p1'] ?? '';
        $this->p2 = $args['p2'] ?? '';
        $this->p3 = $args['p3'] ?? '';
        $this->p4 = $args['p4'] ?? '';
        $this->p5 = $args['p5'] ?? '';
        $this->p6 = $args['p6'] ?? '';
        $this->p7 = $args['p7'] ?? '';
        $this->p8 = $args['p8'] ?? '';
        $this->p9 = $args['p9'] ?? '';
        $this->p10 = $args['p10'] ?? '';
        $this->p11 = $args['p11'] ?? '';
        $this->p12 = $args['p12'] ?? '';
        $this->p13 = $args['p13'] ?? '';
        $this->p14 = $args['p14'] ?? '';
        $this->p15 = $args['p15'] ?? '';
        $this->p16 = $args['p16'] ?? '';
        $this->departamentos_id = $args['departamentos_id'] ?? '';
        $this->periodos_id = $args['periodos_id'] ?? '';
        $this->idpropiedades = $args['idpropiedades'] ?? '';
    } 

    public function validar() {
        if(!$this->periodos_id) {
            self::$alertas['error'][] = 'Selecciona una Propiedad';
        }
        if(!$this->departamentos_id) {
            self::$alertas['error'][] = 'Selecciona un Departamento';
        }
        if(!$this->genero) {
            self::$alertas['error'][] = 'Selecciona el Género';
        }
        if(!$this->edad) {
            self::$alertas['error'][] = 'Selecciona una Edad';
        }
        if(!$this->tipoPuesto) {
            self::$alertas['error'][] = 'Selecciona un Tipo de Puesto';
        }
        if(!$this->antiguedad) {
            self::$alertas['error'][] = 'Selecciona una Antiguedad';
        }

        // Validar preguntas
        for ($i = 1; $i <= 16; $i++) {
            $campo = "p{$i}";
            if (!$this->$campo) {
                self::$alertas['error'][] = "Responde todas las Preguntas";
                break;
            }
        }

        return self::$alertas;
    }
}








