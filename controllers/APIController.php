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
    
        // Verificar que ambos parámetros (periodo y propiedad) estén presentes
        if (!$nombre_periodo || !$nombre_propiedad) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Los parámetros nombre_periodo y nombre_propiedad son obligatorios']);
            exit;
        }
    
        // Obtener la propiedad según el nombre proporcionado
        $propiedad = ActiveRecord::first('propiedades', ['nombrePropiedad' => $nombre_propiedad]);
    
        if (!$propiedad) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Propiedad no encontrada']);
            exit;
        }
    
        // Obtener el periodo según el nombre proporcionado y que esté relacionado con la propiedad
        $periodo = ActiveRecord::first('periodos', [
            'periodo' => $nombre_periodo,
            'propiedades_id' => $propiedad->id // Relacionamos el periodo con la propiedad
        ]);
    
        if (!$periodo) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Periodo no encontrado para esta propiedad']);
            exit;
        }
    
        // Obtener los IDs de periodo y propiedad
        $periodo_id = $periodo->id;
        $propiedad_id = $propiedad->id;
    
        // Verificar el departamento si se proporciona
        $departamento_id = null;
        $nombreDepartamentoReal = 'N/A'; // Valor por defecto para evitar "N/A" en la respuesta
    
        if ($nombre_departamento !== null) {
            // Primero verificar el ID de la propiedad
            $propiedad = ActiveRecord::first('propiedades', ['nombrePropiedad' => $nombre_propiedad]);
    
            if (!$propiedad) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Propiedad no encontrada']);
                exit;
            }
    
            // Luego verificar el periodo
            $periodo = ActiveRecord::first('periodos', [
                'periodo' => $nombre_periodo,
                'propiedades_id' => $propiedad->id
            ]);
    
            if (!$periodo) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Periodo no encontrado para esta propiedad']);
                exit;
            }
    
            // Luego, verificar el ID del departamento
            $departamento = ActiveRecord::first('departamentos', [
                'nombreDepartamento' => $nombre_departamento,
                'periodos_id' => $periodo_id,  // Aseguramos que esté dentro del periodo
                'propiedades_id' => $propiedad_id // Aseguramos que esté dentro de la propiedad
            ]);
    
            if (!$departamento) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Departamento no encontrado']);
                exit;
            }
    
            // Si el departamento es válido, procedemos a obtener el ID del departamento
            $departamento_id = $departamento->claveDepto;
            $nombreDepartamentoReal = $departamento->nombreDepartamento;
        }
    
        // Si no se proporciona departamento, buscar todos los departamentos de la propiedad y periodo
        if ($departamento_id === null) {
            // Obtener todos los departamentos relacionados con el periodo y la propiedad
            $departamentos = Departamentos::whereArray([
                'periodos_id' => $periodo_id,
                'propiedades_id' => $propiedad_id
            ]);
    
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
                    'resultado' => $promedio !== null ? $promedio . '%' : 'N/A',
                    'promedio_esperado' => "85.00%" 
                ];
            }
    
            // Respuesta JSON con los resultados filtrados
            header('Content-Type: application/json');
            echo json_encode($resultados);
        } else {
            // Si se proporciona un departamento, verificar si está dentro del periodo y propiedad
            $departamento_periodo = ActiveRecord::first('departamentos', [
                'claveDepto' => $departamento_id,
                'nombreDepartamento' => $nombre_departamento, // Validación del nombre del departamento
                'periodos_id' => $periodo_id,
                'propiedades_id' => $propiedad_id
            ]);
    
            if (!$departamento_periodo) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'El departamento no está asociado a este periodo y propiedad']);
                exit;
            }
    
            // Obtener respuestas del departamento en ese periodo
            $respuestas = ResultadosDeptos::whereArray([
                'departamentos_id' => $departamento_id,
                'periodos_id' => $periodo_id
            ]);
    
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
                'resultado' => $promedio !== null ? $promedio . '%' : 'N/A',
                'promedio_esperado' => "85.00%" 
            ]);
        }
    }
}



