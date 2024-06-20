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

// Verificar si se han enviado los datos necesarios para restaurar un archivo desde la papelera
if(isset($_POST["idRuta"]) && isset($_POST["idRutaPapelera"])){
    // Obtener los datos enviados
    $idRuta = $_POST["idRuta"];
    $idRutaPapelera = $_POST["idRutaPapelera"];

    // Crear instancias de las clases necesarias
    $archivo = new Archivo();
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtener la ruta del archivo original y su nombre
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];
    $nombreArchivo = basename($rutaArchivo);

    // Obtener el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtener la ruta del archivo en la papelera
    $rutaArchivoPapelera = $archivo->obtenerRutaArchivoPapelera($idRutaPapelera)[0]["ruta_archivo_papelera"];

    // Obtener el ID del archivo
    $idArchivo = $archivo->obtenerIdDesdeMap($idRuta)[0]["id_archivo"];

    // Obtener la ruta de la carpeta raíz del usuario
    $idRutaRaiz = $_SESSION["ruta_carpeta"];
    $rutaRaiz = $carpeta->obtenerRutaCarpeta($idRutaRaiz)[0]["ruta_carpeta"];

    // Verificar si el archivo ya existe en la ruta de destino
    $verificarRutaArchivo = $archivo->verificarRutaArchivo($idArchivo,$rutaArchivo);

    // Notificar error si el archivo ya existe en la ruta de destino
    if($verificarRutaArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoExistente($nombreArchivo,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtener la ruta de restauración del archivo
    $rutaRestauracion = $archivo->ex_obtenerRutaRestauracionArchivo($idArchivo,$rutaRaiz);

    // Notificar error si no se pudo obtener la ruta de restauración del archivo
    if($rutaRestauracion === null){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoBD($nombreArchivo,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Formar la nueva ruta del archivo
    $nombreArchivo = basename($rutaArchivo);
    $nuevaRuta = $rutaRestauracion["ruta_restauracion"]."/$nombreArchivo";

    // Verificar si el archivo ya existe en la nueva ruta
    $verificarRutaArchivo2 = $archivo->verificarRutaArchivo($idArchivo,$nuevaRuta);

    // Notificar error si el archivo ya existe en la nueva ruta
    if($verificarRutaArchivo2){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoExistente($nombreArchivo,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 

    // Determinar la carpeta padre del archivo restaurado
    if($rutaRaiz === $rutaRestauracion["ruta_restauracion"]){
        $idCarpetaPadre = "null";
    } else {
        $idCarpetaPadre = $carpeta->obtenerIdCarpeta($rutaRestauracion["ruta_restauracion"]);
    }

    // Actualizar la ruta del archivo en la base de datos
    $actualizarRutaArchivo = $archivo->actualizarRutaArchivo($idArchivo,$nuevaRuta,$idCarpetaPadre);

    // Notificar error si no se pudo actualizar la ruta del archivo en la base de datos
    if(!$actualizarRutaArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoBD2($nombreArchivo,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Restaurar el archivo
    $restaurarArchivo = $archivo->restaurarArchivo($idArchivo);

    // Notificar error si no se pudo restaurar el archivo
    if(!$restaurarArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoBD3($nombreArchivo,$id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar si la carpeta padre ha sido compartida
    if($idCarpetaPadre !== "null"){
        $idLectores = $carpeta->obtenerIdLectores($idCarpetaPadre);
        if(count($idLectores) !== 0){
            foreach ($idLectores as $idLector) {
                $fecha=date("Y-m-d H:i:s");
                $archivo->compartirArchivo($idArchivo,$id_usuario,$idLector["id_receptor"],$fecha);
            }
        }
    }

    // Restaurar el archivo en el sistema
    $restaurarArchivoSistema = shell_exec('./../../../../../scripts/restaurar/restaurarArchivo.sh '."'$rutaArchivoPapelera' '$nuevaRuta'");

    // Notificar error si no se pudo restaurar el archivo en el sistema
    if($restaurarArchivoSistema != "0"){
        switch ($restaurarArchivoSistema) {
            case "No se han proporcionado los parametros necesarios":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoServidor($nombreArchivo,$id_usuario);
                break;
            case "Archivo no existe":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoServidor2($nombreArchivo,$id_usuario);
                break;
            case "Directorio no existe":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoServidor3($nombreArchivo,$id_usuario);
                break;
            case "Error al restaurar archivo":
                $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoServidor4($nombreArchivo,$id_usuario);
                break;
            default:
                $estructuraNotificacion = $notificacionArchivo->notificarErrorRestaurarArchivoServidor4($nombreArchivo,$id_usuario);
                break;
        }
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notificar éxito al restaurar el archivo
    $estructuraNotificacion = $notificacionArchivo->notificarRestaurarArchivo($nombreArchivo,$id_usuario);
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion,'tipo' => 'archivo'], JSON_UNESCAPED_UNICODE);
    exit();    
}

?>

