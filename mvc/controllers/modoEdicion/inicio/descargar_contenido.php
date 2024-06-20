<?php 
// Incluir los archivos de modelo necesarios para el controlador
require_once "../../../models/carpeta.php";
require_once "../../../models/archivo.php";
require_once "../../../models/usuario.php";
require_once "../../../models/notificacionCarpeta.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables "idRutasCarpetas" e "idRutasArchivos" han sido enviadas
if(isset($_POST["idRutasCarpetas"]) && isset($_POST["idRutasArchivos"])){

    // Obtener las de IDs de carpetas y archivos
    $strRutasCarpetas = $_POST["idRutasCarpetas"];
    $strRutasArchivos = $_POST["idRutasArchivos"];

    // Convertir las cadenas de IDs en arrays, separados por coma
    if(!empty($strRutasCarpetas)){
        $idRutasCarpetas = explode(",",$strRutasCarpetas);
    } else {
        $idRutasCarpetas = [];
    }

    if(!empty($strRutasArchivos)){
        $idRutasArchivos = explode(",",$strRutasArchivos);
    } else {
        $idRutasArchivos = [];
    }

    // Crear instancias de los modelos necesarios
    $usuario = new Usuario();
    $carpeta = new Carpeta();
    $archivo = new Archivo();
    $notificacionCarpeta = new NotificacionCarpeta();
    
    // Obtener el nombre de usuario de la sesión
    $nombreUsuario = $_SESSION["usuario"];

    // Obtener el ID del usuario
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Crear un array para almacenar las rutas de carpetas y archivos seleccionados
    $rutas = [];

    // Obtener las rutas de las carpetas seleccionadas
    if (!empty($idRutasCarpetas)) {
        foreach ($idRutasCarpetas as $idRutaCarpeta) {
            $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRutaCarpeta);
            if (!empty($rutaCarpeta)) {
                $rutas[] = $rutaCarpeta[0]["ruta_carpeta"];
            }
        }
    }
    
    // Obtener las rutas de los archivos seleccionados
    if (!empty($idRutasArchivos)) {
        foreach ($idRutasArchivos as $idRutaArchivo) {
            $rutaArchivo = $archivo->obtenerRutaArchivo($idRutaArchivo);
            if (!empty($rutaArchivo)) {
                $rutas[] = $rutaArchivo[0]["ruta_archivo"];
            }
        }
    }

    // Crear un nombre para el archivo ZIP
    $nombreZip = "descarga_$nombreUsuario";
    // Crear el archivo ZIP con los contenidos de las rutas seleccionadas
    $rutaZip = $carpeta->crearZipContenidos($rutas, $nombreZip);

    // Verificar si se pudo crear el archivo ZIP
    if(!$rutaZip){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorComprimirCarpetaContenido($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);        
        exit();
    }

    // Crear una notificación sobre la compresión de los contenidos de la carpeta
    $estructuraNotificacion = $notificacionCarpeta->notificarComprimirCarpetaContenido($id_usuario);

    // Enviar una respuesta JSON con la información sobre el éxito de la operación y la URL del archivo ZIP
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'url' => $rutaZip], JSON_UNESCAPED_UNICODE);        
    exit();
}

?>
