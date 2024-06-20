<?php 
// FUNCION PARA CONECTAR A LA BASE DE DATOS
function conectar($name_db){
    DEFINE("DB_HOST","localhost");
    DEFINE("DB_USER","gestoradmin");
    DEFINE("DB_PASSWORD","A1b2c3d4.");
    DEFINE("DB_NAME","$name_db");

    $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if(!$con){
        die("Error conectado a la BD: ".mysqli_error());
    }
    return $con;
}

// FUNCION PARA SELECT
function select($conexion, $consulta) {
    $resultado = mysqli_query($conexion, $consulta);
    // Verificar si la consulta se ejecutó correctamente
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }
    return $resultado;
}

// FUNCION PARA EJECUTAR CONSULTAS INSERT, UPDATE, DELETE
function consulta($conexion, $consulta) {
    $resultado = mysqli_query($conexion, $consulta);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    return $resultado;

}

// FUNCION PARA CERRAR CONEXION
function cerrarConexion($conexion) {
    mysqli_close($conexion);
    echo "Conexión cerrada correctamente.";
}

// FUNCION PARA IMPRIMIR DATOS DE UNA CONSULTA SELECT
function imprimirDatos($resultado){
    while($fila = mysqli_fetch_assoc($resultado)){
        echo "<tr>";
        foreach($fila as $key => $value){
            echo "<td>$value</td>";
        }
        echo "</tr>";    
    }
}

// FUNCION PARA VALIDAR LA CLAVE PRIMARIA
function validarClavePrimaria($campo, $valor, $resultado){
    $fila = mysqli_fetch_assoc($resultado);
    if(isset($fila[$campo])){
        if($fila[$campo]===$valor){
            return true;
        } else {
            return false;
        }
    }
}

// FUNCION PARA VALIDAR LA CLAVE PRIMARIA
function validarClavePrimariaVarias($campo, $valor, $resultado){
    $validacion = false;
    while($fila = mysqli_fetch_assoc($resultado)){
        if(isset($fila[$campo])){
            if($fila[$campo]===$valor){
                $validacion = true;
            }
        }
    }
    return $validacion;
}


// FUNCION PARA VALIDAR CAMPOS VACÍOS EN UN ARRAY
function validarVacios($datos){
    $vacio = false;
    foreach ($datos as $clave => $valor){
        if(empty($valor)){
            $vacio = true;
            break;
        }
    }
    return $vacio;
}

// FUNCION PARA VALIDAR EL FORMATO DE UN EMAIL
function validarEmail($email){
    // Valido el formato de un correo electrónico utilizando expresiones regulares
    $patron = "/^[a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,15})$/";
    if(preg_match($patron,$email)){
        return true;
    } else{
        return false;
    }
}


// Verificamos la contraseña
function verificarHash($contrasena, $hashContrasena){
    if (password_verify($contrasena, $hashContrasena)) {
        return true;
    } else {
        return false;
    }
}

// Eliminar tildes de una cadena
function quitarTildes($cadena) {
    $buscar = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'];
    $reemplazar = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];

    return strtr($cadena, array_combine($buscar, $reemplazar));
}

/* FUNCIONES PROYECTO GESTOR DE ARCHIVOS*/

/* FUNCIONES REGISTRO USUARIO */

function validarCamposVacios($datos) {
    return in_array('', $datos);
}

// Función para validar el nombre de usuario
function validarNombreUsuario($nombre) {
    return !(strpos($nombre, '/') === false);
}

?>