<?php 
// Se requieren los archivos de los modelos Carpeta, Archivo, Usuario y NotificacionArchivo
require_once "../../../../models/carpeta.php";
require_once "../../../../models/archivo.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionArchivo.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprueba si se han enviado el nombre del propietario, el correo del receptor, el ID de la ruta y la acción
if(isset($_POST["nombrePropietario"]) && isset($_POST["emailReceptor"]) && isset($_POST["idRuta"]) && isset($_POST["accion"])){

    // Obtiene los datos del formulario
    $nombrePropietario = $_POST["nombrePropietario"];
    $emailReceptor = $_POST["emailReceptor"];
    $idRuta = $_POST["idRuta"];
    $accion = $_POST["accion"];

    // Instancia objetos de las clases Archivo, Usuario y NotificacionArchivo
    $archivo = new Archivo();
    $usuario = new Usuario();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtiene la ruta del archivo utilizando su ID
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];

    // Obtiene el nombre del archivo
    $nombreArchivo = basename($rutaArchivo);

    // Obtiene el ID del archivo
    $idArchivo = $archivo->obtenerIdDesdeMap($idRuta)[0]["id_archivo"];

    // Obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($_SESSION["usuario"]);

    // Valida el nombre del propietario
    $validarNombrePropietario = $usuario->validarNombrePropietario($nombrePropietario);

    if(!$validarNombrePropietario){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCompartirArchivoUsuarioInexistente($nombrePropietario,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtiene el ID del propietario del archivo
    $idPropietario = $usuario->obtenerIdUsuario($nombrePropietario);

    // Valida si el propietario actualmente es el propietario del archivo
    $validarPropietarioArchivo = $archivo->validarPropietarioArchivo($idPropietario,$idArchivo);

    if(!$validarPropietarioArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCompartirArchivoPropietario($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Valida el correo electrónico del receptor
    $validarEmailReceptor = $usuario->validarEmail($emailReceptor);

    if(!$validarEmailReceptor){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCompartirArchivoEmailInexistente($nombreArchivo,$emailReceptor,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtiene el ID del receptor
    $idReceptor = $usuario->obtenerIdUsuarioE($emailReceptor)[0]["id_usuario"];

    // Obtiene el nombre del receptor
    $nombreReceptor = $usuario->obtenerNombreUsuario($idReceptor)[0]["nombre"];

    // Valida que el propietario y el receptor no sean el mismo usuario
    if($idPropietario === $idReceptor){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorCompartirArchivoMismoUsuario($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Valida si el archivo ya ha sido compartido con el receptor
    $validarReceptor = $archivo->validarReceptor($idPropietario,$idReceptor,$idArchivo);

    if($accion === "agregar"){

        if($validarReceptor){
            $estructuraNotificacion = $notificacionArchivo->notificarErrorCompartirArchivoYaCompartido($nombreArchivo,$nombreReceptor,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $fecha = date("Y-m-d H:i:s");

        // Comparte el archivo
        $compartirArchivo = $archivo->compartirArchivo($idArchivo,$idPropietario,$idReceptor, $fecha);

        if(!$compartirArchivo){
            $estructuraNotificacion = $notificacionArchivo->notificarErrorCompartirArchivoBD($nombreArchivo,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Obtiene datos del receptor
        $obtenerDatosLector = $archivo->obtenerDatosLector($idReceptor);

        $emailLector = $obtenerDatosLector[0]["correo"];
        $nombreLector = $obtenerDatosLector[0]["nombre"];
        $apellidoLector = $obtenerDatosLector[0]["apellido"];
        $imgLector = $obtenerDatosLector[0]["ruta_imagen"];

        // Genera la estructura HTML del receptor
        $estructuraLector = $archivo->agregarLector($emailLector,$nombreLector,$apellidoLector,$imgLector,$idArchivo);

        $estructuraNotificacion = $notificacionArchivo->notificarArchivoCompartido($nombreArchivo,$nombrePropietario,$idReceptor);

        $estructuraNotificacion = $notificacionArchivo->notificarCompartirArchivo($nombreArchivo,$nombreReceptor,$id_usuario);

        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'archivo', 'estructura' => $estructuraLector], JSON_UNESCAPED_UNICODE);
        exit();

    } else {

        if(!$validarReceptor){
            $estructuraNotificacion = $notificacionArchivo->notificarErrorQuitarAccesoArchivo($nombreArchivo,$nombreReceptor,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Quita el acceso al archivo
        $quitarAccesoArchivo = $archivo->quitarAccesoArchivo($idPropietario,$idReceptor,$idArchivo);

        if(!$quitarAccesoArchivo){
            $estructuraNotificacion = $notificacionArchivo->notificarErrorQuitarAccesoArchivoBD($nombreArchivo,$nombreReceptor,$id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $estructuraNotificacion = $notificacionArchivo->notificarQuitarAccesoArchivo($nombreArchivo,$nombreReceptor,$id_usuario);

        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'archivo', 'email' => $emailReceptor], JSON_UNESCAPED_UNICODE);
        exit();

    }

}

?>

