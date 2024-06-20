<?php 
// Incluir archivos de las clases necesarias
require_once "../../../../models/carpeta.php";
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar sesión si aún no se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se ha enviado el ID de la ruta
if(isset($_POST["idRuta"])){
    // Obtener el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crear instancias de las clases Carpeta, Usuario y NotificacionCarpeta
    $carpeta = new Carpeta();
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Obtener la ruta de la carpeta a partir de su ID
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Calcular el número total de partes de la ruta de la carpeta
    $totalPartesCarpeta = count(explode("/", $rutaCarpeta));
    
    // Obtener el nombre de la carpeta
    $nombreCarpeta = basename($rutaCarpeta);

    // Obtener el ID de la carpeta a partir de su ruta
    $idCarpeta = $carpeta->obtenerIdDesdeMap($idRuta)[0]["id_carpeta"];

    // Obtener el ID del usuario a partir de su nombre
    $id_usuario = $usuario->obtenerIdUsuario($nombre_usuario);

    // Verificar si se ha obtenido el ID del usuario
    if($id_usuario){
        // Obtener las rutas de todas las subcarpetas y archivos dentro de la carpeta
        $obtenerRutas = $carpeta->obtenerRutas($rutaCarpeta);
        
        // Definir la ruta de la papelera del usuario
        $rutaPapelera = "/gestor/usuarios/$nombre_usuario/papelera";
        $idCarpetaPapelera="";

        // Iterar sobre las rutas obtenidas
        for ($i = 0; $i < count($obtenerRutas) ; $i++) {
            // Generar un ID único para la nueva ubicación de la carpeta en la papelera
            $idUnico = md5(uniqid());

            $idCarpetaAMover = $carpeta->obtenerIdCarpeta($obtenerRutas[$i]["ruta_carpeta"]);

            // Asignar el ID único a la carpeta principal de la papelera
            if($i === 0){
                $idCarpetaPapelera = $idUnico;
            }
            
            // Dividir la ruta en partes
            $rutas = explode("/", $obtenerRutas[$i]["ruta_carpeta"]);

            // Definir la nueva ruta de la carpeta en la papelera
            $rutaCarpetaPapelera = $rutaPapelera . "/" . $idCarpetaPapelera . "_" . $nombreCarpeta;

            // Agregar las subcarpetas a la nueva ruta
            for ($j = $totalPartesCarpeta; $j < count($rutas); $j++) {
                // Agregar el nombre de la subcarpeta actual a la nueva ruta
                $rutaCarpetaPapelera .= "/" . $rutas[$j];
            }

            // Eliminar de compartido si lo esta
            $carpeta->eliminarCompartirCarpeta($idCarpetaAMover);

            // Mover la carpeta a la papelera
            $actualizacionExitosa = $carpeta->moverCarpetaPapelera($obtenerRutas[$i]["ruta_carpeta"],$rutaCarpetaPapelera,$id_usuario,$idUnico);
        }

        // Obtener las rutas de todos los archivos dentro de la carpeta
        $obtenerRutasArchivos = $carpeta->obtenerRutasArchivos($rutaCarpeta,$id_usuario);

        // Obtener el ID de la carpeta principal de la papelera
        $idCarpetaPapelera = $carpeta->obtenerIdCarpetaPapelera2($idCarpeta);

        // Iterar sobre las rutas de los archivos obtenidas
        for ($i = 0; $i < count($obtenerRutasArchivos) ; $i++) {
            // Generar un ID único para la nueva ubicación del archivo en la papelera
            $idUnico = md5(uniqid());

            $idArchivoAMover = $archivo->obtenerIdArchivo($obtenerRutasArchivos[$i]["ruta_archivo"]);

            // Dividir la ruta en partes
            $rutas = explode("/", $obtenerRutasArchivos[$i]["ruta_archivo"]);

            // Definir la nueva ruta del archivo en la papelera
            $rutaArchivoPapelera = $rutaPapelera . "/" . $idCarpetaPapelera . "_" . $nombreCarpeta;

            // Agregar las subcarpetas a la nueva ruta
            for ($j = $totalPartesCarpeta; $j < count($rutas); $j++) {
                // Agregar el nombre de la subcarpeta actual a la nueva ruta
                $rutaArchivoPapelera .= "/" . $rutas[$j];
            }

            $archivo->eliminarCompartirArchivo($idArchivoAMover);

            // Mover el archivo a la papelera
            $actualizacionExitosa = $carpeta->moverArchivoPapelera($obtenerRutasArchivos[$i]["ruta_archivo"],$rutaArchivoPapelera,$id_usuario,$idUnico);
        }

        // Ejecutar un script para mover la carpeta a la papelera del servidor
        $moverCarpetaPapelera = shell_exec('./../../../../../scripts/moverPapelera/moverPapeleraCarpeta.sh '."'$rutaCarpeta' '$nombre_usuario' '$idCarpetaPapelera' '$nombreCarpeta'");

        // Manejar posibles errores durante el proceso de mover la carpeta a la papelera
        if($moverCarpetaPapelera  != "0"){
            switch ($moverCarpetaPapelera) {
                case "No se proporcionaron los parametros necesarios":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaPapeleraServidor($nombreCarpeta,$id_usuario);
                    break;
                case "Carpeta no existe":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaPapeleraServidor2($nombreCarpeta,$id_usuario);
                    break;
                case "Papelera no existe":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaPapeleraServidor3($nombreCarpeta,$id_usuario);
                    break;
                case "Error al mover carpeta a la papelera":
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaPapeleraServidor4($nombreCarpeta,$id_usuario);
                    break;
                default:
                    $estructuraNotificacion = $notificacionCarpeta->notificarErrorMoverCarpetaPapeleraServidor4($nombreCarpeta,$id_usuario);
                    break;
            }
            // Devolver una respuesta de error si ocurrió un problema al mover la carpeta a la papelera
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
    // Notificar el éxito de mover la carpeta a la papelera
    $estructuraNotificacion = $notificacionCarpeta->notificarMoverCarpetaPapelera($nombreCarpeta,$id_usuario);

    // Devolver una respuesta de éxito con la notificación correspondiente
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta'], JSON_UNESCAPED_UNICODE);
    exit();
}

?>