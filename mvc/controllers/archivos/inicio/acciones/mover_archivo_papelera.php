<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionArchivo.php";

// Comprobar si la sesión está activa; si no lo está, iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprobar si se ha enviado el parámetro "idRuta"
if(isset($_POST["idRuta"])){
    // Obtener el valor del parámetro "idRuta"
    $idRuta = $_POST["idRuta"];

    // Crear instancias de las clases Archivo, Usuario y NotificacionArchivo
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtener la ruta del archivo correspondiente al idRuta proporcionado
    $ruta = $archivo->obtenerRutaArchivo($idRuta);
    $rutaArchivo = $ruta[0]["ruta_archivo"];

    // Obtener el nombre base del archivo a partir de su ruta
    $nombreArchivo = basename($rutaArchivo);

    // Obtener el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtener el ID de usuario asociado al nombre de usuario
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Si se obtiene un ID de usuario válido
    if($id_usuario){        
        // Definir la ruta de la papelera del usuario
        $rutaPapelera = "/gestor/usuarios/$nombreUsuario/papelera";
        
        // Generar un ID único para el archivo en la papelera
        $idUnico = md5(uniqid());

        $idArchivo = $archivo->obtenerIdArchivo($rutaArchivo);

        // Construir la ruta del archivo en la papelera
        $rutaArchivoPapelera = $rutaPapelera."/".$idUnico."_".$nombreArchivo;
        
        // Mover el archivo a la papelera en la base de datos
        $actualizacionExitosa = $archivo->moverArchivoPapelera($rutaArchivo,$rutaArchivoPapelera,$id_usuario,$idUnico);

        // Si no se puede mover el archivo a la papelera en la base de datos, enviar una notificación de error
        if(!$actualizacionExitosa){
            $estructuraNotificacion = $notificacionArchivo->notificarErrorMoverArchivoBD($nombreArchivo,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $archivo->eliminarCompartirArchivo($idArchivo);

        // Ejecutar un script para mover el archivo a la papelera en el servidor
        $moverArchivoPapelera = shell_exec('./../../../../../scripts/moverPapelera/moverPapeleraArchivo.sh '."'$rutaArchivo' '$nombreUsuario' '$idUnico' '$nombreArchivo'");
        
        // Si ocurrió algún error al mover el archivo a la papelera en el servidor, enviar una notificación de error
        if($moverArchivoPapelera != "0"){
            switch ($moverArchivoPapelera) {
                case "No se han pasado los parametros necesarios":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorMoverArchivoServidor($nombreArchivo,$id_usuario);
                    break;
                case "Archivo no existe":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorMoverArchivoServidor2($nombreArchivo,$id_usuario);
                    break;
                case "Papelera no existe":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorMoverArchivoServidor3($nombreArchivo,$id_usuario);
                    break;
                case "Error al mover archivo a papelera":
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorMoverArchivoServidor4($nombreArchivo,$id_usuario);
                    break;
                default:
                    $estructuraNotificacion = $notificacionArchivo->notificarErrorMoverArchivoServidor4($nombreArchivo,$id_usuario);
                    break;
            }
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Si se movió el archivo correctamente a la papelera, enviar una notificación de éxito
        $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoPapelera($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion,'tipo' => 'archivo'], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

?>
