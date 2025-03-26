<?php

namespace Model;

class Departamentos extends ActiveRecord {
    protected static $tabla = 'departamentos';
    protected static $columnasDB = ['id', 'claveDepto', 'nombreDepartamento', 'cantidad', 'propiedades_id', 'periodos_id'];

    public $id;
    public $claveDepto;
    public $nombreDepartamento;
    public $cantidad;
    public $propiedades_id;
    public $periodos_id;

    public function __construct($args = []) {
      $this->id = $args['id'] ?? null;
      $this->claveDepto = $args['claveDepto'] ?? '';
      $this->nombreDepartamento = $args['nombreDepartamento'] ?? '';
      $this->cantidad = $args['cantidad'] ?? '';
      $this->propiedades_id = $args['propiedades_id'] ?? '';
      $this->periodos_id = $args['periodos_id'] ?? '';
    } 

  public function validar() {
    if(!$this->claveDepto) {
      self::$alertas['error'][] = 'El ID del Departamento es Obligatorio';
    }
    if(!$this->nombreDepartamento) {
      self::$alertas['error'][] = 'El nombre del Departamento es Obligatorio';
    }
    // if(!$this->cantidad) {
    //   self::$alertas['error'][] = 'El número de Anfitriones es Obligatorio';
    // }
    if(!filter_var($this->cantidad, FILTER_VALIDATE_INT) || $this->cantidad <= 0) {
      self::$alertas['error'][] = 'Ingresa un número de Anfitriones válido';
    }
    if(!$this->propiedades_id) {
      self::$alertas['error'][] = 'Selecciona una Propiedad';
    }

    return self::$alertas;
  }

  public function getDepartamentosByPeriodoId($periodoId) {
    $query = "SELECT claveDepto, nombreDepartamento FROM departamentos WHERE periodos_id = ?";
    $stmt = self::$db->prepare($query);

    if ($stmt) {
        $stmt->bind_param('i', $periodoId);
        $stmt->execute();
        $result = $stmt->get_result();

        $departamentos = [];
        while ($row = $result->fetch_assoc()) {
            $departamentos[] = $row;
        }

        $stmt->close();

        // Verificar si se recuperaron resultados
        if (!empty($departamentos)) {
            return $departamentos;
        } else {
            return false;
        }
    } else {
        // Manejar el error de preparación del statement
        return false;
    }
}


}