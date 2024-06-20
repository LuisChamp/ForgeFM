<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../../models/archivo.php";
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionArchivo.php";

// Iniciar sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se han enviado las rutas de origen y destino
if(isset($_POST["idRutaOrigen"]) && isset($_POST["idRutaDestino"])){
    // Obtener los ID de rutas del archivo de origen y de la carpeta de destino
    $idRutaOrigen = $_POST["idRutaOrigen"];
    $idRutaDestino = $_POST["idRutaDestino"];

    // Crear instancias de las clases necesarias
    $archivo = new Archivo();
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtener el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtener las rutas de archivo de origen y carpeta de destino
    $rutaArchivoOrigen = $archivo->obtenerRutaArchivo($idRutaOrigen)[0]["ruta_archivo"];
    $rutaCarpetaDestino = $carpeta->obtenerRutaCarpeta($idRutaDestino)[0]["ruta_carpeta"];

    // Obtener los IDs de archivo y carpeta correspondientes a las rutas
    $idArchivoOrigen = $archivo->obtenerIdDesdeMap($idRutaOrigen)[0]["id_archivo"];
    $idCarpetaDestino = $carpeta->obtenerIdDesdeMap($idRutaDestino)[0]["id_carpeta"];

    // Verificar si la carpeta destino es la misma que la carpeta raiz del usuario
    if($idRutaDestino === $_SESSION["ruta_carpeta"]){
        $idCarpetaDestino = "null";
    }
    
    // Obtener el nombre de archivo y carpeta
    $nombreArchivo = basename($rutaArchivoOrigen);
    $nombreCarpeta = basename($rutaCarpetaDestino);

    // Obtener el ID de usuario asociado al nombre de usuario
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Verificar si se obtuvo un ID de usuario válido
    if($id_usuario){

        // Definir la ruta actualizada del archivo
        $rutaArchivoActualizada = $rutaCarpetaDestino."/".$nombreArchivo;

        // Verificar si se está intentando mover el archivo dentro de la misma carpeta
        if($rutaArchivoOrigen === $rutaArchivoActualizada){
            $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoMismaCarpeta($nombreArchivo,$nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Verificar si ya existe un archivo con el mismo nombre en la carpeta destino
        $verificarNombreArchivoAMover = $archivo->verificarNombreArchivoAMover($rutaCarpetaDestino,$nombreArchivo);

        if($verificarNombreArchivoAMover){
            $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoExistente($nombreArchivo,$nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Mover el archivo a la carpeta destino en la base de datos
        $moverArchivo = $archivo->moverArchivo($idArchivoOrigen,$rutaArchivoActualizada ,$idCarpetaDestino);

        // Verificar si se pudo mover el archivo en la base de datos
        if(!$moverArchivo){
            $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoBD($nombreArchivo,$nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Verificar si la carpeta destino ha sido compartida y compartir el archivo si es necesario
        if($idCarpetaDestino !== "null"){
            $idLectores = $carpeta->obtenerIdLectores($idCarpetaDestino);            
            if(count($idLectores) !== 0){
                foreach ($idLectores as $idLector) {
                    $fecha=date("Y-m-d H:i:s");
                    if($archivo->esArchivoCompartido($idArchivoOrigen,$idLector["id_receptor"])){
                        continue;
                    }
                    $archivo->compartirArchivo($idArchivoOrigen,$id_usuario,$idLector["id_receptor"],$fecha);
                }
            }
        }

        // Mover el archivo y su contenido en el sistema de archivos del servidor
        $moverArchivoSistema = shell_exec('./../../../../../scripts/mover/moverArchivo.sh '."'$rutaArchivoOrigen' '$rutaArchivoActualizada'");

        // Verificar si hubo algún error al mover el archivo en el sistema de archivos
        if($moverArchivoSistema != "0"){
            switch ($moverArchivoSistema) {
                case "No se han pasado los parametros necesarios":
                    $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoServidor($nombreArchivo,$nombreCarpeta,$id_usuario);
                    break;
                case "Archivo no existe":
                    $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoServidor2($nombreArchivo,$nombreCarpeta,$id_usuario);
                    break;
                case "Directorio no existe":
                    $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoServidor3($nombreArchivo,$nombreCarpeta,$id_usuario);
                    break;
                case "Error al mover archivo en la carpeta":
                    $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoServidor4($nombreArchivo,$nombreCarpeta,$id_usuario);
                    break;
                default:
                    $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivoServidor4($nombreArchivo,$nombreCarpeta,$id_usuario);
                    break;
            }
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    
        // Notificar éxito en el movimiento del archivo
        $estructuraNotificacion = $notificacionArchivo->notificarMoverArchivo($nombreArchivo,$nombreCarpeta,$id_usuario);

        // Enviar respuesta JSON con detalles del movimiento del archivo
        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'archivo'], JSON_UNESCAPED_UNICODE);
        exit();
    }
}