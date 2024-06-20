<?php 
// Incluir los archivos de modelo necesarios para el controlador
require_once "../../models/archivo.php";
require_once "../../models/usuario.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la variable "idRuta" ha sido enviada
if(isset($_POST["idRuta"])){
    // Obtener el ID de la ruta
    $idRuta = $_POST["idRuta"];

    // Crear una instancia del modelo Archivo y Usuario
    $archivo = new Archivo();
    $usuario = new Usuario();

    // Obtener la ruta del archivo a partir de su ID
    $rutaArchivo = $archivo->obtenerRutaArchivo($idRuta)[0]["ruta_archivo"];

    // Obtener el nombre del archivo a partir de su ruta
    $nombreArchivo = basename($rutaArchivo);

    // Obtener el ID del archivo a partir de su ID de ruta
    $idArchivo = $archivo->obtenerIdDesdeMap($idRuta)[0]["id_archivo"];

    // Obtener el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];
    
    // Obtener los datos del propietario del archivo para compartir
    $propietarioDatos = $usuario->obtenerDatosUsuarioCompartir($nombre_usuario);

    // Obtener el nombre completo del propietario
    $propietarioNombre = $propietarioDatos[0]["nombre"]." ".$propietarioDatos[0]["apellido"]; 
    
    // Obtener el correo electrónico del propietario
    $propietarioEmail = $propietarioDatos[0]["correo"];

    // Obtener la ruta de la imagen del propietario
    $propietarioImg = $propietarioDatos[0]["ruta_imagen"];

    // Obtener los ID de los lectores del archivo
    $idLectores = $archivo->obtenerIdLectores($idArchivo);

    // Obtener los datos de los lectores del archivo
    $lectoresDatos = $archivo->obtenerDatosLectores($idLectores);

    // Inicializar una cadena para almacenar la estructura de los lectores
    $lectoresEstructura = "";

    // Recorrer los datos de los lectores obtenidos y construir la estructura de los lectores
    foreach($lectoresDatos as $lectorDatos){
        $lectoresEstructura .= $archivo->agregarLector($lectorDatos["correo"],$lectorDatos["nombre"],$lectorDatos["apellido"],$lectorDatos["ruta_imagen"],$idRuta);
    }

    // Convertir la estructura de lectores y otros datos a formato JSON y enviarla como respuesta
    echo json_encode(['nombre' => $nombreArchivo, 'propietario_nombre' => $propietarioNombre,'propietario_email' => $propietarioEmail, 'propietario_img' => $propietarioImg, 'lectores' => $lectoresEstructura], JSON_UNESCAPED_UNICODE);
}

?>
