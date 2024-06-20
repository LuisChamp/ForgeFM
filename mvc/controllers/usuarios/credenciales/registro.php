<?php 
// Incluir archivos necesarios
require_once "../../../../lib/libreria.php";
require_once "../../../models/usuario.php";
require_once "../../../models/carpeta.php";

// Iniciar una nueva sesión si no hay una sesión existente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para mostrar un mensaje de error y redirigir al usuario a la página de registro
function mostrarErrorYRedirigir($mensaje) {
    $_SESSION['error_registro'] = "<h4><b>$mensaje</b></h4>";
    // Redirigir a la página de registro
    header("Location: ../../../../registro"); 
    exit();
}

// Función para verificar si un usuario ya existe en la base de datos
function usuarioExiste($usuario) {
    $usuarioObj = new Usuario(); 
    // Validar si el usuario existe
    return $usuarioObj->validarUsuario($usuario);
}

// Función para verificar si un correo ya está registrado
function correoExiste($correo) {
    $usuarioObj = new Usuario();
    // Validar si el correo ya está registrado
    return $usuarioObj->validarCorreo($correo); 
}

// Verificar si se envió el formulario de registro
if(!isset($_POST["enviar_registro"])){
    // Si no ha sido enviado, incluir el formulario de registro
    include "../../../../registro";
    exit();
}

// Validar campos vacíos
if (validarCamposVacios($_REQUEST)) {
    mostrarErrorYRedirigir("Rellene todos los campos por favor");
}

// Validar nombre de usuario
if (validarNombreUsuario($_POST["form_registro_usuario"])) {
    mostrarErrorYRedirigir("El nombre de usuario no puede contener '/'");
}

// Verificar si el usuario ya existe
if (usuarioExiste($_POST["form_registro_usuario"])) {
    mostrarErrorYRedirigir("El usuario ya existe, ingrese otro por favor"); 
}

// Verificar si el correo ya está registrado
if (correoExiste($_POST["form_registro_email"])) {
    mostrarErrorYRedirigir("El correo electrónico ya se ha registrado, ingrese otro por favor"); 
}

// Verificar el formato del correo electrónico
if (!validarEmail($_POST["form_registro_email"])) {
    mostrarErrorYRedirigir("El correo electrónico no es válido");
}

// Función para agregar un usuario y sus carpetas asociadas
function agregarUsuarioYCarpetas($nombre, $apellido, $correo, $pass) {
    $usuarioObj = new Usuario(); 
    $carpetaObj = new Carpeta(); 
    // Obtener el hash de la contraseña
    $hashContrasena = $usuarioObj->obtenerHash($pass); 
    // Obtener la fecha actual
    $fecha = date("Y-m-d");
    // Obtener el último ID de usuario
    $ultimoId = $usuarioObj->ultimoIdUsuario();
    // Establecer el almacenamiento total (30 GB)
    $almacenamientoTotal = 32212254720;
    // Ruta de la imagen por defecto del usuario
    $rutaImagenDefault = "../../assets/imgs/users/default_usuario.jpg";
    // Insertar el nuevo usuario en la base de datos
    $insertUsuario = $usuarioObj->agregarUsuario($ultimoId, $correo, $nombre, $apellido, $hashContrasena, $fecha, $almacenamientoTotal, $rutaImagenDefault);
    if (!$insertUsuario) {
        // Mostrar mensaje de error si la inserción del usuario falla
        mostrarErrorYRedirigir("Ha ocurrido un error al hacer la inserción del usuario"); 
    }

    // Definir las carpetas que se crearán para el usuario
    $carpetas = [
        ["ruta" => "/gestor/usuarios/$nombre/carpetas", "nombre" => "carpetas"],
        ["ruta" => "/gestor/usuarios/$nombre/papelera", "nombre" => "papelera"]
    ];

    // Obtener el último ID de carpeta
    $idCarpeta = $carpetaObj->ultimoIdCarpeta(); 

    // Crear las carpetas para el usuario
    foreach ($carpetas as $carpeta) {
        // Generar un ID único
        $idUnico = md5(uniqid()); 

        // Insertar la carpeta en la base de datos
        $insertarCarpeta = $carpetaObj->agregarCarpeta($idCarpeta, $carpeta["ruta"], $carpeta["nombre"], $fecha, $ultimoId, "null");
        if (!$insertarCarpeta) {
            // Mostrar mensaje de error si la creación de la carpeta falla
            mostrarErrorYRedirigir("Ha ocurrido un error al crear la carpeta del usuario ".$carpeta['nombre']); 
        }

        // Insertar el mapeo de la carpeta en la base de datos
        $insertarMapeo = $carpetaObj->agregarMapeo($idUnico, $idCarpeta);
        if(!$insertarMapeo){
            // Mostrar mensaje de error si la inserción del mapeo falla
            mostrarErrorYRedirigir("Ha ocurrido un error al hacer el mapeo de la carpeta ".$carpeta['nombre']); 
        }

        $idCarpeta++; // Incrementar el ID de carpeta
    }

    // Ejecutar el script para crear las carpetas del usuario en el servidor
    $crearUsuarioYCarpetas = shell_exec('./../../../../scripts/crear/crearUsuario.sh '.$nombre);
    if($crearUsuarioYCarpetas == "0"){
        $_SESSION['correcto_registro'] = "<h4><b>Se ha registrado correctamente</b></h4>"; 
        header("Location: ../../../../registro"); 
        exit(); 
    } else {
        // Mostrar mensaje de error si la creación de carpetas falla
        mostrarErrorYRedirigir("Error al crear las carpetas del usuario en el sistema"); 
    }
}

// Agregar el usuario y sus carpetas asociadas
agregarUsuarioYCarpetas($_POST["form_registro_usuario"], $_POST["form_registro_apellido"], $_POST["form_registro_email"], $_POST["form_registro_pass"]);

?>
