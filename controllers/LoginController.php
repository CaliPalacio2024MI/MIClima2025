<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;
use Model\Master;

class LoginController {
   public static function login(Router $router) {
    $alertas = [];
    session_start(); // Inicia la sesión al comienzo

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitización de la entrada
        $auth = new Admin([
            'id' => htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8'), // Sanitiza el ID
            'contraseña' => $_POST['contraseña'] // Asegúrate de que la contraseña no se refleje directamente
        ]);

        $alertas = $auth->validarLogin();

        if (empty($alertas)) {
            $admin = Admin::where('id', $auth->id);
            $master = Master::where('id', $auth->id);

            // Manejo de autenticación
            if ($admin && $admin->comprobarPassword($auth->contraseña)) {
                $_SESSION['id'] = $admin->id;
                $_SESSION['admin'] = true; 
                $_SESSION['nombre'] = $admin->nombreAnfitrion;
                $_SESSION['propiedad'] = $admin->propiedades_id;

                // Redirigir a dashboard
                header('Location: /admin/dashboard');
                exit; // Termina el script después de la redirección
            } elseif ($master && $master->comprobarPasswordMaster($auth->contraseña)) {
                $_SESSION['id'] = $master->id;
                $_SESSION['master'] = true;

                // Redirigir a administradores
                header('Location: /admin/administradores');
                exit; // Termina el script después de la redirección
            } else {
                Admin::setAlerta('error', 'Usuario o contraseña incorrectos');
            }
        }
    }

    // Obtener alertas
    $alertas = Admin::getAlertas();
    
    // Renderizar la vista de login
    $router->render('auth/login', [
        'alertas' => $alertas
    ]);
}

    public static function logout() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        $_SESSION = [];
        session_destroy(); // Destruir la sesión para mayor seguridad

        header('Location: /'); // Redirigir a la página principal
        exit; // Termina el script después de la redirección
    }
}

}