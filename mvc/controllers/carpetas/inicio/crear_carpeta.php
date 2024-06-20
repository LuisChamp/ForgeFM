<?php 
// Se incluyen los archivos de clase necesarios
require_once "../../../../lib/libreria.php";
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";
require_once "../../../models/notificacionCarpeta.php";

// Se verifica si la sesión está activa, de lo contrario se inicia una nueva sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se verifica si no se ha enviado el formulario de creación de carpeta, en cuyo caso se redirige a la página de inicio
if(!isset($_POST["enviar_crear_carpeta"])){
    include "../../../views/inicio.php";
    exit();
} else {
    // Se verifica si hay campos vacíos en el formulario enviado
    $verif_campos_vacios= validarVacios($_REQUEST);
    if ($verif_campos_vacios){
        header("Location: ../../../views/inicio.php");
        exit();
    } 

    // Se crean instancias de las clases necesarias
    $carpeta = new Carpeta();
    $notificacionCarpeta = new NotificacionCarpeta();
    $usuario = new Usuario();

    // Se obtienen los datos que han sido enviados
    $nombre_carpeta = $_POST["nombre_carpeta"];
    $fecha = date("Y-m-d H:i:s");
    $nombre_usuario = $_SESSION["usuario"];
    $id_usuario = $usuario->obtenerIdUsuario($nombre_usuario);

    // Validar que el nombre no sea muy largo
    if(strlen($nombre_carpeta) > 255) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorNombreLargo($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Validar que el nombre de la carpeta no contenga '/'
    if (!(strpos($nombre_carpeta, '/') === false)) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorNombreCarpeta($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo nombre contiene una barra invertida, lo que no está permitido
    if (!(strpos($nombre_carpeta, '\\') === false)) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorNombreCarpeta2($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verifica si el nuevo nombre contiene una comilla simple, lo que no está permitido
    if (!(strpos($nombre_carpeta, "'") === false)) {
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorNombreCarpeta3($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Se obtiene el último ID de carpeta
    $ultimo_id = $carpeta->ultimoIdCarpeta();

    // Se establece la ruta de la carpeta a crear
    if(isset($_POST["id_carpeta"])){
        $ruta = $carpeta->obtenerRutaCarpeta($_POST["id_carpeta"]);
        $ruta_carpeta = $ruta[0]["ruta_carpeta"];
        $ruta_carpeta = $ruta_carpeta."/$nombre_carpeta";
    } else {
        $ruta_carpeta = "/gestor/usuarios/$nombre_usuario/carpetas/$nombre_carpeta";
    }

    // Se valida que la ruta de la carpeta no exista previamente
    $validar_ruta = $carpeta->validarRutaCarpeta($ruta_carpeta);
    if($validar_ruta){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCarpetaExistente($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Se obtiene la ruta del directorio padre y su ID
    $rutas_separadas = explode("/", $ruta_carpeta);
    $ruta_carpeta_padre = dirname($ruta_carpeta);
    if(count($rutas_separadas) === 6){
        $id_carpeta_padre = "null";
    } else {
        $id_carpeta_padre = $carpeta->obtenerIdCarpeta($ruta_carpeta_padre);
    }  
            
    // Se agrega la carpeta en la base de datos
    $insertar_carpeta = $carpeta->agregarCarpeta($ultimo_id,$ruta_carpeta,$nombre_carpeta,$fecha,$id_usuario,$id_carpeta_padre);
    if(!$insertar_carpeta){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorInsertarCarpeta($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Se crea un mapeo de la carpeta
    $id_unico = md5(uniqid());
    $mapeo_carpeta = $carpeta->agregarMapeo($id_unico,$ultimo_id);
    if(!$mapeo_carpeta){
        $estructuraNotificacion = $notificacionCarpeta->notificarAdvMapeoCarpeta($id_usuario);
        echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Se crea la carpeta en el servidor
    $crearCarpetaSistema = shell_exec('./../../../../scripts/crear/crearCarpeta.sh '."'$ruta_carpeta'");
    if($crearCarpetaSistema != "0"){
        $estructuraNotificacion = $notificacionCarpeta->notificarErrorCrearCarpetaSistema($id_usuario);
        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Se verifica si la carpeta padre ha sido compartida
    if($id_carpeta_padre !== "null"){
        $idLectores = $carpeta->obtenerIdLectores($id_carpeta_padre);
        if(count($idLectores) !== 0){
            // Si ha sido compartida, entonces se comparte la nueva carpeta con los usuarios
            foreach ($idLectores as $idLector) {
                $carpeta->compartirCarpeta($ultimo_id,$id_usuario,$idLector["id_receptor"],$fecha);
            }
        }
    }

    // Se notifica la creación exitosa de la carpeta
    $estructuraNotificacion = $notificacionCarpeta->notificarCreacionCarpeta($nombre_carpeta,$id_usuario);
    $estructuraCarpeta = $carpeta->obtenerYCrearCarpeta($ultimo_id,$id_usuario);
    // Se devuelve la respuesta exitosa junto con la notificación y la estructura de la carpeta creada
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'estructura' => $estructuraCarpeta], JSON_UNESCAPED_UNICODE);
    exit();
}   
?>
   
