<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];

    //Visibilidad de los atributos (Para que Intelephense no marque error, es innecesario )
    public $id;
    public $imagen;

    public $claveDepto;
    public $nombreDepartamento;
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    public static function first($table, $conditions = []) {
        // Construcción de la consulta básica
        $query = "SELECT * FROM $table";

        // Si hay condiciones, agregarlas al query
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $conditions_arr = [];
            foreach ($conditions as $column => $value) {
                $conditions_arr[] = "$column = '$value'";  // Puedes mejorar la seguridad usando prepared statements
            }
            $query .= implode(' AND ', $conditions_arr);
        }

        // Ejecutar la consulta
        $result = self::consultarSQL($query);

        // Retornar el primer resultado
        return isset($result[0]) ? $result[0] : null;
    }

    // Consulta SQL para crear un objeto en Memoria
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            // if($columna === 'id') continue; // Revisar, debería ir
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            if($key == 'contraseña'){
                $hashed_password = password_hash($value, PASSWORD_BCRYPT);
                $this->$key = $hashed_password;
            }else{
                $this->$key = $value;
            }
          }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

 
    public static function all($columna = '', $valor = '', $orden = 'id ASC', $limite = '') {
        $query = "SELECT * FROM " . static::$tabla;
        if ($columna) {
            $query .= " WHERE $columna = '$valor'"; // Asegúrate de manejar correctamente las comillas para valores string.
        }
        $query .= " ORDER BY $orden"; // Ordenamiento dinámico
    
        if ($limite) {
            $query .= " LIMIT $limite"; // Límite dinámico
        }
    
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function filtrar($params = [], $orden = 'id ASC') {
        $query = "SELECT * FROM " . static::$tabla;
    
        if (!empty($params)) {
            $query .= " WHERE ";
            $conditions = [];
    
            foreach ($params as $columna => $valor) {
                if (is_numeric($valor)) {
                    $conditions[] = "$columna = $valor";
                } else {
                    $conditions[] = "$columna = '$valor'";
                }
            }
    
            $query .= join(" AND ", $conditions);
        }
    
        $query .= " ORDER BY $orden"; // Ordenamiento dinámico
    
    
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function findPrevio($periodos_id, $departamentos_id, $idpropiedades) {
        // Verificar si el periodos_id es igual a 8, 11, o 14
        if (in_array($periodos_id, [8, 11, 14])) {
            return 'N/A';
        }
    
        // Construir la consulta SQL
        $query = "
            SELECT * 
            FROM " . static::$tabla . "
            WHERE departamentos_id = '$departamentos_id'
              AND idpropiedades = '$idpropiedades'
              AND periodos_id = (
                SELECT MAX(periodos_id) 
                FROM " . static::$tabla . "
                WHERE periodos_id < '$periodos_id' 
                  AND departamentos_id = '$departamentos_id'
                  AND idpropiedades = '$idpropiedades'
              ) 
            ORDER BY id DESC 
            LIMIT 1;
        ";
    
        // Ejecutar la consulta SQL
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    public static function findRG($periodos_id, $idpropiedades) {
        // Verificar si el periodos_id es 8, 11 o 14 y devolver "N/A"
        if (in_array($periodos_id, [8, 11, 14])) {
            return [['departamentos_id' => 'N/A']];
        }
    
        $query = "
            SELECT * 
            FROM " . static::$tabla . "
            WHERE idpropiedades = '$idpropiedades'
              AND periodos_id = (
                SELECT MAX(periodos_id) 
                FROM " . static::$tabla . "
                WHERE periodos_id < '$periodos_id'
                  AND idpropiedades = '$idpropiedades'
              )
            ORDER BY id DESC;
        ";
    
        return self::consultarSQL($query);
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = '$id'"; //Reviar, '' deberían ser innecesarios
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }
    
    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT $limite ORDER BY id ASC";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }
   
    public static function where($columna, $valor, $orden = 'ASC') {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna = '$valor' ORDER BY id $orden LIMIT 1";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Busqueda Where con Múltiples opciones
    public static function whereArray($array = []) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value) {
            if ($key == array_key_last($array)) {
                $query .= " $key = '$value'";
            } else {
                $query .= " $key = '$value' AND ";
            }
        }
        $query .= " LIMIT 10000"; // Asegura que traiga suficientes registros
        $resultado = self::consultarSQL($query);
        return !empty($resultado) ? $resultado : [];
    }
    

    // Traer un total de registros
    public static function total($columna = '', $valor = '', $columna2 = '', $valor2 = '') {
        $query = "SELECT COUNT(*) FROM " . static::$tabla;
        if($columna) {
            $query .= " WHERE $columna = '$valor'";
        }
        if($columna2) {
            $query .= " AND $columna2 = '$valor2'";
        }
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();
        
        return array_shift($total);
    }

    public static function promedio($columna = '', $valor = '', $campo1 = '', $campo1Valor = '', $campo2 = '', $campo2Valor = '', $campo3 = '', $campo3Valor = '', $campo4 = '', $campo4Valor = '') {
        $queryTotalCondicion = "SELECT COUNT(*) FROM " . static::$tabla;
        $whereConditionsTotal = [];
        if ($campo1) {
            $whereConditionsTotal[] = "$campo1 = '" . self::$db->real_escape_string($campo1Valor) . "'";
        }
        if ($campo2) {
            $whereConditionsTotal[] = "$campo2 = '" . self::$db->real_escape_string($campo2Valor) . "'";
        }
        if ($campo3) {
            $whereConditionsTotal[] = "$campo3 = '" . self::$db->real_escape_string($campo3Valor) . "'";
        }
          //PlanAc
        //if ($campo4) {
          //  $whereConditionsTotal[] = "$campo4 = '" . self::$db->real_escape_string($campo4Valor) . "'";
        //}
        if (!empty($whereConditionsTotal)) {
            $queryTotalCondicion .= " WHERE " . implode(" AND ", $whereConditionsTotal);
        }
    
        $resultadoTotalCondicion = self::$db->query($queryTotalCondicion);
        $totalConPrimeraCondicion = $resultadoTotalCondicion->fetch_array()[0];
    
        if ($totalConPrimeraCondicion == 0) {
            return 0;
        }
    
        $queryCondicional = "SELECT COUNT(*) FROM " . static::$tabla;
        $whereConditions = [];
        
        if ($columna) {
            $whereConditions[] = "$columna = '" . self::$db->real_escape_string($valor) . "'";
        }
        if ($campo1) {
            $whereConditions[] = "$campo1 = '" . self::$db->real_escape_string($campo1Valor) . "'";
        }
        if ($campo2) {
            $whereConditions[] = "$campo2 = '" . self::$db->real_escape_string($campo2Valor) . "'";
        }
        if ($campo3) {
            $whereConditions[] = "$campo3 = '" . self::$db->real_escape_string($campo3Valor) . "'";
        }
        
       // if ($campo4) {
         //   $whereConditions[] = "$campo4 = '" . self::$db->real_escape_string($campo4Valor) . "'";
        //}
        if (!empty($whereConditions)) {
            $queryCondicional .= " WHERE " . implode(" AND ", $whereConditions);
        }
    
        $resultadoCondicional = self::$db->query($queryCondicional);
        $totalConTodasCondiciones = $resultadoCondicional->fetch_array()[0];
    
        $promedio = round(($totalConTodasCondiciones / $totalConPrimeraCondicion) * 100, 2);
        return $promedio;
    }
    
    // Revisa si existe registro
    public function existeRegistro($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE $columna = '$valor'";
        $resultado = self::$db->query($query);
        // if($resultado->num_rows) {
        //     self::$alertas['error'][] = 'El Anfitrión ya está registrado';
        // }
        return $resultado;
    }
    
    //Consulta Plana de SQL (Utilizar cuando los métodos del modelo no son suficientes)
    //La forma correcta es con una clase QueryBuilders que permite unir diferenets tablas
    public static function SQL($query) {
        $resultado = self::consultarSQL(($query));
        return $resultado;
    }

    //Guardar en BD con id AutoIncrementable
    public function crearAI() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();
    
        // Quitar el ID del array de atributos si está presente
        unset($atributos['id']); // Asegúrate de que 'id' es el nombre de tu campo autoincrementable
    
        // Insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= "')";
    
        // Resultado de la consulta
        $resultado = self::$db->query($query);

       if ($resultado){
       $this->id = self::$db->insert_id;
    }

    return $resultado;
}

 /*        return [
            'resultado' => $resultado,
            'id' => self::$db->insert_id // ID del registro recién insertado
        ];
    }     */

    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= "') ";
        
        
        // Resultado de la consulta
        $resultado = self::$db->query($query);
        return [
           'resultado' =>  $resultado,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 
        
        // Actualizar BD
        $resultado = self::$db->query($query);
       
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = '" . self::$db->escape_string($this->id) . "' LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }
//Obtener

}