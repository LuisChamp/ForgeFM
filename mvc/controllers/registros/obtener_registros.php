<?php 
// Incluir los archivos de modelo necesarios
require_once "../../models/usuario.php";
require_once "../../models/notificacion.php";

// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables "numObjetos" y "paginaActual" han sido enviadas
if(isset($_POST["numObjetos"]) && isset($_POST["paginaActual"])){
    // Crear instancias de las clases Usuario y Notificacion
    $usuario = new Usuario();
    $notificacion = new Notificacion();
    
    // Obtener el nombre de usuario de la sesión actual
    $nombreUsuario = $_SESSION["usuario"];
    // Obtener el ID de usuario correspondiente al nombre de usuario
    $idUsuario = $usuario->obtenerIdUsuario($nombreUsuario);

    // Obtener el número de objetos por página y la página actual
    $numObjetos = $_POST["numObjetos"];
    $paginaActual = $_POST["paginaActual"];

    // Obtener el total de registros de notificaciones para el usuario
    $obtenerTotalRegistros = $notificacion->obtenerTotalRegistros($idUsuario);
    // Calcular el total de páginas necesarias para mostrar todos los registros
    $totalPaginas = ceil($obtenerTotalRegistros/$numObjetos);

    // Calcular el desplazamiento para la consulta de la base de datos
    $offset = ($paginaActual - 1) * $numObjetos;

    // Obtener los registros paginados
    $registros = $notificacion->obtenerRegistrosPaginacion($idUsuario,$numObjetos,$offset);

    // Inicializar una tabla para almacenar los registros de notificaciones
    $registrosHTML = "<li class='table-header'><div class='col col-1'>Nivel</div><div class='col col-2'>ID Evento</div><div class='col col-3'>Fecha</div><div class='col col-4'>Evento</div></li>";

    // Iterar sobre los registros obtenidos y construir el HTML correspondiente
    foreach($registros as $registro){
        $registrosHTML .= $notificacion->crearRegistro($registro["id_evento"],$registro["fecha_hora"],$registro["evento"],$registro["tipo"]);
    }

    // Convertir la estructura de registros y el total de páginas a formato JSON y enviarlos como respuesta
    echo json_encode(["estructura" => $registrosHTML, "totalPaginas" => $totalPaginas]);
    exit();
}

?>
