<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function is_admin() : bool {
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

function is_master() : bool {
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['master']) && !empty($_SESSION['master']);
}

//Validar la URL por un ID válido
function validarORedireccionar(string $url) {
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header("Location: $url " );
    }

    return $id;
}

function pagina_actual($path ) : bool {
    return str_contains( $_SERVER['PATH_INFO'] ?? '/', $path  ) ? true : false;
}

function contarPorGenero($periodoId) {
    global $db; // Utilizar la instancia global de la base de datos

    // Primero, obtener el total de registros para el periodo_id
    $queryTotal = "SELECT COUNT(*) as total FROM resultados WHERE periodos_id = '$periodoId'";
    $resultadoTotal = $db->query($queryTotal);
    $totalRegistros = $resultadoTotal->fetch_assoc()['total'];

    // Si no hay registros, devolver un array vacío para evitar división por cero
    if ($totalRegistros == 0) {
        return [];
    }

    // Luego, obtener el conteo por género
    $query = "
        SELECT genero, COUNT(*) as conteo 
        FROM resultados 
        WHERE periodos_id = '$periodoId' 
        GROUP BY genero
    ";

    $resultado = $db->query($query);

    $conteos = [];
    while ($registro = $resultado->fetch_assoc()) {
        $porcentaje = ($registro['conteo'] / $totalRegistros) * 100;
        $conteos[] = [
            'genero' => $registro['genero'],
            'porcentaje' => round($porcentaje) // Redondear al segundo decimal
        ];
    }

    return $conteos;
}


