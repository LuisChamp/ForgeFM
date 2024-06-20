<?php 
    include_once "../../../../includes/verificarInicioSesion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../../assets/imgs/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../../../assets/css/form/form.css">

    <title>Inicio Sesión</title>
</head>
<body>
    <!-- Contenedor principal del formulario de inicio de sesión -->
    <div class="login-box">
    <h2>Inicio Sesión</h2>
    <!-- Formulario de inicio de sesión -->
    <form action="mvc/controllers/usuarios/credenciales/inicio_sesion.php" method="post">
         <!-- Campo de entrada para el nombre de usuario -->
        <div class="user-box">
            <input type="text" name="form_inicio_usuario" required="">
            <label>Nombre (Usuario)</label>
        </div>
        <!-- Campo de entrada para la contraseña -->
        <div class="user-box">
            <input type="password" name="form_inicio_pass" required="">
            <label>Contraseña</label>
        </div>
        <!-- PHP para mostrar mensajes de validación o éxito -->
        <?php

            require_once "../../../models/carpeta.php";
            // Inicio sesión si no hay una sesión existente
            if (session_status() == PHP_SESSION_NONE) {
                    session_start();
            }

            // Muestro el mensaje de error indicado
            if (isset($_SESSION['error_usuario'])) {
                echo  $_SESSION['error_usuario'];
                // Elimino la variable de sesión después de mostrarla
                unset($_SESSION['error_usuario']); 
            }

            // Verifico si hay un mensaje de inicio correcto o un inicio existente
            if(isset($_SESSION['correcto_inicio']) || isset($_SESSION['inicio_existente'])){

                if(isset($_SESSION['correcto_inicio'])){
                    echo $_SESSION['correcto_inicio'];
                    unset($_SESSION['correcto_inicio']);
                } else {
                    echo $_SESSION['inicio_existente'];
                    unset($_SESSION['inicio_existente']);
                }

                // Obtengo el usuario de la sesión
                $usuario = $_SESSION["usuario"];
                $carpetaObj = new Carpeta();

                // Obtengo los IDs de las carpetas iniciales del usuario
                $idCarpetasIniciales = $carpetaObj->obtenerIdCarpetasIniciales($usuario);

                // Guardo en la sesión la ruta de la carpeta raiz y la papelera
                $_SESSION["ruta_carpeta"] = $carpetaObj->obtenerIdMap($idCarpetasIniciales[0]["id_carpeta"])[0]["id"];
                $_SESSION["ruta_papelera"] = $carpetaObj->obtenerIdMap($idCarpetasIniciales[1]["id_carpeta"])[0]["id"];

                // Redirijo a la página de inicio después de 2 segundos
                echo '<script>
                        setTimeout(function(){
                            window.location.href = "inicio";
                        }, 2000);
                    </script>';
            }

        ?>
        <!-- Botón de envío del formulario -->
        <button type="submit" name="enviar_inicio" value="enviar">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            ENVIAR
        </button>
        <!-- Enlace para ir a la página de registro -->
        <a href="registro" class="link_regresar">REGISTRO</a>
    </form>
    </div>
</body>
</html>
