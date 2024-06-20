<?php 
// Se requieren los archivos de los modelos Usuario, Archivo y NotificacionUsuario
require_once "../../models/usuario.php";
require_once "../../models/archivo.php";
require_once "../../models/notificacionUsuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprueba si se ha enviado un archivo
if(isset($_FILES['imagen']['name'])) {

    // Define las rutas de los directorios donde se guardarán y se almacenarán la imagen
    $rutaDirectorio = "../../../assets/imgs/users/";
    $rutaDirectorioAGuardar = "../../assets/imgs/users/";
    // Obtiene la ruta completa del archivo imagen
    $rutaImagen = $rutaDirectorio . basename($_FILES['imagen']['name']);
    $rutaImagenAGuardar = $rutaDirectorioAGuardar . basename($_FILES['imagen']['name']);

    // Instancia objetos de las clases Usuario y NotificacionUsuario
    $usuario = new Usuario(); 
    $notificacionUsuario = new NotificacionUsuario();

    // Obtiene el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtiene el ID del usuario actual
    $id_usuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Intenta mover el archivo subido al directorio de imágenes
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
        // Actualiza la imagen de perfil del usuario en la base de datos
        $actualizarImgUsuario = $usuario->actualizarImgUsuario($nombreUsuario,$rutaImagenAGuardar);
        // Si no se puede actualizar la imagen en la base de datos, muestra un mensaje de error y termina el script
        if(!$actualizarImgUsuario){
            $estructuraNotificacion = $notificacionUsuario->notificarErrorSubirImagenSistema($id_usuario);

            echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
            exit();
        }
    } else {
        // Si no se puede mover el archivo, muestra un mensaje de error y termina el script
        $estructuraNotificacion = $notificacionUsuario->notificarErrorSubirImagen($id_usuario);

        echo json_encode(['respuesta' => 'error','notificacion' => $estructuraNotificacion], JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// Obtiene la estructura de notificación de éxito para subir una imagen
$estructuraNotificacion = $notificacionUsuario->notificarSubirImagen($id_usuario);

// Devuelve una respuesta JSON con el resultado exitoso y la ruta de la imagen guardada
echo json_encode(['respuesta' => 'exito','notificacion' => $estructuraNotificacion, "rutaImagen" => "$rutaImagenAGuardar"], JSON_UNESCAPED_UNICODE);
exit();

?>
