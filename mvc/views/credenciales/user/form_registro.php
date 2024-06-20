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

    <title>Registro</title>
</head>
<body>
    <div class="login-box">
    <h2>Registro</h2>
    <!-- Formulario de registro -->
    <form action="mvc/controllers/usuarios/credenciales/registro.php" method="post">
        <!-- Campo de entrada para el nombre de usuario -->
        <div class="user-box">
            <input type="text" name="form_registro_usuario" required="">
            <label>Nombre (Usuario)</label>
        </div>
        <div class="user-box">
            <input type="text" name="form_registro_apellido" required="">
            <label>Apellido</label>
        </div>
        <!-- Campo de entrada para la dirección de correo electrónico -->
        <div class="user-box">
            <input type="text" name="form_registro_email" required="">
            <label>Email</label>
        </div>
        <!-- Campo de entrada para la contraseña -->
        <div class="user-box">
            <input type="password" name="form_registro_pass" required="">
            <label>Contraseña</label>
        </div>
         <!-- Sección de mensajes de validación y éxito -->
        <?php
            // Inicio sesión si no hay una sesión existente
            if (session_status() == PHP_SESSION_NONE) {
                    session_start();
            }

            // Muestro el mensaje de error indicado
            if (isset($_SESSION['error_registro'])) {
                echo  $_SESSION['error_registro'];
                // Elimino la variable de sesión después de mostrarla
                unset($_SESSION['error_registro']); 
            }

            // Mensaje de éxito después del registro
            if(isset($_SESSION['correcto_registro'])){
                echo $_SESSION['correcto_registro'];
                // Elimino la variable de sesión después de mostrarla
                unset($_SESSION['correcto_registro']); 
                echo '<script>
                        setTimeout(function(){
                            window.location.href = "inicio_sesion";
                        }, 2000);
                    </script>';
            }
        ?>
        <!-- Botón de envío del formulario -->
        <button type="submit" name="enviar_registro" value="enviar">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            ENVIAR
        </button>
        <!-- Enlace para regresar a la página de inicio de sesión -->
        <a href="inicio_sesion" class="link_regresar">INICIO</a>
    </form>
    </div>
</body>
</html>
