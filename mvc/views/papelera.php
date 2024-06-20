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
    <link rel="stylesheet" href="../../assets/css/funcionalidades/sidebar.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/contextmenu.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/almacenamiento.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/modalBox.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/notificacion.css">
    <title>Papelera</title>
</head>
<body>
    <main class="container">
        <?php 
        // Incluir el archivo de la barra lateral
        include_once "../../includes/caracteristicas/sidebar.php";
        ?>
        <section class="con_principal">
            <section class="vaciar_papelera">
                <p>Los elementos se van a eliminar luego de 30 días</p>
                <button class="btn_modal_vaciar_papelera">Vaciar papelera</button>
            </section>
            <?php
                // Incluir el archivo del modo edicion
                include_once "../../includes/modoEdicion/modoEdicionPapelera.php";
            ?>
            <section class="con_carpetas">
                <div class="con_carpetas_top">
                  <div class="con_carpetas_top_izq">
                    <p class="title_carpeta">CARPETAS</p>
                  </div>
                </div>
                <div id="carpetas">

                </div>
            </section>
            <section class="con_archivos">
                <div class="con_archivos_top">
                    <div class="con_archivos_top_izq">
                        <p class="title_archivo">ARCHIVOS</p>
                    </div>
                </div>
                <div id="archivos">
                
                </div>
            </section>
            <?php 
                // Incluir el menú contextual para archivos en papelera
                include_once "../../includes/contextMenu/contextMenuPapelera.php";
            ?>
        </section>
        <?php 
        // Incluir el archivo de almacenamiento
        include_once "../../includes/caracteristicas/almacenamiento.php";
        // Incluir el archivo de modal box
        include_once "../../includes/modalBox/modalBox.php";
        ?>
    </main>
    <!-- Contenedor para las notificaciones -->
    <div id="toastBox">
    
    </div>
    <!-- Archivos Javascript -->
    <script src="assets/js/funcionalidades/sidebar.js" type="module"></script>
    <script src="assets/js/paginas/papelera/papelera.js" type="module"></script>
    <script src="assets/js/funcionalidades/contextMenu/contextmenu.js" type="module"></script>
    <script src="assets/js/funcionalidades/modoEdicion/modoEdicion.js" type="module"></script>

</body>
</html>