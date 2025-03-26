<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Model\Admin;
use Model\Departamentos;
use Model\Globales;
use Model\Periodos;
use Model\Preguntas;
use Model\Propiedades;
use Model\Resultados;
use Model\ResultadosDeptos;
use Model\AdminPropiedades;



class AdminController extends ActiveRecord{

    public static function index( Router $router) {
        if(!is_admin()) { //Maybe un método diferente para TH 
            header('Location: /login');
        }

        $propiedad = $_SESSION['propiedad']; //Obtener el ID de Propiedad de User

        $namePropiedad = Propiedades::find($propiedad); //Name Propeidad H3

        // debuguear($namePropiedad);

        $totales = Periodos::all('propiedades_id', $propiedad, 'id DESC', '3'); //Ultimos 3 periodos de esa Propiedad
        
        //Calificaciones globales de esos 3 periodos
        foreach ($totales as $total) {
            $total->globales = Globales::where('periodos_id', $total->id);

            if($total->globales) {
                //Asignar nombre del icono con base a porcentaje
                if($total->globales->porcentaje >= 90) {
                    $total->globales->icono = 'Capitalizar';
                } else if($total->globales->porcentaje >= 85) {
                    $total->globales->icono = 'Optimizar';
                } else if($total->globales->porcentaje >= 80) {
                    $total->globales->icono = 'Mejorar';
                } else {
                    $total->globales->icono = 'Corregir';
                }
            }     
        }
        
        // debuguear($totales);
        
        $router->render('admin/index', [
            'totales' => array_reverse($totales), //array_reverse para cambiar reordenar a ASC
            'namePropiedad' => $namePropiedad
        ]);
    }

    public static function areas(Router $router) {
        if (!is_admin()) { 
            header('Location: /login');
            exit; // Importante salir después de redirigir para evitar que el código continúe ejecutándose
        }
    
        $propiedad_id = $_SESSION['propiedad'];
    
        // Obtener el último período de esa propiedad
        $lastPeriodo = Periodos::where('propiedades_id', $propiedad_id, 'DESC');
    
        // Verificar si se encontró algún período
        if ($lastPeriodo) {
            // Obtener los departamentos de esa propiedad y último período
            $areas = Departamentos::filtrar(['propiedades_id' => $propiedad_id, 'periodos_id' => $lastPeriodo->id], 'id ASC');
            
            foreach ($areas as $area) {
                $area->propiedad = Propiedades::find($area->propiedades_id);
            }
        } else {
            // Si no hay períodos registrados para esa propiedad, inicializar $areas como un array vacío
            $areas = [];
        }
    
        $router->render('admin/area/index', [
            'areas' => $areas,
            'lastPeriodo' => $lastPeriodo
        ]);
    }
    
    public static function crearArea(Router $router) {
    if (!is_admin()) {
        header('Location: /login');
        exit;
    }

    $propiedad = $_SESSION['propiedad'];
    $propiedades = Propiedades::all('id', $propiedad); // Consulta BD Propiedad

    // Obtener el último periodo
    $lastPeriodo = Periodos::where('propiedades_id', $propiedad, 'DESC'); // Último periodo

    if (!$lastPeriodo) {
        $alertas['error'][] = 'No se encontró un periodo para la propiedad seleccionada.';
        $router->render('admin/area/crear', [
            'propiedades' => $propiedades,
            'alertas' => $alertas ?? [],
        ]);
        return;
    }

    // Obtener el periodo anterior al último
    $previousPeriodo = Periodos::consultarSQL("
        SELECT * 
        FROM periodos
        WHERE propiedades_id = '$propiedad' 
        AND id < '{$lastPeriodo->id}' 
        ORDER BY id DESC
        LIMIT 1
    ");

    // Si no encontramos un periodo anterior, mostrar un error
    if (!$previousPeriodo) {
        $alertas['error'][] = 'No existe un periodo anterior para la propiedad seleccionada.';
        $router->render('admin/area/crear', [
            'propiedades' => $propiedades,
            'alertas' => $alertas ?? [],
        ]);
        return;
    }

    // Instancia vacía para el área
    $area = new Departamentos;

    // Buscar departamentos en el periodo anterior y, si no los encontramos, buscar en el anterior a ese
    $departamentosAnterior = [];
    while (!$departamentosAnterior) {
        // Obtener los departamentos asociados al periodo anterior
        $departamentosAnterior = Departamentos::filtrar([
            'propiedades_id' => $propiedad,
            'periodos_id' => $previousPeriodo[0]->id // Usamos el primer resultado del array
        ], 'claveDepto ASC');

        if (!$departamentosAnterior) {
            // Si no hay departamentos en este periodo, obtenemos el periodo anterior al actual
            $previousPeriodo = Periodos::consultarSQL("
                SELECT * 
                FROM periodos
                WHERE propiedades_id = '$propiedad' 
                AND id < '{$previousPeriodo[0]->id}' 
                ORDER BY id DESC
                LIMIT 1
            ");

            // Si ya no hay más periodos anteriores, salimos del bucle
            if (!$previousPeriodo) {
                break;
            }
        }
    }

    // Si no se encontraron departamentos, mostrar un error
    if (!$departamentosAnterior) {
        $alertas['error'][] = 'No se encontraron departamentos en los periodos anteriores para la propiedad seleccionada.';
        $router->render('admin/area/crear', [
            'propiedades' => $propiedades,
            'alertas' => $alertas ?? [],
        ]);
        return;
    }

    // Obtener los departamentos asociados al último periodo
    $departamentosUltimo = Departamentos::filtrar([
        'propiedades_id' => $propiedad,
        'periodos_id' => $lastPeriodo->id
    ], 'claveDepto ASC');

    // Comparar los departamentos del último periodo con los del periodo anterior
    // y agregar los que no tengan el mismo nombreDepartamento
    $departamentosAAgregar = [];

    foreach ($departamentosUltimo as $departamentoUltimo) {
        $existe = false;
        foreach ($departamentosAnterior as $departamentoAnterior) {
            // Comparar los nombres de los departamentos
            if ($departamentoUltimo->nombreDepartamento == $departamentoAnterior->nombreDepartamento) {
                $existe = true;
                break; // Si el nombre coincide, no agregamos el departamento
            }
        }

        // Si no existe en el periodo anterior, agregar al array
        if (!$existe) {
            $departamentosAAgregar[] = $departamentoUltimo;
        }
    }

    // Unir los departamentos del periodo anterior con los nuevos departamentos del último periodo
    $departamentosFinal = array_merge($departamentosAnterior, $departamentosAAgregar);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $area->sincronizar($_POST);

        // **CAMBIO AQUI: Asignamos el periodo al último periodo (no al periodo anterior)**
        $area->periodos_id = $lastPeriodo->id;  // Este es el último periodo, no el anterior.

        // Validación: Comprobar si ya existe un departamento con la misma claveDepto en el último periodo
        $departamentoExistente = Departamentos::consultarSQL("
            SELECT * 
            FROM departamentos
            WHERE claveDepto = '{$area->claveDepto}' 
            AND periodos_id = '{$lastPeriodo->id}'
        ");

        if ($departamentoExistente) {
            // Si el departamento ya existe, mostrar un error
            $alertas['error'][] = 'El departamento con la clave ' . $area->claveDepto . ' ya existe en este periodo.';
        } else {
            // Si no existe, procedemos con la validación y creación
            $alertas = $area->validar();

            if (empty($alertas)) {
                // Guardar en la BD
                $resultado = $area->crearAI();

                if ($resultado) {
                    header('Location: /admin/areas');
                    exit;
                } else {
                    $alertas['error'][] = 'Error al crear el departamento.';
                }
            }
        }
    }

    // Renderizar la vista con los departamentos finales
    $router->render('admin/area/crear', [
        'propiedades' => $propiedades,
        'departamentos' => $departamentosFinal, // Pasamos los departamentos final con los agregados
        'area' => $area,
        'alertas' => $alertas ?? [],
    ]);
}
    
    public static function actualizarArea(Router $router) {
        if (!is_admin()) {
            header('Location: /login');
            exit();
        }
    
        $alertas = [];
    
        // Sanitizar o Escapar SIEMPRE LOS ID de GET
        $id = $_GET['id'] ?? null;
        $id = s($id);
    
        if (!$id) {
            header('Location: /admin/areas');
            exit();
        }
    
        // Obtener los datos del Area
        $area = Departamentos::find($id);
        if (!$area) {
            header('Location: /admin/areas');
            exit();
        }
    
        $propiedad = $_SESSION['propiedad'] ?? null;
        $propiedades = Propiedades::all('id', $propiedad); // Consulta BD Propiedades
    
        // Obtener el último periodo
        $lastPeriodo = Periodos::where('propiedades_id', $propiedad, 'DESC'); // Último periodo
    
        if (!$lastPeriodo) {
            $alertas['error'][] = 'No se encontró un periodo para la propiedad seleccionada.';
            $router->render('admin/area/actualizar', [
                'propiedades' => $propiedades,
                'alertas' => $alertas ?? [],
            ]);
            return;
        }
    
        // Obtener el periodo anterior al último
        $previousPeriodo = Periodos::consultarSQL("
            SELECT * 
            FROM periodos
            WHERE propiedades_id = '$propiedad' 
            AND id < '{$lastPeriodo->id}' 
            ORDER BY id DESC
            LIMIT 1
        ");
    
        // Si no encontramos un periodo anterior, mostrar un error
        if (!$previousPeriodo) {
            $alertas['error'][] = 'No existe un periodo anterior para la propiedad seleccionada.';
            $router->render('admin/area/actualizar', [
                'propiedades' => $propiedades,
                'alertas' => $alertas ?? [],
            ]);
            return;
        }
    
        // Obtener los departamentos asociados al periodo anterior
        $departamentosAnterior = Departamentos::filtrar([
            'propiedades_id' => $propiedad,
            'periodos_id' => $previousPeriodo[0]->id // Usamos el primer resultado del array
        ], 'claveDepto ASC');
    
        // Obtener los departamentos asociados al último periodo
        $departamentosUltimo = Departamentos::filtrar([
            'propiedades_id' => $propiedad,
            'periodos_id' => $lastPeriodo->id
        ], 'claveDepto ASC');
    
        // Comparar los departamentos del último periodo con los del periodo anterior
        // y agregar los que no tengan el mismo nombreDepartamento
        $departamentosAAgregar = [];
    
        foreach ($departamentosUltimo as $departamentoUltimo) {
            $existe = false;
            foreach ($departamentosAnterior as $departamentoAnterior) {
                // Comparar los nombres de los departamentos
                if ($departamentoUltimo->nombreDepartamento == $departamentoAnterior->nombreDepartamento) {
                    $existe = true;
                    break; // Si el nombre coincide, no agregamos el departamento
                }
            }
    
            // Si no existe en el periodo anterior, agregar al array
            if (!$existe) {
                $departamentosAAgregar[] = $departamentoUltimo;
            }
        }
    
        // Unir los departamentos del periodo anterior con los nuevos departamentos del último periodo
        $departamentosFinal = array_merge($departamentosAnterior, $departamentosAAgregar);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
                exit();
            }
    
            // Sincronizar los datos con el formulario
            $area->sincronizar($_POST);
    
            $alertas = $area->validar();
    
            if (empty($alertas)) {
                // Verificar dependencias antes de guardar
                $dependencias = ResultadosDeptos::whereArray(['departamentos_id' => $id]);
    
                if (empty($dependencias)) {
                    $resultado = $area->guardar();
                    if ($resultado) {
                        header('Location: /admin/areas');
                        exit();
                    } else {
                        $alertas[] = "Error al guardar el área.";
                    }
                } else {
                    $alertas[] = "No se puede actualizar el área porque tiene dependencias en ResultadosDeptos.";
                }
            }
        }
    
        // Renderizar la vista de actualización del área
        $router->render('admin/area/actualizar', [
            'area' => $area,
            'propiedades' => $propiedades,
            'departamentos' => $departamentosFinal,  // Pasamos los departamentos filtrados
            'alertas' => $alertas
        ]);
    }

    public static function eliminarArea () {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_admin()) { 
                header('Location: /login');
            }

            $id = $_POST['id'];
            $area = Departamentos::find($id);
            if(!isset($area)) {
                header('Location: /admin/areas');
            }
            $resultado = $area->eliminar();
            if($resultado) {
                header('Location: /admin/areas');
            }
        }
    }
    
    public static function progreso(Router $router) {
        if (!is_admin()) { 
            header('Location: /login');
        }
    
        $propiedad = $_SESSION['propiedad'];
        $area = Departamentos::all('propiedades_id', $propiedad);
        
        $propiedad =$_SESSION['propiedad'];
        // Obtener el último período de esa propiedad
        $lastPeriodo = Periodos::where('propiedades_id', $propiedad, 'DESC');

        $areas = Departamentos::filtrar(['propiedades_id' => $propiedad, 'periodos_id' => $lastPeriodo->id], 'claveDepto ASC');

        foreach($areas as $area) {

            if($lastPeriodo){
                $area->evaluados = Resultados::total('departamentos_id', $area->claveDepto, 'periodos_id', $lastPeriodo->id); //Cantidad que ya respondió de ese depto
            } else {
                $area->evaluados = 0; //Aún no han evaludo
            }
            $area->faltante =  $area->cantidad - $area->evaluados; //Cantidad que no ha respondido encuesta
            $area->porcentaje = intval(($area->evaluados / $area->cantidad) * 100);
        }
    
        $router->render('admin/progreso/index', [
            'areas' => $areas,
            'lastPeriodo' => $lastPeriodo
        ]);
    }


    public static function resultados(Router $router) {
        if(!is_admin()) { 
            header('Location: /login');
        }

        $preguntas = Preguntas::all();
        $propiedad = $_SESSION['propiedad']; //Obtener Propiedad
        $areas = Departamentos::all('propiedades_id', $propiedad);
        $periodos = Periodos::all('propiedades_id', $propiedad); // Periodos de esa Propiedad

        $lastPeriodo = Periodos::where('propiedades_id', $propiedad, 'DESC'); //último periodo de esa propiedad
        //Datos del género
        if($lastPeriodo) {
            $conteoGeneros = contarPorGenero($lastPeriodo->id); // Asegúrate de reemplazar con el ID del período específico
        } else {
            $conteoGeneros = null;
        }
        // debuguear($conteoGeneros);

        $globales = [];

        // Iterar sobre cada periodo
        foreach ($periodos as $periodo) {
            // Obtener el global asociado al id del periodo
            $global = Globales::where('periodos_id', $periodo->id);
 
            // Verificar si existe un global asociado
            if($global) {
                // Agregar el nombre del periodo al global
                $global->periodo = $periodo->periodo;
                $globales[] = $global;
            }
        }        


        
        $router->render('admin/resultados/index', [
            'preguntas' => $preguntas,
            'globales' => array_reverse($globales),
            'periodos' => $periodos,
            'areas' => $areas,
            'conteoGeneros' => $conteoGeneros
        ]);
    }
    
    
    public static function administradores (Router $router) {
        if(!is_master()) {
            header('Location: /login');
        }

        $administradores = Admin::all();

        foreach($administradores as $administrador) {
            $administrador->propiedad = Propiedades::find($administrador->propiedades_id);
        }

        $router->render('admin/administradores/index', [
            'administradores' => $administradores
        ]);
    }

    public static function crearAdministradores(Router $router) {
        if (!is_master()) {
            header('Location: /login');
            exit;
        }
    
        $alertas = [];
        $administrador = new Admin(); // Instancia vacía
        $propiedades = Propiedades::all(); // Consulta BD Propiedad
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_master()) {
                header('Location: /login');
                exit;
            }
    
            // Sincronizamos los datos
            $datos = $_POST;
            unset($datos['propiedades_id']); // Excluimos `propiedades_id` temporalmente
            $administrador->sincronizar($datos);
    
            // Validamos los datos
            $alertas = $administrador->validar();
    
            if (empty($alertas)) {
                $resultado = $administrador->existeUsuario();
    
                if ($resultado->num_rows) {
                    $alertas = Admin::getAlertas();
                } else {
                    // Guarda el Administrador en la base de datos
                    $resultado = $administrador->crear();
    
                    // Si el administrador se creó correctamente
                    if ($resultado) {
                        // Insertamos las relaciones entre administrador y propiedades
                        if (!empty($_POST['propiedades_id']) && is_array($_POST['propiedades_id'])) {
                            foreach ($_POST['propiedades_id'] as $propiedad_id) {
                                $adminPropiedad = new AdminPropiedades([
                                    'admin_id' => $administrador->id,
                                    'propiedad_id' => $propiedad_id
                                ]);
                                $adminPropiedad->insertarRelacion();
                            }
                        }
    
                        // Redirigimos al listado de administradores
                        header('Location: /admin/administradores');
                        exit;
                    }
                }
            }
        }
    
        $router->render('admin/administradores/crear', [
            'administrador' => $administrador,
            'propiedades' => $propiedades,
            'alertas' => $alertas
        ]);
    }
    public static function actualizarAdministradores (Router $router) {
        if(!is_master()) { 
            header('Location: /login');
        }
        $alertas = [];

        // Sanitizar o Escapar SIEMPRE LOS ID de GET
        $id = $_GET['id'];
        $id = s($id);

        if(!$id) {
            header('Location: /admin/administradores');
        }

        // Obtener los datos del Administardor
        $administrador = Admin::find($id);
        $propiedades = Propiedades::all(); // Consulta BD Propiedades
    
        if(!$administrador) {
            header('Location: /admin/th');
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_master()) {
                header('Location: /login');
            }
            
            $administrador->sincronizar($_POST);
            $alertas = $administrador->validar();
            
            if(empty($alertas)) {
                // Guarda el Anfitrión en BD
                $resultado = $administrador->guardar();

                if($resultado) {
                    header('Location: /admin/administradores');
                }
            }
        }

        $router->render('admin/administradores/actualizar', [
            'administrador' => $administrador,
            'propiedades' => $propiedades,
            'alertas' => $alertas
        ]);
    }

    public static function eliminarAdministradores (Router $router) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_master()) { 
                header('Location: /login');
            }

            $id = $_POST['id'];
            $administrador = Admin::find($id);
            if(!isset($administrador)) {
                header('Location: /admin/administradores');
            }
            $resultado = $administrador->eliminar();
            if($resultado) {
                header('Location: /admin/administradores');
            }
        }
    }

    public static function planAccion() {

        $file =  __DIR__ . '/../public/archivos/Plan de c, ac y m - Mundo Imperial.xlsx';
        // debuguear($file);
        
        if (file_exists($file)) { // Verifica si el archivo existe
            header('Content-Description: File Transfer'); // Configura los encabezados para la descarga del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // Tipo MIME para archivos de Excel
            header('Content-Disposition: attachment; filename="' . basename($file) . '"'); // attachment fuerza descarga
            header('Expires: 0'); // Evitar caché, expirar de inmediato
            header('Cache-Control: must-revalidate'); // Navegador debe validar el archivo con el servidor antes de usar copia en caché
            header('Pragma: public'); // Control de caché HTTP/1.0 (respuesta accesible publicamente)
            header('Content-Length: ' . filesize($file)); // Tamaño del archivo
            flush(); // Limpia el búfer del sistema
            readfile($file); // Lee y envía el contenido del archivo
            exit; // Termina el script
        } else {
            // Manejar el caso en que el archivo no existe
            http_response_code(404);
            echo "El archivo no se encuentra.";
            exit;
        }
    }


    public static function upload($router) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_FILES["planAccion"]) && $_FILES["planAccion"]["error"] == 0) {
                // Ruta donde se almacenará la imagen
                $directorio_destino = __DIR__ . "/../public/uploads/";
    
                // Generar un nombre único para el archivo
                $nombre_archivo = uniqid() . '_' . basename($_FILES["planAccion"]["name"]);
    
                // Mover el archivo al directorio de destino
                if (move_uploaded_file($_FILES["planAccion"]["tmp_name"], $directorio_destino . $nombre_archivo)) {
                    // La imagen se ha subido correctamente
    
                    // Obtener los datos del formulario
                    $departamento_id = $_POST["departamento"];
                    $periodo_id = $_POST["periodo"];
    
                    // Crear una instancia del modelo que maneja el registro en la base de datos
                    $registro = new ResultadosDeptos();
    
                    // Actualizar el plan de acción en la base de datos
                    $resultado = $registro->actualizarPlanAC($departamento_id, $periodo_id, $nombre_archivo);
    
                    if ($resultado) {
                        header('Location: /plan-enviado');
                    } else {
                        echo "<script>
                            alert('No se han capturado resultados en el departamento / Sin plan de accion.');
                            window.location.reload();
                        </script>";
                    }
                } else {
                    echo "Ocurrió un error al subir la imagen.";
                }
            } else {
                echo "Por favor selecciona una imagen.";
            }
        }
    }

    public static function planes($router) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["departamento"]) && isset($_POST["periodo"])) {
        // Obtener los valores del formulario
        $departamento_id = $_POST["departamento"];
        $periodo_id = $_POST["periodo"];
    
        // Crear una instancia del modelo que maneja el registro en la base de datos
        $registro = new ResultadosDeptos();
    
        // Obtener la ruta de la imagen del plan de acción
        $rutaImagen = $registro->obtenerRutaImagen($departamento_id, $periodo_id);
    
        // Verificar si se encontró una ruta de imagen
        if ($rutaImagen) {
            ?>

<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMaF6R0Ox+pb8Y5/4h52c5w/igT93HklDVIftfN" crossorigin="anonymous">
</head>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }

        .plan-accion-container {
            text-align: center; /* Centrar horizontalmente */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh; /* Ajusta según sea necesario */
        }

        .plan-accion-imagen {
            max-width: 80%; /* Ajusta el tamaño máximo al 80% del contenedor */
            height: auto; /* Mantiene la proporción de la imagen */
            margin: auto;
        }

        .boton-volver, .btn {
            display: inline-block;
            padding: 10px 20px; /* Ajustado para mayor consistencia */
            margin: 10px;
            font-size: 18px; /* Ajusta el tamaño de la fuente */
            font-weight: 700; /* Hace la fuente más gruesa */
            color: #fff;
            background-color: #007bff; /* Color azul */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }

        .boton-volver:hover, .btn:hover {
            background-color: #0056b3; /* Color azul más oscuro */
        }

        .boton-volver:active, .btn:active {
            background-color: #004494; /* Color azul aún más oscuro */
        }

        .boton-volver:focus, .btn:focus {
            outline: none;
            box-shadow: 0 0 5px #007bff; /* Sombra azul */
        }

        .enviadotexto {
            width: 50px;
            height: 50px;
            width: 100%;
            height: auto;
        }

        .botones-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 {
            font-size: 36px; /* Ajusta el tamaño del encabezado */
            font-weight: 700; /* Hace el encabezado más grueso */
            margin-bottom: 20px;
            font-family: 'Roboto', sans-serif;
        }

        h2 {
            font-size: 24px; /* Ajusta el tamaño del subencabezado */
            font-weight: 400; /* Hace el subencabezado más delgado */
            margin-bottom: 15px;
        }

        p {
            font-size: 16px; /* Ajusta el tamaño del texto del párrafo */
            font-weight: 400; /* Hace el texto del párrafo más delgado */
            margin-bottom: 10px;
        }
    </style>

    <div class="plan-accion-container">
        <h1>PLAN DE ACCIÓN, CORRECCIONES Y ACCIONES CORRECTIVAS</h1>
        <img src="/uploads/<?php echo htmlspecialchars($rutaImagen); ?>" alt="Plan de Acción" class="plan-accion-imagen">
        <!-- Contenedor para los botones -->
        <div class="botones-container">
            <a href="/admin/resultados" class="boton-volver">Volver</a>
            <a href="/uploads/<?php echo htmlspecialchars($rutaImagen); ?>" download="Plan_de_Accion.jpg" class="btn"> 
                <i class="fa fa-download"></i> Descargar
            </a>
        </div>
    </div>
</body>
            <?php
        } else {
            header('Location: /noplan');
        }
    } 
}

public static function verificarPlanAC() {
    if (isset($_POST['periodo']) && isset($_POST['departamento'])) {
        $periodo_id = $_POST['periodo'];
        $departamento_id = $_POST['departamento'];

        // Realiza la consulta para obtener el PlanAC
        $query = "SELECT PlanAC FROM resultadosdeptos WHERE periodos_id = ? AND departamentos_id = ?";
        $stmt = self::$db->prepare($query);

        if ($stmt) {
            $stmt->bind_param('ii', $periodo_id, $departamento_id);
            $stmt->execute();
            $stmt->bind_result($planAC);
            $stmt->fetch();
            $stmt->close();

            echo json_encode(['planAC' => $planAC]);
        } else {
            echo json_encode(['error' => 'Error en la preparación de la consulta']);
        }
    } else {
        echo json_encode(['error' => 'Faltan parámetros']);
    }
}

public static function obtener_departamento() {
// Verifica que tienes el periodo_id
if (isset($_GET['periodos_id'])) {

    $periodo_id = $_GET['periodos_id'];

    $registro = new Departamentos();

    // Aquí deberías realizar la consulta para obtener los departamentos vinculados al periodo
    $departamentos = $registro->getDepartamentosByPeriodoId($periodo_id);

    // Devuelve los departamentos en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['departamentos' => $departamentos]);
} else {
    // Si no se proporciona periodo_id, devuelve un error
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No se proporcionó periodo_id']);
}

}

}