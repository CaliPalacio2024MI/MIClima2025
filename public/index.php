<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\APIController;
use Controllers\AdminController;
use Controllers\APIFiltro;
use Controllers\LoginController;
use Controllers\PaginasController;

$router = new Router(); //Instancia de Router para mostrar vistas

//Iniciar Sesión
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->post('/logout', [LoginController::class, 'logout']);

// ZONA PÚBLICA
$router->get('/', [PaginasController::class, 'index']);
$router->get('/anonimo', [PaginasController::class, 'anonimored']);
$router->get('/encuesta', [PaginasController::class, 'responder']);
$router->post('/encuesta', [PaginasController::class, 'responder']);
$router->get('/encuesta/api', [APIController::class, 'departamentos']);
$router->get('/encuesta/preguntas', [APIController::class, 'preguntas']);
$router->get('/resultados/index', [APIController::class, 'preguntas']);
$router->get('/resultados/departamentos', [APIController::class, 'departamentos']);
$router->get('/encuesta/periodos', [APIController::class, 'periodos']);
$router->get('/404', [PaginasController::class, 'error']);
$router->get('/respuestas-enviadas', [PaginasController::class, 'volver']);
$router->get('/plan-de-accion', [AdminController::class, 'planAccion']);
$router->post('/upload', [AdminController::class, 'upload']);
$router->get('/plan-enviado', [PaginasController::class, 'regresar']);
$router->post('/planupload', [AdminController::class, 'planes']);
$router->get('/noplan', [PaginasController::class, 'planNo']);
$router->get('/inicio', [PaginasController::class, 'iniciar']);
$router->post('/verificar-plan', [AdminController::class, 'verificarPlanAC']);
$router->get('/obtenerdepto', [AdminController::class, 'obtener_departamento']);

// ZONA PRIVADA
$router->get('/admin/dashboard', [AdminController::class, 'index']);
// Areas
$router->get('/admin/areas', [AdminController::class, 'areas']);
$router->get('/admin/areas-crear', [AdminController::class, 'crearArea']);
$router->post('/admin/areas-crear', [AdminController::class, 'crearArea']);
$router->get('/admin/areas-actualizar', [AdminController::class, 'actualizarArea']);
$router->post('/admin/areas-actualizar', [AdminController::class, 'actualizarArea']);
$router->post('/admin/areas-eliminar', [AdminController::class, 'eliminarArea']);
// Progreso
$router->get('/admin/progreso', [AdminController::class, 'progreso']);
// Resultado
$router->get('/admin/resultados', [AdminController::class, 'resultados']);
// $router->post('/admin/resultados', [AdminController::class, 'resultados']);
$router->get('/resultados/api', [APIFiltro::class, 'filtro']);

$router->group('/api', (function($router) {
    $router->get('/indicadores', [APIController::class, 'indicadores']);
    $router->get('/preguntas', [APIController::class, 'preguntas']);
    $router->get('/periodos', [APIController::class, 'periodos']);
}));

// Registrar Users USUARIO MASTER
$router->get('/admin/administradores', [AdminController::class, 'administradores']);
$router->get('/admin/administradores-crear', [AdminController::class, 'crearAdministradores']);
$router->post('/admin/administradores-crear', [AdminController::class, 'crearAdministradores']);
$router->get('/admin/administradores-actualizar', [AdminController::class, 'actualizAradministradores']);
$router->post('/admin/administradores-actualizar', [AdminController::class, 'actualizAradministradores']);
$router->post('/admin/administradores-eliminar', [AdminController::class, 'eliminarAdministradores']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();





