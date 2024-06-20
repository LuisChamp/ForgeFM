<?php 
// Incluir archivos de modelo necesarios para la funcionalidad
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar una sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables necesarias han sido enviadas
if(isset($_POST["idRuta"]) && isset($_POST["idRutaPapelera"])){
    // Asignar variables
    $idRuta = $_POST["idRuta"];
    $idRutaPapelera = $_POST["idRutaPapelera"];

    // Crear instancias de los modelos necesarios
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener el nombre del usuario desde la sesión
    $nombre_usuario = $_SESSION["usuario"];

    // Obtener el ID del usuario a partir del nombre del usuario
    $id_usuario = $usuario->obtenerIdUsuario($nombre_usuario);

    // Si se obtiene un ID de usuario válido
    if($id_usuario){

        // Obtener el ID de la carpeta a partir del ID de la ruta
        $idCarpeta = $carpeta->obtenerIdDesdeMap($idRuta)[0]["id_carpeta"];

        // Obtener la ruta de la carpeta y la ruta de la carpeta en la papelera a partir del ID de la carpeta
        $rutaCarpeta = $carpeta->obtenerRutaCarpetaId($idCarpeta)[0]["ruta_carpeta"];
        $rutaCarpetaPapelera = $carpeta->obtenerRutaCarpetaId($idCarpeta)[0]["ruta_carpeta_papelera"];

        // Obtener la ruta raíz de la carpeta desde la sesión
        $idRutaRaiz = $_SESSION["ruta_carpeta"];
        $rutaRaiz = $carpeta->obtenerRutaCarpeta($idRutaRaiz)[0]["ruta_carpeta"];

        // Obtener el nombre de la carpeta desde la ruta
        $nombreCarpeta = basename($rutaCarpeta);

        // Obtener IDs de las subcarpetas en la papelera
        $arrayCarpetasPapelera = $carpeta->obtenerIdCarpetasPapelera($rutaCarpetaPapelera);
        $idCarpetasPapelera = array_column($arrayCarpetasPapelera, 'id_carpeta');

        // Iterar sobre cada ID de las subcarpetas en la papelera
        foreach ($idCarpetasPapelera as $ids => $id) {
            // Obtener la ruta de la subcarpeta en la papelera
            $rutaCarpetaIdPapelera = $carpeta->obtenerRutaCarpetaIdPapelera($id)[0]["ruta_carpeta"];
            // Dividir la ruta en partes
            $partes = explode('/', $rutaCarpetaIdPapelera);
            // Eliminar el último segmento de la ruta
            array_pop($partes);
            // Unir las partes nuevamente para formar la ruta modificada
            $rutaNueva = implode('/', $partes);

            // Actualizar las rutas de las carpetas y archivos en la BD
            $actualizarRutasCarpetas = $carpeta->ex_actualizarRutasCarpetas($id, $rutaCarpetaIdPapelera, $rutaNueva, $rutaRaiz);
            $actualizarRutasArchivos = $carpeta->ex_actualizarRutasArchivos($id, $rutaCarpetaIdPapelera, $rutaNueva, $rutaRaiz);

            // Verificar si hubo errores al actualizar las rutas de las carpetas
            if(!$actualizarRutasCarpetas){
                // Notificar error si no se pudieron actualizar las rutas de las carpetas en la BD
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaBD($nombreCarpeta, $id_usuario);
                echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
                exit();
            }

            // Verificar si hubo errores al actualizar las rutas de los archivos
            if(!$actualizarRutasArchivos){
                // Notificar error si no se pudieron actualizar las rutas de los archivos en la BD
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaBD($nombreCarpeta, $id_usuario);
                echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
                exit();
            }
        }

        // Eliminar carpetas y archivos contenidos en la ruta de la papelera
        $validarEliminacionCarpeta = $carpeta->eliminarCarpetaContenido($rutaCarpetaPapelera);
        $validarEliminacionArchivos = $carpeta->eliminarCarpetaContenido2($rutaCarpetaPapelera);

        // Verificar si hubo errores al eliminar las carpetas
        if(!$validarEliminacionCarpeta){
            // Notificar error si no se pudieron eliminar las carpetas en la BD
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaBD($nombreCarpeta, $id_usuario);
            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Verificar si hubo errores al eliminar los archivos
        if(!$validarEliminacionArchivos){
            // Notificar error si no se pudieron eliminar los archivos en la BD
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaBD($nombreCarpeta, $id_usuario);
            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
        
        // Ejecutar un script del sistema para eliminar la carpeta en la papelera
        $eliminarCarpeta = shell_exec('./../../../../../scripts/eliminar/eliminarCarpeta.sh '."'$rutaCarpetaPapelera'");

        // Verificar el resultado de la ejecución del script
        if($eliminarCarpeta  != "0"){
            // Si el script no retorna "0", significa que ocurrió un error
            switch ($eliminarCarpeta) {
                case "No se ha proporcionado el parametro necesario":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaServidor($nombreCarpeta, $id_usuario);
                    break;
                case "Carpeta no existe":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaServidor2($nombreCarpeta, $id_usuario);
                    break;
                case "Error al eliminar carpeta":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaServidor3($nombreCarpeta, $id_usuario);
                    break;
                default:
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorEliminarCarpetaServidor3($nombreCarpeta, $id_usuario);
                    break;
            }
            // Enviar la respuesta con el error y la notificación correspondiente
            echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Notificar la eliminación exitosa de la carpeta
        $estructuraNotificacion = $notificacionCarpeta->notificarEliminarCarpeta($nombreCarpeta, $id_usuario);

        // Enviar la respuesta de éxito junto con la notificación
        echo json_encode(['respuesta' => 'exito', 'notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta'], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
?>

