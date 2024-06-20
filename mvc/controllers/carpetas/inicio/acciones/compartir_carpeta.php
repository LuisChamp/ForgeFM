<?php 
// Incluir archivos de las clases necesarias
require_once "../../../../models/carpeta.php";
require_once "../../../../models/usuario.php";
require_once "../../../../models/notificacionCarpeta.php";

// Iniciar sesión si aún no se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se han enviado los datos necesarios
if(isset($_POST["nombrePropietario"]) && isset($_POST["emailReceptor"]) && isset($_POST["idRuta"]) && isset($_POST["accion"])){

    // Obtener datos enviados
    $nombrePropietario = $_POST["nombrePropietario"];
    $emailReceptor = $_POST["emailReceptor"];
    $idRuta = $_POST["idRuta"];
    $accion = $_POST["accion"];

    // Crear instancias de las clases Carpeta, Usuario y NotificacionCarpeta
    $carpeta = new Carpeta();
    $usuario = new Usuario();
    $notificacionCarpeta = new NotificacionCarpeta();

    // Obtener la ruta de la carpeta a partir de su ID
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];

    // Obtener el ID de la carpeta a partir de su mapeo
    $idCarpeta = $carpeta->obtenerIdDesdeMap($idRuta)[0]["id_carpeta"];

    // Validar el nombre del propietario de la carpeta
    $validarNombrePropietario = $usuario->validarNombrePropietario($nombrePropietario);

    // Obtener el nombre de la carpeta a partir de su ruta
    $nombreCarpeta = basename($rutaCarpeta);

    // Obtener el ID del propietario de la carpeta
    $idPropietario = $usuario->obtenerIdUsuario($nombrePropietario);

    // Verificar si el nombre del propietario de la carpeta es válido
    if(!$validarNombrePropietario){
        // Notificar un error si el propietario de la carpeta no existe
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCompartirCarpetaNoExistente($nombreCarpeta,$nombrePropietario,$idPropietario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar si el propietario de la carpeta es válido
    $validarPropietarioCarpeta = $carpeta->validarPropietarioCarpeta($idPropietario,$idCarpeta);
    if(!$validarPropietarioCarpeta){
        // Notificar un error si el propietario de la carpeta no coincide con el propietario especificado
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCompartirCarpetaNoExistente2($nombreCarpeta,$idPropietario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Validar el email del receptor de la carpeta
    $validarEmailReceptor = $usuario->validarEmail($emailReceptor);
    if(!$validarEmailReceptor){
        // Notificar un error si el email del receptor es inválido
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCompartirCarpetaEmailInvalido($nombreCarpeta,$emailReceptor,$idPropietario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtener el ID del receptor a partir de su email
    $idReceptor = $usuario->obtenerIdUsuarioE($emailReceptor)[0]["id_usuario"];

    // Verificar que el propietario y el receptor no sean la misma persona
    if($idPropietario === $idReceptor){
        // Notificar un error si el propietario y el receptor son la misma persona
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCompartirCarpetaMismoReceptor($nombreCarpeta,$idPropietario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Obtener el nombre del receptor
    $nombreReceptor = $usuario->obtenerNombreUsuario($idReceptor)[0]["nombre"];

    // Validar si el receptor ya ha sido compartido con esta carpeta
    $validarReceptor = $carpeta->validarReceptor($idPropietario,$idReceptor,$idCarpeta);

    // Realizar acciones según la acción especificada (agregar o quitar)
    if($accion === "agregar"){
        // Si se va a agregar acceso a la carpeta

        if($validarReceptor){
            // Notificar una advertencia si el receptor ya tiene acceso a la carpeta
            $estructuraNotificacion = $notificacionCarpeta->notificarAdvCompartirCarpetaExistente($nombreCarpeta,$nombreReceptor,$idPropietario);
            echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Obtener la fecha actual
        $fecha = date("Y-m-d H:i:s");

        // Compartir la carpeta y subcarpetas
        $compartirCarpeta = $carpeta->compartirCarpeta($idCarpeta,$idPropietario,$idReceptor, $fecha);

        if(!$compartirCarpeta){
            // Notificar un error si no se puede compartir la carpeta en la base de datos
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorCompartirCarpetaBD($nombreCarpeta,$idPropietario);
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Capturar IDs de las carpetas a compartir
        $capturarIdCarpetasACompartir = $carpeta->capturarIdCarpetasACompartir($idPropietario,$idReceptor,$rutaCarpeta);

        // Compartir cada subcarpeta con el receptor
        for ($i=0; $i < count($capturarIdCarpetasACompartir); $i++) { 
            $fechaCompartido = date("Y-m-d H:i:s");
            $compartirCarpeta = $carpeta->compartirCarpeta($capturarIdCarpetasACompartir[$i]["id_carpeta"],$idPropietario,$idReceptor, $fechaCompartido);
        }

        // Capturar IDs de los archivos a compartir
        $capturarIdArchivosACompartir = $carpeta->capturarIdArchivosACompartir($idPropietario,$idReceptor,$rutaCarpeta);

        // Compartir cada archivo con el receptor
        for ($i=0; $i < count($capturarIdArchivosACompartir); $i++) { 
            $fechaCompartido = date("Y-m-d H:i:s");
            $compartirArchivo = $carpeta->compartirArchivo($capturarIdArchivosACompartir[$i]["id_archivo"],$idPropietario,$idReceptor, $fechaCompartido);
        }

        // Obtener datos del receptor al que se le ha compartido
        $obtenerDatosLector = $carpeta->obtenerDatosLector($idReceptor);

        // Extraer información del receptor
        $emailLector = $obtenerDatosLector[0]["correo"];
        $nombreLector = $obtenerDatosLector[0]["nombre"];
        $apellidoLector = $obtenerDatosLector[0]["apellido"];
        $imgLector = $obtenerDatosLector[0]["ruta_imagen"];

        // Generar estructura HTML del receptor
        $estructuraLector = $carpeta->agregarLector($emailLector,$nombreLector,$apellidoLector,$imgLector,$idCarpeta);

        // Notificar al propietario del éxito de compartir la carpeta
        $estructuraNotificacion = $notificacionCarpeta->notificarCompartirCarpetaEmisor($nombreCarpeta,$nombrePropietario,$idReceptor);

        // Notificar al receptor del éxito de compartir la carpeta
        $estructuraNotificacion = $notificacionCarpeta->notificarCompartirCarpetaReceptor($nombreCarpeta,$nombreReceptor,$idPropietario);

        // Devolver respuesta de éxito junto con la estructura del receptor en formato JSON
        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta', 'estructura' => $estructuraLector], JSON_UNESCAPED_UNICODE);
        exit();
    
    } else {
        // Si se va a quitar acceso a la carpeta
    
        if(!$validarReceptor){
            // Notificar un error si el receptor no tiene acceso a la carpeta
            $estructuraNotificacion = $notificacionCarpeta->notificarErrorQuitarAcceso($nombreCarpeta,$nombreReceptor,$idPropietario);
            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    
        // Quitar acceso a la carpeta y subcarpetas
        $quitarAccesoCarpeta = $carpeta->quitarAccesoCarpeta($idPropietario,$idReceptor,$rutaCarpeta);
    
        // Quitar acceso a cada subcarpeta
        $quitarAccesoCarpetas = $carpeta->quitarAccesoCarpetas($idPropietario,$idReceptor,$rutaCarpeta);
    
        if(!$quitarAccesoCarpetas){
            // Notificar una advertencia si no se pueden quitar los accesos a las subcarpetas
            $estructuraNotificacion = $notificacionCarpeta->notificarAdvQuitarAccesoSubcarpetas($nombreCarpeta,$nombreReceptor,$idPropietario);
            echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    
        // Quitar acceso a cada archivo
        $quitarAccesoArchivos = $carpeta->quitarAccesoArchivos($idPropietario,$idReceptor,$rutaCarpeta);
    
        if(!$quitarAccesoArchivos){
            // Notificar una advertencia si no se pueden quitar los accesos a los archivos
            $estructuraNotificacion = $notificacionCarpeta->notificarAdvQuitarAccesoArchivos($nombreCarpeta,$nombreReceptor,$idPropietario);
            echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    
        // Notificar al propietario del éxito de quitar el acceso a la carpeta
        $estructuraNotificacion = $notificacionCarpeta->notificarQuitarAccesoCarpeta($nombreCarpeta,$nombreReceptor,$idPropietario);
    
        // Devolver respuesta de éxito junto con el email del receptor en formato JSON
        echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'tipo' => 'carpeta', 'email' => $emailReceptor], JSON_UNESCAPED_UNICODE);
        exit();
    
    }
}

?>

