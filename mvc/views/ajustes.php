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
    <link rel="stylesheet" href="../../assets/css/principal/ajustes.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/sidebar.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/almacenamiento.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/modalBox.css">
    <link rel="stylesheet" href="../../assets/css/funcionalidades/notificacion.css">
    <title>Ajustes</title>
</head>
<body>
    
    <main class="container">
        <?php 
        // Incluir el archivo de la barra lateral
        include_once "../../includes/caracteristicas/sidebar.php";
        ?>
        <section class="con_principal con_principal_ajustes">
            <h2>Ajustes</h2>
            <h3>Mi perfil</h3>
            <section class="ajustes_usuario_info">
                <div class="imagen-perfil">
                    <label class="-label" for="file">
                        <!--Icono camera-->
                        <span class="cont-icon-camera">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff" viewBox="0 0 256 256"><path d="M208,56H180.28L166.65,35.56A8,8,0,0,0,160,32H96a8,8,0,0,0-6.65,3.56L75.71,56H48A24,24,0,0,0,24,80V192a24,24,0,0,0,24,24H208a24,24,0,0,0,24-24V80A24,24,0,0,0,208,56Zm-44,76a36,36,0,1,1-36-36A36,36,0,0,1,164,132Z"></path></svg>
                        </span>
                        <span>Cambiar imagen</span>
                    </label>
                    <input id="file" type="file" class="input_cambiar_imagen"/>
                    <img src="" id="output" width="200" />
                </div>
                <div class="usuario_info_detalles">
                    <p class="usuario_info_nombre_titulo">Nombre</p>
                    <p class="usuario_info_nombre">&nbsp;</p>
                </div>
            </section>
            <h3>Seguridad de la cuenta</h3>
            <section class="cambiar_correo">
                <div class="usuario_info_email">
                    <p>Correo electrónico</p>
                    <p class="ajustes_email">&nbsp;</p>
                </div>
                
                <div class="cont_button_cambiar_correo">
                    <button class="btn_cambiar_correo">Cambiar correo electrónico</button>
                </div>
            </section>
            <section class="cambiar_pass">
                <div class="usuario_info_pass">
                    <p>Contraseña</p>
                    <p class="ajustes_pass">Cambia o reestablece la contraseña</p>
                </div>
                <div class="cont_button_cambiar_pass">
                    <button class="btn_cambiar_pass">Cambiar contraseña</button>
                </div>
            </section>
            <section class="eliminar_cuenta">
                <button class="btn_eliminar_cuenta">Eliminar cuenta</button>
                <p class="ajustes_eliminar_cuenta">Elimina definitivamente la cuenta y el acceso a ella</p>
            </section>
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
    <script src="../../assets/js/paginas/ajustes/ajustes.js" type="module"></script>
    <script src="../../assets/js/paginas/ajustes/modalAjustes.js" type="module"></script>
</body>
</html>