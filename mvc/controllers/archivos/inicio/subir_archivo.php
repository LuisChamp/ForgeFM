<?php 
// Incluir los archivos necesarios para el funcionamiento del script
require_once "../../../models/archivo.php";
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";
require_once "../../../models/notificacionArchivo.php";

// Iniciar sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se han enviado archivos y el id de la ruta
if(isset($_FILES["archivo"]) && !empty($_FILES["archivo"]["name"]) && isset($_POST["idRuta"])){

    // Crear instancias de las clases necesarias
    $archivo = new Archivo();
    $usuario = new Usuario();
    $carpeta = new Carpeta();
    $notificacionArchivo = new NotificacionArchivo();

    // Obtener el id de la ruta de la carpeta donde se sube el archivo
    $idRuta = $_POST["idRuta"];

    // Obtener el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtener el tamaño total de almacenamiento permitido para el usuario
    $tamanioTotal = intval($usuario->obtenerAlmacenamientoTotal($nombreUsuario)[0]["almacenamiento_total"]);

    // Obtener el id de la carpeta raíz de la sesión actual
    $idRutaRaiz = $_SESSION["ruta_carpeta"];

    // Obtener la ruta de la carpeta correspondiente al id de ruta
    $rutaCarpeta = $carpeta->obtenerRutaCarpeta($idRuta)[0]["ruta_carpeta"];

    // Obtener la ruta de la carpeta raíz
    $rutaRaiz = $carpeta->obtenerRutaCarpeta($idRutaRaiz)[0]["ruta_carpeta"];

    // Obtener el id de usuario asociado al nombre de usuario
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Obtener detalles del archivo subido
    $nombreArchivo = $_FILES["archivo"]["name"];
    $tmpNombreArchivo = $_FILES["archivo"]["tmp_name"];
    $tamanioArchivo = $_FILES["archivo"]["size"];
    $errorArchivo = $_FILES["archivo"]["error"];
    $tipoArchivo = $_FILES["archivo"]["type"];

    // Verificar si hay algún error al subir el archivo
    if($errorArchivo !== 0){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorTransferirArchivo($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Construir la ruta del archivo
    $rutaArchivo = $rutaCarpeta."/$nombreArchivo";                        

    // Validar si la ruta del archivo ya existe
    $validarRuta = $archivo->validarRutaArchivo($rutaArchivo);

    if($validarRuta){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorSubirArchivoExistente($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar el tamaño total de la carpeta
    $tamanioCarpeta =  shell_exec('./../../../../scripts/verificarTamanio/verifTamanioTotal.sh '."'$rutaRaiz'");
    $intTamanioCarpeta = intval($tamanioCarpeta);

    // Verificar si el espacio disponible es suficiente
    if($tamanioTotal < $intTamanioCarpeta + $tamanioArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorEspacioInsuficiente($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Mover el archivo a la carpeta correspondiente
    move_uploaded_file($tmpNombreArchivo,$rutaArchivo);

    // Obtener el último ID de archivo
    $ultimoIdArchivo = $archivo->ultimoIdArchivo();

    // Obtener la fecha de creación actual
    $fechaCreacion = date("Y-m-d H:i:s");

    // Obtener partes de la ruta del archivo
    $partesRuta = explode("/", $rutaArchivo);

    // Verificar si la carpeta tiene una carpeta padre
    if(count($partesRuta) === 6){
        $idCarpeta = "null"; // Si no hay carpeta padre
    } else {
        $idCarpeta = $carpeta->obtenerIdCarpeta($rutaCarpeta); // Si hay carpeta padre
    }   

    // Insertar los detalles del archivo en la base de datos
    $insertarArchivo = $archivo->agregarArchivo($ultimoIdArchivo,$rutaArchivo,$nombreArchivo,$tamanioArchivo,$fechaCreacion,$tipoArchivo,$id_usuario,$idCarpeta);

    // Verificar si se pudo insertar el archivo en la base de datos
    if(!$insertarArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarErrorSubirArchivoBD($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Generar un ID único para el archivo
    $idUnico = md5(uniqid());

    // Agregar el mapeo del archivo
    $mapeoArchivo = $archivo->agregarMapeo($idUnico,$ultimoIdArchivo);

    // Verificar si se pudo agregar el mapeo del archivo
    if(!$mapeoArchivo){
        $estructuraNotificacion = $notificacionArchivo->notificarAdvSubirArchivoMapeo($nombreArchivo,$id_usuario);

        echo json_encode(['respuesta' => 'advertencia','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Verificar si la carpeta padre ha sido compartida
    if($idCarpeta !== "null"){
        $idLectores = $carpeta->obtenerIdLectores($idCarpeta);
        if(count($idLectores) !== 0){
            // Si ha sido compartida, entonces compartir el archivo con todos los usuarios que estan en la lista de acceso
            foreach ($idLectores as $idLector) {
                $archivo->compartirArchivo($ultimoIdArchivo,$id_usuario,$idLector["id_receptor"],$fechaCreacion);
            }
        }
    }

    // Notificar éxito en la subida del archivo
    $estructuraNotificacion = $notificacionArchivo->notificarSubirArchivo($nombreArchivo,$id_usuario);

    // Enviar respuesta JSON con detalles del archivo subido
    echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, 'estructura' => $archivo->obtenerYCrearArchivo($ultimoIdArchivo,$id_usuario)], JSON_UNESCAPED_UNICODE);
    exit();
    
}
