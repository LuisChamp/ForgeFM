<?php 
// Se incluyen los archivos de las clases necesarias
require_once "../../../../models/carpeta.php";
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Se verifica si la sesión está iniciada, de lo contrario se inicia
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se verifica si se recibieron los IDs de la ruta de origen y destino para mover la carpeta
if(isset($_POST["idRutaOrigen"]) && isset($_POST["idRutaDestino"])){
    $idRutaOrigen = $_POST["idRutaOrigen"];
    $idRutaDestino = $_POST["idRutaDestino"];

    // Se crean instancias de las clases necesarias
    $carpeta = new Carpeta();
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Se obtiene el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Se obtienen las rutas de la carpeta de origen y destino
    $rutaCarpetaOrigen = $carpeta->obtenerRutaCarpeta($idRutaOrigen)[0]["ruta_carpeta"];
    $rutaCarpetaDestino = $carpeta->obtenerRutaCarpeta($idRutaDestino)[0]["ruta_carpeta"];

    // Se obtienen los IDs de la carpeta de origen y destino
    $idCarpetaOrigen = $carpeta->obtenerIdDesdeMap($idRutaOrigen)[0]["id_carpeta"];
    $idCarpetaDestino = $carpeta->obtenerIdDesdeMap($idRutaDestino)[0]["id_carpeta"];

    // Se verifica si la carpeta destino es la misma que la carpeta actual del usuario
    if($idRutaDestino === $_SESSION["ruta_carpeta"]){
        $idCarpetaDestino = "null";
    }
    
    // Se obtienen los nombres de la carpeta de origen y el padre de la carpeta de destino
    $nombreCarpeta = basename($rutaCarpetaOrigen);
    $nombreCarpetaPadre = basename($rutaCarpetaDestino);

    // Si la carpeta destino es la raíz, se establece su nombre como "Unidad"
    if($idCarpetaDestino === "null"){
        $nombreCarpetaPadre = "Unidad";
    }

    // Se obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Si se obtuvo el ID del usuario, se procede a mover la carpeta
    if($id_usuario){

        // Se genera la ruta actualizada de la carpeta
        $rutaCarpetaActualizada = $rutaCarpetaDestino."/".$nombreCarpeta;

        // Se verifica si la carpeta de origen es la misma que la carpeta de destino actualizada
        if($rutaCarpetaOrigen === $rutaCarpetaActualizada){
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaExistente($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se verifica si se está intentando mover la misma carpeta
        $verificarCarpetaAMover = $carpeta->verificarCarpetaAMover($rutaCarpetaDestino,$idCarpetaOrigen);

        if($verificarCarpetaAMover){
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaSiMisma($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se verifica si la carpeta de destino está dentro de la carpeta de origen (para evitar bucles)
        if (strpos($rutaCarpetaDestino, $rutaCarpetaOrigen) === 0) {
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaEnSubcarpeta($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se verifica si ya existe una carpeta con el mismo nombre en la carpeta de destino
        $verificarNombreCarpetaAMover = $carpeta->verificarNombreCarpetaAMover($rutaCarpetaDestino,$nombreCarpeta);

        if($verificarNombreCarpetaAMover){
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaMismoNombre($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se actualiza la ruta de la carpeta a la carpeta de destino en la base de datos
        $actualizarRutaCarpetaMovida = $carpeta->actualizarRutaCarpetaMovida($idCarpetaOrigen,$rutaCarpetaActualizada ,$idCarpetaDestino);

        // Si no se puede actualizar la ruta de la carpeta movida en la base de datos, se muestra un error
        if(!$actualizarRutaCarpetaMovida){
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaBD($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se actualiza la ruta de las carpetas hijas de la carpeta de origen
        $actualizarRutaCarpetaHijas = $carpeta->actualizarRutaCarpetaHijas($rutaCarpetaOrigen,$rutaCarpetaActualizada);

        // Si no se pueden actualizar las rutas de las carpetas hijas, se muestra una advertencia
        if(!$actualizarRutaCarpetaHijas){
            $estructuraNotificacion = $notificacionCarpeta->notificarAdvMoverCarpetaSubcarpetas($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se actualiza la ruta de los archivos hijos de la carpeta de origen
        $actualizarRutaArchivosHijos = $carpeta->actualizarRutaArchivosHijos($rutaCarpetaOrigen,$rutaCarpetaActualizada);

        // Si no se pueden actualizar las rutas de los archivos hijos, se muestra una advertencia
        if(!$actualizarRutaArchivosHijos){
            $estructuraNotificacion = $notificacionCarpeta->notificarAdvMoverCarpetaArchivos($nombreCarpeta,$id_usuario);

            echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Si la carpeta de destino no es la raíz, se verifica si la carpeta de destino ha sido compartida
        if($idCarpetaDestino !== "null"){
            $idLectores = $carpeta->obtenerIdLectores($idCarpetaDestino);
            $arrayIdCarpetas = $carpeta->obtenerRutasCompartirCarpetas($rutaCarpetaActualizada);
            $arrayIdArchivos = $carpeta->obtenerRutasCompartirArchivos($rutaCarpetaActualizada);

            $idsCarpetas = array_column($arrayIdCarpetas, 'id_carpeta');
            $idsArchivos = array_column($arrayIdArchivos, 'id_archivo');
            
            // Si hay lectores, se comparten las carpetas y archivos con ellos
            if(count($idLectores) !== 0){
                foreach ($idLectores as $idLector) {
                    $fecha=date("Y-m-d H:i:s");
                    if($carpeta->esCarpetaCompartida($idCarpetaOrigen,$idLector["id_receptor"])){
                        continue;
                    }
                    $carpeta->compartirCarpeta($idCarpetaOrigen,$id_usuario,$idLector["id_receptor"],$fecha);
                }
                foreach ($idLectores as $idLector) {
                    $fecha=date("Y-m-d H:i:s");
                    foreach($idsCarpetas as $idCarpetaHija){
                        if($carpeta->esCarpetaCompartida($idCarpetaHija,$idLector["id_receptor"])){
                            continue;
                        }
                        $carpeta->compartirCarpeta($idCarpetaHija,$id_usuario,$idLector["id_receptor"],$fecha);
                    }
                    foreach($idsArchivos as $idArchivoHijo){
                        if($archivo->esArchivoCompartido($idArchivoHijo,$idLector["id_receptor"])){
                            continue;
                        }
                        $archivo->compartirArchivo($idArchivoHijo,$id_usuario,$idLector["id_receptor"],$fecha);
                    }
                    
                }
            }
        }

        // Se mueve la carpeta y su contenido en el servidor
        $moverCarpetaSistema = shell_exec('./../../../../../scripts/mover/moverCarpeta.sh '."'$rutaCarpetaOrigen' '$rutaCarpetaActualizada'");

        // Si no se puede mover la carpeta en el servidor, se muestra un error
        if($moverCarpetaSistema  != "0"){
            switch ($moverCarpetaSistema) {
                case "Carpeta origen no existe":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaServidor($nombreCarpeta,$id_usuario);
                    break;
                case "Carpeta destino no existe":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaServidor($nombreCarpeta,$id_usuario);
                    break;
                case "Mover carpeta fallida":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaServidor($nombreCarpeta,$id_usuario);
                    break;
                default:
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaServidor($nombreCarpeta,$id_usuario);
                    break;
            }
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Se notifica que la carpeta se ha movido exitosamente
        $estructuraNotificacion = $notificacionCarpeta->notificarMoverCarpeta($nombreCarpeta,$nombreCarpetaPadre,$id_usuario);

        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta'], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

?>

