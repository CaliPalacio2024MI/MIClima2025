<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public string $currentPrefix = '';
    // Método para agrupar rutas bajo un prefijo común
    public function group($prefix, $callback)
    {
        // Guardar el prefijo temporalmente
        $previousPrefix = $this->currentPrefix ?? '';

        // Concatenar el nuevo prefijo
        $this->currentPrefix = $previousPrefix . $prefix;

        // Ejecutar el callback para definir las rutas dentro del grupo
        call_user_func($callback, $this);

        // Restaurar el prefijo anterior
        $this->currentPrefix = $previousPrefix;
    }

    public function get($url, $fn)
    {
        // Construir la ruta completa con el prefijo actual
        $fullUrl = $this->currentPrefix . $url;
        $this->getRoutes[$fullUrl] = $fn;
    }

    public function post($url, $fn)
    {
        // Construir la ruta completa con el prefijo actual
        $fullUrl = $this->currentPrefix . $url;
        $this->postRoutes[$fullUrl] = $fn;
    }

    public function comprobarRutas()
    {
        // Obtener la URL actual sin parámetros de consulta
        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // Buscar la función asociada a la ruta
        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }

        if ($fn) {
            // Si la función es un array, asumimos que es un callback [Controlador, Método]
            if (is_array($fn) && is_callable($fn)) {
                // Creamos una instancia del controlador
                $controller = new $fn[0]();
                // Llamamos al método correspondiente pasando el enrutador como argumento
                call_user_func([$controller, $fn[1]], $this);
            } elseif (is_callable($fn)) {
                // Si es un callback, simplemente lo llamamos
                call_user_func($fn, $this);
            } else {
                header('Location: /404');
            }
        } else {
            header('Location: /404');
        }
    }

    public function render($view, $datos = [])
    {
        // Leer lo que le pasamos a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable
        }

        ob_start();

        // Ajustar la construcción de la ruta de la vista
        $viewPath = __DIR__ . "/views/$view.php";
        if (file_exists($viewPath)) {
            include_once $viewPath;
        } else {
            echo "La vista $view.php no se encontró";
        }

        $contenido = ob_get_clean(); // Limpia el Buffer

        // Utilizar el Layout de acuerdo a la URL
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        if (str_contains($url_actual, '/admin')) {
            include_once __DIR__ . '/views/admin-layout.php';
        } else {
            include_once __DIR__ . '/views/layout.php';
        }
    }
}