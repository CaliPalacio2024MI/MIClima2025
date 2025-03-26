<?php

namespace Controllers;

use Model\Departamentos;
use Model\Preguntas;
use Model\Periodos;
use Model\Propiedades;
use Model\ResultadosDeptos;
use Model\ActiveRecord;


class APIController{
   
    //
    public static function departamentos() {
        $departamentos = departamentos::all();
        
        // Crear un array que contenga todas las entidades
        $data = [
            'departamentos' => $departamentos
        ];

        // Enviar la respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($data);  
    }

    public static function preguntas() {
        $preguntas = preguntas::all();
        
        // Crear un array que contenga todas las entidades
        $data = [
            'preguntas' => $preguntas
        ];

        // Enviar la respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($data);  
    }

    public static function periodos() {
        $periodos = periodos::all();
        
        // Crear un array que contenga todas las entidades
        $data = [
            'periodos' => $periodos
        ];

        // Enviar la respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($data);  
    }

    public static function propiedades() {
        $propiedades = propiedades::all();
        
        // Crear un array que contenga todas las entidades
        $data = [
            'propiedades' => $propiedades
        ];

        // Enviar la respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($data);  
    }

    public static function indicadores() {
        // Verificar los parámetros recibidos
        $nombre_periodo = $_GET['periodo'] ?? null;
        $nombre_propiedad = $_GET['nombrePropiedad'] ?? null;
        $nombre_departamento = $_GET['nombreDepartamento'] ?? null;


        // Verificar que al menos 'nombre_periodo' o 'nombre_propiedad' estén presentes
        if (!$nombre_periodo || !$nombre_propiedad) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Los parámetros nombre_periodo y nombre_propiedad son obligatorios']);
            exit;
        }
    
        // Obtener el ID del periodo a partir del nombre
        $periodo = ActiveRecord::first('periodos', ['periodo' => $nombre_periodo]);
        if (!$periodo) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Periodo no encontrado']);
            exit;
        }
        $periodo_id = $periodo->id;
    
        // Obtener el ID de la propiedad a partir del nombre
        $propiedad = ActiveRecord::first('propiedades', ['nombrePropiedad' => $nombre_propiedad]);
        if (!$propiedad) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Propiedad no encontrada']);
            exit;
        }
        $propiedad_id = $propiedad->id;
    
        // Obtener el ID del departamento si se proporciona
        $departamento_id = null;
        $nombreDepartamentoReal = 'N/A'; // Valor por defecto para evitar "N/A" en la respuesta
    
        if ($nombre_departamento !== null) {
            $departamento = ActiveRecord::first('departamentos', ['nombreDepartamento' => $nombre_departamento]);
    
            // Depuración: verificar si se encontró el departamento
            error_log("Departamento buscado: " . $nombre_departamento);
            error_log("Resultado de búsqueda: " . json_encode($departamento));
    
            if (!$departamento) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Departamento no encontrado']);
                exit;
            }
            $departamento_id = $departamento->claveDepto;
            $nombreDepartamentoReal = $departamento->nombreDepartamento;
        }
    
        // Si no se proporciona departamento, buscar todos los departamentos de la propiedad y periodo
        if ($departamento_id === null) {
            $departamentos = Departamentos::whereArray([
                'periodos_id' => $periodo_id,
                'propiedades_id' => $propiedad_id
            ]);
    
            // Verificar que se obtuvieron departamentos
            if (!$departamentos || !is_array($departamentos) || empty($departamentos)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No se encontraron departamentos para este periodo y propiedad']);
                exit;
            }
    
            $resultados = [];
            foreach ($departamentos as $departamento) {
                // Obtener respuestas del departamento en ese periodo
                $respuestas = ResultadosDeptos::whereArray([
                    'departamentos_id' => $departamento->claveDepto,
                    'periodos_id' => $periodo_id
                ]);
    
                // Depuración: Verificar la cantidad de respuestas encontradas
                error_log("Consultando respuestas para depto_id: " . $departamento->claveDepto . " en periodo: " . $periodo_id);
                error_log("Total respuestas encontradas: " . count($respuestas));
    
                // Si no hay respuestas, saltamos este departamento
                if (!$respuestas || !is_array($respuestas) || empty($respuestas)) {
                    continue;
                }
    
                // Calcular el promedio
                $suma = 0;
                $conteo = 0;
                foreach ($respuestas as $respuesta) {
                    for ($i = 1; $i <= 16; $i++) {
                        $campo = 'cp' . $i;
                        if (!is_null($respuesta->$campo)) {
                            $suma += floatval($respuesta->$campo);
                            $conteo++;
                        }
                    }
                }
                $promedio = $conteo > 0 ? round($suma / $conteo, 2) : null;
    
                // Guardar resultado
                $resultados[] = [
                    'departamento' => $departamento->nombreDepartamento ?? 'Error en departamento',
                    'promedio' => $promedio !== null ? $promedio . '%' : 'N/A',
                    'promedio_esperado' => "85.00%" 
                ];
            }
    
            // Respuesta JSON
            header('Content-Type: application/json');
            echo json_encode($resultados);
        } 
        // Si se proporciona un departamento, buscar solo ese
        else {
            $respuestas = ResultadosDeptos::whereArray([
                'departamentos_id' => $departamento_id,
                'periodos_id' => $periodo_id
            ]);
    
            // Depuración: Verificar la cantidad de respuestas encontradas
            error_log("Consultando respuestas para depto_id: " . $departamento_id . " en periodo: " . $periodo_id);
            error_log("Total respuestas encontradas: " . count($respuestas));
    
            // Verificar que se obtuvieron respuestas
            if (!$respuestas || !is_array($respuestas) || empty($respuestas)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No se encontraron respuestas para el departamento']);
                exit;
            }
    
            // Calcular el promedio
            $suma = 0;
            $conteo = 0;
            foreach ($respuestas as $respuesta) {
                for ($i = 1; $i <= 16; $i++) {
                    $campo = 'cp' . $i;
                    if (!is_null($respuesta->$campo)) {
                        $suma += floatval($respuesta->$campo);
                        $conteo++;
                    }
                }
            }
            $promedio = $conteo > 0 ? round($suma / $conteo, 2) : null;
    
            // Respuesta JSON para el departamento específico
            header('Content-Type: application/json');
            echo json_encode([
                'departamento' => $nombreDepartamentoReal,
                'promedio' => $promedio !== null ? $promedio . '%' : 'N/A',
                'promedio_esperado' => "85.00%" 
            ]);
        }
    }
    
    

}



