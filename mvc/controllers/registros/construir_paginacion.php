<?php 
// Incluir el modelo necesario
require_once "../../models/paginacion.php";

// Verificar si las variables "paginaActual" y "totalPaginas" han sido enviadas
if(isset($_POST["paginaActual"]) && isset($_POST["totalPaginas"])){

    // Obtener los valores de "paginaActual" y "totalPaginas" de la solicitud POST
    $paginaActual = $_POST["paginaActual"];
    $totalPaginas = $_POST["totalPaginas"];

    // Crear una instancia de la clase Paginacion
    $paginacion = new Paginacion($paginaActual,$totalPaginas);

    // Construir la estructura HTML de la paginación
    $paginacionHTML = $paginacion->construirPaginacion($paginaActual,$totalPaginas);

    // Convertir la estructura HTML de la paginación a formato JSON y enviarla como respuesta
    echo json_encode($paginacionHTML);
}

?>
