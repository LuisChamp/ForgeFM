<?php 
// Incluir los archivos de modelo necesarios para el controlador
require_once "../../../../models/carpeta.php";
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables idRuta e idRutaPapelera han sido enviadas
if(isset($_POST["idRuta"]) && isset($_POST["idRutaPapelera"])){
    $idRuta = $_POST["idRuta"];
    $idRutaPapelera = $_POST["idRutaPapelera"];

    // Crear instancias de los modelos necesarios
    $carpeta = new Carpeta();
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener la ruta de la carpeta y la ruta de la papelera usando los IDs proporcionados
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];
    $rutaCarpetaPapelera = $carpeta->obtenerRutaCarpetaPapelera($idRutaPapelera)[0]["ruta_carpeta_papelera"];

    // Obtener el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Obtener la ruta raíz de la sesión
    $idRutaRaiz = $_SESSION["ruta_carpeta"];
    $rutaRaiz = $carpeta->obtenerRutaCarpeta($idRutaRaiz)[0]["ruta_carpeta"];

    // Obtener el ID de la carpeta usando el ID de la ruta
    $idCarpeta = $carpeta->obtenerIdDesdeMap($idRuta)[0]["id_carpeta"];

    // Verificar si la ruta de la carpeta es válida
    $verificarRutaCarpeta = $carpeta->verificarRutaCarpeta($idCarpeta, $rutaCarpeta);
    $nombreCarpeta = basename($rutaCarpeta);

    // Si la carpeta ya existe en la ruta de restauración, notificar error
    if($verificarRutaCarpeta){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaExistente($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 

    // Obtener la ruta de restauración usando la carpeta y la ruta raíz
    $rutaRestauracion = $carpeta->ex_obtenerRutaRestauracion($idCarpeta, $rutaRaiz);

    // Si no se puede obtener la ruta de restauración, notificar error
    if($rutaRestauracion === null){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaBD($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Construir la nueva ruta de restauración
    $nuevaRuta = $rutaRestauracion["ruta_restauracion"] . "/$nombreCarpeta";

    // Verificar nuevamente si la nueva ruta de la carpeta ya existe
    $verificarRutaCarpeta2 = $carpeta->verificarRutaCarpeta($idCarpeta, $nuevaRuta);

    // Si la carpeta ya existe en la nueva ruta, notificar error
    if($verificarRutaCarpeta2){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaExistente($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 

    // Determinar el ID de la carpeta padre en función de la ruta de restauración
    if($rutaRaiz === $rutaRestauracion["ruta_restauracion"]){
        $idCarpetaPadre = "null";
    } else {
        $idCarpetaPadre = $carpeta->obtenerIdCarpeta($rutaRestauracion["ruta_restauracion"]);
    }

    // Actualizar la ruta de la carpeta en la base de datos
    $actualizarRutaCarpeta = $carpeta->actualizarRutaCarpeta($idCarpeta, $nuevaRuta, $idCarpetaPadre);

    // Si no se pudo actualizar la ruta de la carpeta, notificar error
    if(!$actualizarRutaCarpeta){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaBD2($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Actualizar las rutas de las subcarpetas de la carpeta
    $actualizarRutaCarpetaHijas = $carpeta->actualizarRutaCarpetas($rutaCarpeta, $nuevaRuta, $rutaCarpetaPapelera);

    // Si no se pudo actualizar las rutas de las subcarpetas, notificar error
    if(!$actualizarRutaCarpetaHijas){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaBD3($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 

    // Actualizar las rutas de los archivos de la carpeta
    $actualizarRutaArchivosHijos = $carpeta->actualizarRutaArchivos($rutaCarpeta, $nuevaRuta, $rutaCarpetaPapelera);

    // Si no se pudo actualizar las rutas de los archivos, notificar error
    if(!$actualizarRutaArchivosHijos){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaBD4($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    } 

    // Restaurar la carpeta en la base de datos
    $restaurarCarpeta = $carpeta->restaurarCarpeta($idCarpeta);

    // Si no se pudo restaurar la carpeta, notificar error
    if(!$restaurarCarpeta){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaBD($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Restaurar las subcarpetas de la carpeta
    $restaurarCarpetasHijas = $carpeta->restaurarCarpetasHijas($nuevaRuta, $rutaCarpetaPapelera);

    // Si no se pudo restaurar las subcarpetas, notificar advertencia
    if(!$restaurarCarpetasHijas){
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvRestaurarSubcarpetas($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'advertencia', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Restaurar los archivos de la carpeta
    $restaurarArchivosHijos = $carpeta->restaurarArchivosHijos($nuevaRuta, $rutaCarpetaPapelera);

    // Si no se pudo restaurar los archivos, notificar advertencia
    if(!$restaurarArchivosHijos){
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvRestaurarArchivos($nombreCarpeta, $id_usuario);
        echo json_encode(['respuesta' => 'advertencia', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar si la carpeta restaurada estaba compartida y restaurar permisos
    if($idCarpetaPadre !== "null"){
        $idLectores = $carpeta->obtenerIdLectores($idCarpetaPadre);
        $arrayIdCarpetas = $carpeta->obtenerRutasCompartirCarpetas2($nuevaRuta);
        $arrayIdArchivos = $carpeta->obtenerRutasCompartirArchivos2($nuevaRuta);

        $idsCarpetas = array_column($arrayIdCarpetas, 'id_carpeta');
        $idsArchivos = array_column($arrayIdArchivos, 'id_archivo');
        
        if(count($idLectores) !== 0){
            foreach ($idLectores as $idLector) {
                $fecha = date("Y-m-d H:i:s");
                $carpeta->compartirCarpeta($idCarpeta, $id_usuario, $idLector["id_receptor"], $fecha);
            }
            foreach ($idLectores as $idLector) {
                $fecha = date("Y-m-d H:i:s");
                foreach($idsCarpetas as $idCarpetaHija){
                    $carpeta->compartirCarpeta($idCarpetaHija, $id_usuario, $idLector["id_receptor"], $fecha);
                }
                foreach($idsArchivos as $idArchivoHijo){
                    $archivo->compartirArchivo($idArchivoHijo, $id_usuario, $idLector["id_receptor"], $fecha);
                }
            }
        }
    }

    // Ejecutar el script del servidor para restaurar la carpeta
    $restaurarCarpetaSistema = shell_exec('./../../../../../scripts/restaurar/restaurarCarpeta.sh ' . "'$rutaCarpetaPapelera' '$nuevaRuta'");

    // Verificar el resultado de la ejecución del script y notificar errores si ocurren
    if($restaurarCarpetaSistema != "0"){
        switch ($restaurarCarpetaSistema) {
            case "No se han proporcionado los parametros necesarios":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaServidor3($nombreCarpeta, $id_usuario);
                break;
            case "Carpeta origen no existe":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaServidor($nombreCarpeta, $id_usuario);
                break;
            case "Directorio de destino no existe":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaServidor2($nombreCarpeta, $id_usuario);
                break;
            case "Error al restaurar carpeta":
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaServidor3($nombreCarpeta, $id_usuario);
                break;
            default:
                $estructuraNotificacion = $notificacionCarpeta->notificarErrorRestaurarCarpetaServidor3($nombreCarpeta, $id_usuario);
                break;
        }
        echo json_encode(['respuesta' => 'error', 'notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Notificar la restauración exitosa de la carpeta
    $estructuraNotificacion = $notificacionCarpeta->notificarRestaurarCarpeta($nombreCarpeta, $id_usuario);

    // Enviar la respuesta de éxito junto con la notificación
    echo json_encode(['respuesta' => 'exito', 'notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta'], JSON_UNESCAPED_UNICODE);
    exit();    
}
?>
