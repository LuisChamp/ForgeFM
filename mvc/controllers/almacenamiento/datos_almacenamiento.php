<?php 
// Se requieren los archivos de los modelos Carpeta y Usuario
require_once "../../models/carpeta.php";
require_once "../../models/usuario.php";

// Comienza la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprueba si se ha enviado el ID de la ruta de la carpeta
if(isset($_POST["idRuta"])){
    // Obtiene el ID de la ruta de la carpeta desde la solicitud POST
    $idRuta = $_POST["idRuta"];

    // Instancia objetos de las clases Carpeta y Usuario
    $carpeta = new Carpeta();
    $usuario = new Usuario();

    // Obtiene la ruta de la carpeta utilizando su ID
    $ruta = $carpeta->obtenerRutaCarpeta($idRuta);

    // Obtiene el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];

    // Obtiene la ruta completa de la carpeta
    $rutaCarpeta = $ruta[0]["ruta_carpeta"];

    // Divide la ruta en partes separadas por "/"
    $partesRuta = explode("/", $rutaCarpeta);

    // Elimina el último elemento del array (el nombre de la carpeta actual)
    array_pop($partesRuta);

    // Une las partes de la ruta nuevamente para formar la ruta anterior
    $rutaAVerificar = implode("/", $partesRuta);

    // Ejecuta un script de shell para verificar el tamaño de almacenamiento en la carpeta
    $datosAlmacenamiento = shell_exec('./../../../scripts/verificarTamanio/datosAlmacenamiento.sh '."'$rutaAVerificar'");

    // Obtiene el tamaño total de almacenamiento permitido para el usuario en gigabytes
    $almacenamientoTotal = intval($usuario->obtenerAlmacenamientoTotal($nombreUsuario)[0]["almacenamiento_total"]);

    // Convierte el almacenamiento total a gigabytes y lo formatea para mostrarlo
    $almacenamientoFormateado = floor($almacenamientoTotal / (1024 * 1024 * 1024));

    // Devuelve una respuesta JSON con el almacenamiento total y los datos de almacenamiento de la carpeta
    echo json_encode(["almacenamientoTotal" => $almacenamientoFormateado, "datosAlmacenamiento" => $datosAlmacenamiento]);
    exit();
}

?>
