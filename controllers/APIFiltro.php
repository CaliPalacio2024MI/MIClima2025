<?php

namespace Controllers;

use Model\ResultadosDeptos;

class APIFiltro {


        public static function filtro() {
            $periodo = $_GET['periodos_id'] ?? '';
            $departamento = $_GET['departamentos_id'] ?? '';
            $propiedad = $_GET['idpropiedades'] ?? '';

            $periodo = filter_var($periodo, FILTER_VALIDATE_INT);
            $departamento = filter_var($departamento, FILTER_SANITIZE_SPECIAL_CHARS);
            $propiedad = filter_var($propiedad, FILTER_VALIDATE_INT);
        
            if(!$periodo || !$departamento) {
                echo json_encode([]);
                return;
            }
        
            if ($departamento == "RG") {
                $resultadosFiltrados = ResultadosDeptos::all('periodos_id', $periodo);
                
                // Resultados de los Deptos del periodo anterior para 'RG'
                $resultadoPrevio = ResultadosDeptos::findRG($periodo, $propiedad);
            } else {
                // Aquí obtienes los resultados filtrados de tu base de datos para otros departamentos
                $resultadosFiltrados = ResultadosDeptos::filtrar([
                    'periodos_id' => $periodo, 
                    'departamentos_id' => $departamento
                ]);
            
                // Resultados del Depto del periodo anterior para otros departamentos
                $resultadoPrevio = ResultadosDeptos::findPrevio($periodo, $departamento, $propiedad);
            }
            

            // Crear un array que contenga todas las entidades
            $data = [
                'resultadosFiltrados' => $resultadosFiltrados,
                'resultadoPrevio' => $resultadoPrevio
            ];
        
            header('Content-Type: application/json');
            echo json_encode($data);
        }

        

}
    


     
                // $administradores = Admin::all();

                // foreach($administradores as $administrador) {
                //     $administrador->propiedad = Propiedades::find($administrador->propiedades_id);
                // }