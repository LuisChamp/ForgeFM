<?php
    // Incluir el archivo para verificar si hay un usuario en la sesión actual
    include_once "../../includes/verificarUsuario.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/imgs/favicon/favicon.ico" type="image/x-icon">
    <!-- Archivos CSS -->
    <link rel="stylesheet" href="../../assets/css/principal/inicio.css">
    <link rel="stylesheet" href="../../assets/css/principal/registros.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/sidebar.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/almacenamiento.css">
    <title>Registros</title>
</head>
<body>
    
    <main class="container">
        <?php 
        // Incluir el archivo de la barra lateral
        include_once "../../includes/caracteristicas/sidebar.php";
        ?>
        <section class="con_principal con_principal_registros">
            <div class="container-table">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1">Nivel</div>
                        <div class="col col-2">ID Evento</div>
                        <div class="col col-3">Fecha</div>
                        <div class="col col-4">Evento</div>
                    </li>
                </ul>
            </div>
            <div class="paginacion"></div>
        </section>
        <?php 
        // Incluir el archivo de almacenamiento
        include_once "../../includes/caracteristicas/almacenamiento.php";
        ?>
    </main>
    <!-- Archivos Javascript -->
    <script src="../../assets/js/funcionalidades/sidebar.js" type="module"></script>
    <script src="../../assets/js/paginas/registros/registros.js" type="module"></script>
    
</body>
</html>