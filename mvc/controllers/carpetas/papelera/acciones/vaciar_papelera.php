<?php 
// Incluir los archivos de modelo necesarios para el controlador
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la variable "vaciar_papelera" ha sido enviada
if(isset($_POST["vaciar_papelera"])){

    // Crear instancias de los modelos necesarios
    $usuario = new Usuario();
    $carpeta = new Carpeta();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener el ID del usuario
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtener las rutas de las carpetas y archivos a eliminar asociados al usuario
    $rutasCarpetasAEliminar = $carpeta->obtenerRutasCarpetasAEliminar($id_usuario);
    $rutasArchivosAEliminar = $carpeta->obtenerRutasArchivosAEliminar($id_usuario);

    // Extraer las rutas de las carpetas y archivos obtenidos
    $rutasCarpetas = array_column($rutasCarpetasAEliminar, "ruta_carpeta_papelera");
    $rutasArchivos = array_column($rutasArchivosAEliminar, "ruta_archivo_papelera");

    // Convertir las rutas de las carpetas y archivos en strings separados por comas
    $rutasCarpetasCadena = implode(",", $rutasCarpetas);
    $rutasArchivosCadena = implode(",", $rutasArchivos);

    // Verificar si las rutas de carpetas están vacías antes de ejecutar el script
    if (!empty($rutasCarpetasCadena)) {
        // Ejecutar el script para vaciar la papelera de carpetas
        $vaciarPapeleraCarpetasSistema = shell_exec("./../../../../../scripts/eliminar/vaciarPapelera.sh '$rutasCarpetasCadena'");
        
        // Verificar si ocurrió algún error al ejecutar el script
        if ($vaciarPapeleraCarpetasSistema != "0") {
            // Notificar error si ocurrió algún problema al vaciar la papelera en el servidor
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorVaciarPapeleraServidor($id_usuario);

            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    // Verificar si las rutas de archivos están vacías antes de ejecutar el script
    if (!empty($rutasArchivosCadena)) {
        // Ejecutar el script para vaciar la papelera de archivos
        $vaciarPapeleraArchivosSistema = shell_exec("./../../../../../scripts/eliminar/vaciarPapelera.sh '$rutasArchivosCadena'");
        
        // Verificar si ocurrió algún error al ejecutar el script
        if ($vaciarPapeleraArchivosSistema != "0") {
            // Notificar error si ocurrió algún problema al vaciar la papelera en el servidor
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorVaciarPapeleraServidor($id_usuario);

            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    // Vaciar la papelera en la base de datos solo si existen rutas de carpetas
    if (!empty($rutasCarpetasCadena)) {
        $vaciarPapeleraCarpetasBD = $carpeta->vaciarPapeleraCarpetas($id_usuario);
        
        // Verificar si ocurrió algún error al vaciar la papelera en la base de datos
        if (!$vaciarPapeleraCarpetasBD) {
            // Notificar error si ocurrió algún problema al vaciar la papelera en la base de datos
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorVaciarPapeleraBD($id_usuario);

            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    // Vaciar la papelera en la base de datos solo si existen rutas de archivos
    if (!empty($rutasArchivosCadena)) {
        $vaciarPapeleraArchivosBD = $carpeta->vaciarPapeleraArchivos($id_usuario);
        
        // Verificar si ocurrió algún error al vaciar la papelera en la base de datos
        if (!$vaciarPapeleraArchivosBD) {
            // Notificar error si ocurrió algún problema al vaciar la papelera en la base de datos
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorVaciarPapeleraBD($id_usuario);

            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    // Notificar éxito al vaciar la papelera de reciclaje
    $estructuraNotificacion = $notificacionCarpeta->notificarVaciarPapelera($id_usuario);

    echo json_encode(['respuesta' => 'exito', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
    exit();
}
?>
