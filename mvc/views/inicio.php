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
    <title>Inicio</title>
</head>
<body>
    
    <main class="container">
        <?php 
        // Incluir el archivo de la barra lateral
        include_once "../../includes/caracteristicas/sidebar.php";
        ?>
        <section class="con_principal">
            <section class="con_path">
                <!-- Ruta de la unidad -->
                <p id="ruta">RUTA: <a href="inicio" data-ruta="" data-usuario="" class="ruta_unidad">Unidad </a><img src="../../assets/imgs/svg/principal/arrow.svg" alt="" class="svg-arrow"> </p>
            </section>
            <?php 
            // Incluir el archivo del modo edicion
            include_once "../../includes/modoEdicion/modoEdicionInicio.php";
            ?>
            <section class="con_carpetas">
                <div class="con_carpetas_top">
                  <div class="con_carpetas_top_izq">
                    <p class="title_carpeta">CARPETAS</p>
                     <!-- Botón para crear una nueva carpeta -->
                    <button class="btn_modal_crear_carpeta"><img src="../../assets/imgs/svg/principal/plus.svg" alt="">Crear carpeta</button>
                  </div>
                  <div class="con_busqueda_carpeta">
                    <input type="search" name="" id="" class="input_busqueda_carpetas" placeholder="Buscar...">
                    <i class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M232.49,215.51,185,168a92.12,92.12,0,1,0-17,17l47.53,47.54a12,12,0,0,0,17-17ZM44,112a68,68,0,1,1,68,68A68.07,68.07,0,0,1,44,112Z"></path></svg>
                    </i>
                  </div>
                </div>
                <div id="carpetas">

                </div>
            </section>
            <section class="con_archivos">
                <div class="con_archivos_top">
                    <div class="con_archivos_top_izq">
                        <p class="title_archivo">ARCHIVOS</p>
                        <!-- Botón para subir un archivo -->
                        <button class="btn_modal_subir_archivo"><img src="../../assets/imgs/svg/principal/upload-simple.svg" alt="">Subir archivo</button>
                    </div>
                    <div class="con_busqueda_archivo">
                        <input type="search" name="" id="" class="input_busqueda_archivos" placeholder="Buscar...">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M232.49,215.51,185,168a92.12,92.12,0,1,0-17,17l47.53,47.54a12,12,0,0,0,17-17ZM44,112a68,68,0,1,1,68,68A68.07,68.07,0,0,1,44,112Z"></path></svg>
                        </i>
                    </div>
                </div>
                <input type="hidden" class="input_hidden" name="id_carpeta" value="">
                <div id="archivos">
                </div>
            </section>
            <?php 
            // Incluir el menú contextual para archivos en inicio
            include_once "../../includes/contextMenu/contextMenu.php";
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
    <script src="../../assets/js/funcionalidades/sidebar.js" type="module"></script>
    <script src="../../assets/js/paginas/inicio/inicio.js" type="module"></script>
    <script src="../../assets/js/funcionalidades/contextMenu/contextmenu.js" type="module"></script>
    <script src="../../assets/js/funcionalidades/modoEdicion/modoEdicion.js" type="module"></script>
    <script src="../../assets/js/funcionalidades/contextMenu/acciones/subirArchivo.js" type="module"></script>
    <script src="../../assets/js/funcionalidades/modalBox.js" type="module"></script>
</body>
</html>