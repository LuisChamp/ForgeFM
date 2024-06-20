<?php 
// Se requieren los archivos de las clases necesarias
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionArchivo.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se han enviado los ID de la ruta y la papelera
if(isset($_POST["idRuta"]) && isset($_POST["idRutaPapelera"])){
    // Obtiene los ID de la ruta y la papelera enviados por el formulario
    $idRuta = $_POST["idRuta"];
    $idRutaPapelera = $_POST["idRutaPapelera"];

    // Crea instancias de las clases Archivo, Usuario y NotificacionArchivo
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtiene la ruta del archivo y su nombre
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];
    $nombreArchivo = basename($rutaArchivo);

    // Obtiene la ruta del archivo en la papelera
    $rutaArchivoPapelera = $archivo->obtenerRutaArchivoPapelera($idRutaPapelera)[0]["ruta_archivo_papelera"];

    // Obtiene el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Obtiene el ID de usuario correspondiente al nombre de usuario de la sesión
    $id_usuario = $usuario->obtenerIdUsuario($nombre_usuario);

    // Verifica si se ha obtenido el ID de usuario
    if($id_usuario){
        // Valida la ruta del archivo en la papelera
        $validarRutaPapelera = $archivo->validarRutaArchivoPapelera($rutaArchivoPapelera);

        // Verifica si la ruta de la papelera es válida
        if(!$validarRutaPapelera){
            // Notifica un error si la ruta de la papelera no es válida
            $estructuraNotificacion = $notificacionArchivo->notificarErrorEliminarArchivoPapelera($nombreArchivo,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Elimina el archivo de la carpeta y la papelera
        $validarEliminacionArchivo = $archivo->eliminarArchivo($rutaArchivo,$rutaArchivoPapelera);

        // Verifica si se ha eliminado correctamente el archivo
        if(!$validarEliminacionArchivo){
            // Notifica un error si no se ha podido eliminar el archivo en la base de datos
            $estructuraNotificacion = $notificacionArchivo->notificarErrorEliminarArchivoBD($nombreArchivo,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Ejecuta el script de eliminación del archivo en el sistema
        $eliminarArchivoSistema = shell_exec('./../../../../../scripts/eliminar/eliminarArchivo.sh '."'$rutaArchivoPapelera'");

        // Verifica si se ha eliminado correctamente el archivo en el sistema
        if($eliminarArchivoSistema != "0"){
            // Notifica un error si no se ha podido eliminar el archivo en el sistema
            switch ($eliminarArchivoSistema) {
                case "No se ha proporcionado el parametro necesario":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorEliminarArchivoServidor($nombreArchivo,$id_usuario);
                    break;
                case "Archivo no existe":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorEliminarArchivoServidor2($nombreArchivo,$id_usuario);
                    break;
                case "Error al eliminar archivo":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorEliminarArchivoServidor3($nombreArchivo,$id_usuario);
                    break;
                default:
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorEliminarArchivoServidor3($nombreArchivo,$id_usuario);
                    break;
            }
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Notifica la eliminación exitosa del archivo
        $estructuraNotificacion = $notificacionArchivo->notificarEliminarArchivo($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'archivo'], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

?>
