<?php 
// Se requieren los archivos de los modelos Archivo, Carpeta y Usuario
require_once "../../../models/archivo.php";
require_once "../../../models/carpeta.php";
require_once "../../../models/usuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprueba si se ha enviado el ID de la ruta de la carpeta
if(isset($_POST["idRuta"])){
    // Obtiene el ID de la ruta de la carpeta desde la solicitud POST
    $idRuta = $_POST["idRuta"];

    // Instancia objetos de las clases Archivo, Carpeta y Usuario
    $archivo = new Archivo();
    $carpeta = new Carpeta();
    $usuario = new Usuario();

    // Obtiene la ruta de la carpeta utilizando su ID
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);

    // Obtiene la ruta completa de la carpeta
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Obtiene el nombre de usuario de la sesión actual
    $nombre_usuario = $_SESSION["usuario"];

    // Obtiene el ID del receptor utilizando el nombre de usuario
    $idReceptor = $usuario->obtenerIdUsuario($nombre_usuario);

    // Verifica si la ruta de la carpeta coincide con la ruta de la carpeta de la sesión
    if($idRuta === $_SESSION["ruta_carpeta"]){
        // Obtiene los datos de los archivos compartidos en la raíz del usuario
        $archivosDatos = $archivo->obtenerArchivosCompartidosRaiz($idReceptor);
    } else {
        // Obtiene los datos de los archivos compartidos en una carpeta específica
        $archivosDatos = $archivo->obtenerArchivosCompartidos($rutaCarpeta,$idReceptor);
    }

    // Inicializa una cadena para almacenar la estructura de los archivos compartidos
    $archivosEstructura = "";

    // Recorre los datos de los archivos compartidos y crea la estructura correspondiente
    foreach($archivosDatos as $archivoDatos){
        $archivosEstructura .= $archivo->crearArchivoCompartido($archivoDatos["id"],$archivoDatos["nombre_archivo"],$archivoDatos["nombre"],$archivoDatos["ruta_icono"],$archivoDatos["tamanio"],$archivoDatos["fecha_creacion"]);
    }

    // Devuelve la estructura de los archivos compartidos en formato JSON
    echo json_encode($archivosEstructura);
}

?>
