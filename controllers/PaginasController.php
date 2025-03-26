<?php

namespace Controllers;

use Model\Antiguedades;
use Model\Departamentos;
use Model\Edades;
use Model\Generos;
use Model\Globales;
use Model\Periodos;
use Model\Preguntas;
use Model\Propiedades;
use Model\Resultados;
use Model\ResultadosDeptos;
use Model\TipoPuestos;
use MVC\Router;

class PaginasController {
    public static function index( Router $router) {

        $inicio = true;

        $router->render('paginas/index', [
            'inicio' => $inicio
        ]);
    }

    public static function responder(Router $router) {
        $alertas = [];
    
        $resultado = new Resultados; // Instancia vacía
    
        $propiedades = Propiedades::all();
        $areas = Departamentos::all();
        $generos = Generos::all();
        $edades = Edades::all();
        $tipoPuestos = TipoPuestos::all();
        $antiguedades = Antiguedades::all();
    
        $preguntas = Preguntas::all();
        $contador = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultado->sincronizar($_POST);
        
            // Obtener el id del último periodo de esa Propiedad
            $resultado->idpropiedades = $resultado->periodos_id;
            $lastPeriodo = Periodos::where('propiedades_id', $resultado->periodos_id, 'DESC');
            $resultado->periodos_id = $lastPeriodo->id;
    
            $alertas = $resultado->validar();
    
            if (empty($alertas)) {
                // Guardar en BD Resultados
                $resultadoBD = $resultado->crearAI();
    
                if ($resultadoBD) {
                    // ACTUALIZAR ESTADÍSTICAS GLOBALES
                    $globalExistente = Globales::where('periodos_id', $resultado->periodos_id); // Buscar registro global existente
                    if ($globalExistente) {
                        $globalExistente->cantidad++; // Incrementar la cantidad
    
                        // Actualizar promedios 
                        $totalPorcentaje = 0;
                        for ($i = 1; $i <= 87; $i++) {
                            $campo = "cp" . $i;
                            $pregunta = "p" . $i;
                            $valor = ($i == 1 || $i == 3) ? 'Negativo' : 'Positivo'; // 'Negativo' para p1 y p3, 'Positivo' para los demás
    
                            
                            if($i <= 16){
                            $promedio = Resultados::promedio($pregunta, $valor, 'periodos_id', $resultado->periodos_id, 'idpropiedades', $resultado->idpropiedades);
                            $globalExistente->$campo = $promedio; // Asignar el promedio al campo correspondiente
                            $totalPorcentaje += $promedio;
                            }else{
                            $globalExistente->$campo = 0;
                        }
                        }
    
                        $globalExistente->porcentaje = round($totalPorcentaje / 16, 2);
                        $globalExistente->guardar(); // Actualizar cambios
                    } else {
                        // Si no existe, crear nuevo registro
                        $nuevoGlobal = new Globales([
                            'cantidad' => 1,
                            'periodos_id' => $resultado->periodos_id,
                            'idpropiedades' => $resultado->idpropiedades
                        ]);
    
                        // Asignar valores calculados a campos adicionales si es necesario
                        $totalPorcentaje = 0;
                        for ($i = 1; $i <= 87; $i++) {
                            $campo = "cp" . $i;
                            $pregunta = "p" . $i;
                            $valor = ($i == 1 || $i == 3) ? 'Negativo' : 'Positivo';
    
                            if($i <= 16){
                            $promedio = Resultados::promedio($pregunta, $valor, 'periodos_id', $resultado->periodos_id, 'idpropiedades', $resultado->idpropiedades);
                            $nuevoGlobal->$campo = $promedio;
                            $totalPorcentaje += $promedio;
                            }else{
                            $nuevoGlobal->$campo = 0;
                        }

                        }
    
                        $nuevoGlobal->porcentaje = round($totalPorcentaje / 16, 2);
                        $nuevoGlobal->crearAI(); // Guardar nuevo registro
                    }
    
                    // ACTUALIZAR ESTADÍSTICAS DE DEPARTAMENTOS

                    $resultadoDeptoExistente = ResultadosDeptos::whereArray([
                        'periodos_id' => $resultado->periodos_id,
                        'departamentos_id' => $resultado->departamentos_id,
                        'idpropiedades' => $resultado->idpropiedades
                    ]);

                    if ($resultadoDeptoExistente) {
                    
                        // Actualizar promedios 
                        for ($i = 1; $i <= 87; $i++) {
                            $campo = "cp" . $i;
                            $pregunta = "p" . $i;
                            $valor = ($i == 1 || $i == 3) ? 'Negativo' : 'Positivo';

                            if($i <= 16){
                            $promedio = Resultados::promedio($pregunta, $valor, 'periodos_id', $resultado->periodos_id, 'departamentos_id', $resultado->departamentos_id, 'idpropiedades', $resultado->idpropiedades);
                            $resultadoDeptoExistente->$campo = $promedio; // Asignar el promedio al campo correspondiente
                        }else{
                            $resultadoDeptoExistente->$campo = 0;
                        }
                        }

                        $resultadoDeptoExistente->guardar(); // Actualizar cambios
                    } else {

                        // Si no existe, crear nuevo registro
                        $nuevoResultadoDepto = new ResultadosDeptos([
                            'departamentos_id' => $resultado->departamentos_id,
                            'periodos_id' => $resultado->periodos_id,
                            'idpropiedades' => $resultado->idpropiedades
                        ]);
                    
                        for ($i = 1; $i <= 87; $i++) {
                            $campo = "cp" . $i;
                            $pregunta = "p" . $i;
                            $valor = ($i == 1 || $i == 3) ? 'Negativo' : 'Positivo';
                            if($i <= 16){
                                $promedio = Resultados::promedio($pregunta, $valor, 'periodos_id', $resultado->periodos_id, 'departamentos_id', $resultado->departamentos_id, 'idpropiedades', $resultado->idpropiedades);
                                $nuevoResultadoDepto->$campo = $promedio;
                            }else{
                                $nuevoResultadoDepto->$campo = 0;
                            }
                        }
                        $nuevoResultadoDepto->PlanAC = "NULL";
                        $nuevoResultadoDepto->crearAI(); // Guardar nuevo registro
                    }
    
                    header('Location: /respuestas-enviadas');
                }
            }
        }
    
        $router->render('paginas/encuesta', [
            'propiedades' => $propiedades,
            'areas' => $areas,
            'generos' => $generos,
            'edades' => $edades,
            'tipoPuestos' => $tipoPuestos,
            'antiguedades' => $antiguedades,
            'preguntas' => $preguntas,
            'contador' => $contador,
            'resultado' => $resultado,
        ]);
    }

    public static function volver( Router $router) {

        $router->render('paginas/enviado', [
            
        ]);
    }

    public static function regresar( Router $router) {

        $router->render('paginas/plan', [
            
        ]);
    }

    public static function planNo( Router $router) {

        $router->render('paginas/noplan', [
            
        ]);
    }
    
     public static function anonimored( Router $router) {

        $router->render('paginas/anonimo', [
            
        ]);
    }


    public static function error(Router $router) {

        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada'
        ]);
    }
    // public static function admin( Router $router) {
    //     $router->render('paginas/usuario', [

    //     ]);
    // }
}