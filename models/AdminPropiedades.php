<?php

namespace Model;

class AdminPropiedades extends ActiveRecord {
    protected static $tabla = 'admin_propiedades'; // Tabla de relación
    protected static $columnasDB = ['id', 'admin_id', 'propiedad_id'];

    public $idap;
    public $admin_id;
    public $propiedad_id;

    public function __construct($args = []) {
        $this->idap = $args['id'] ?? null;
        $this->admin_id = $args['admin_id'] ?? null;
        $this->propiedad_id = $args['propiedad_id'] ?? null;
    }

    // Método para insertar una relación entre un administrador y una propiedad
    public function insertarRelacion() {
        // Definir la consulta SQL
        $query = "INSERT INTO " . self::$tabla . " (admin_id, propiedad_id) VALUES (?, ?)";
        
        // Preparar la consulta utilizando la conexión de la clase ActiveRecord
        $stmt = self::$db->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros a la consulta
            $stmt->bind_param("ii", $this->admin_id, $this->propiedad_id);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la inserción fue exitosa
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        } else {
            return false;
        }
    }
}