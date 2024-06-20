<?php 

require_once "notificacion.php";

class NotificacionUsuario extends Notificacion {
    
/*****************************************************/

    /***************************NOTIFICACIONES USUARIOS********************************/

    /*******************NOIFICACIONES - INICIO SESION***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarInicioSesion($idUsuario){
        return $this->insertarRegistro("U4600","Se ha iniciado sesión","EXITO",$idUsuario);
    }

    /*****************************************************/


    /*******************NOIFICACIONES - CERRAR SESION***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCerrarSesion($idUsuario){
        return $this->insertarRegistro("U4800","Se ha cerrado sesión","EXITO",$idUsuario);
    }

    /*****************************************************/


    /*******************NOIFICACIONES - CAMBIAR CORREO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCambiarCorreo($correoAnterior,$correoNuevo,$idUsuario){
        $consulta = $this->insertarRegistro("U5000","Se ha cambiado el correo $correoAnterior a $correoNuevo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha cambiado el correo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCambiarCorreoIncorrecto($idUsuario){
        $consulta = $this->insertarRegistro("U5050","No se ha cambiado el correo debido a que el formato del correo no es válido","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Formato de correo incorrecto");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarCorreoInexistente($correo,$idUsuario){
        $consulta = $this->insertarRegistro("U5051"," No se ha cambiado el correo ya que el correo $correo ya le pertenece a un usuario","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Correo $correo le pertenece a un usuario");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarCorreoUsuarioInvalido($idUsuario){
        $consulta = $this->insertarRegistro("U5052","No se ha cambiado el correo ya que no se ha podido validar al usuario","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar correo. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarCorreoBD($idUsuario){
        $consulta = $this->insertarRegistro("U5053","No se ha cambiado el correo ya que ha habido un error al actualizar el correo en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar correo. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMismoCorreo($idUsuario){
        $consulta = $this->insertarRegistro("U5054","No se ha cambiado el correo ya que se ha ingresado el mismo correo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Se ha ingresado el mismo correo");
        }

        return $estructuraNotificacion;
    }
    
    /*****************************************************/


    /*******************NOIFICACIONES - CAMBIAR CONTRASEÑA ***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCambiarPass($idUsuario){
        $consulta = $this->insertarRegistro("U5200","Se ha cambiado la contraseña","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha cambiado la contraseña");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCambiarPassIncorrecta($idUsuario){
        $consulta = $this->insertarRegistro("U5250","La contraseña actual que ha ingresado es incorrecta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Contraseña actual incorrecta");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarPassNoCoinciden($idUsuario){
        $consulta = $this->insertarRegistro("U5251","La contraseñas que has ingresado no coinciden, por lo que no se ha cambiado de contraseña","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Contraseñas no coinciden");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarPassIguales($idUsuario){
        $consulta = $this->insertarRegistro("U5252","La contraseña actual asi como la nueva son las mismas, por lo tanto no se ha actualizado la contraseña","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Contraseña actual y nueva son las mismas");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarPassBD($idUsuario){
        $consulta = $this->insertarRegistro("U5253","Ha ocurrido un error en la base de datos que no ha permitido actualizar la contraseña","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al actualizar contraseña. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }
    
    /*****************************************************/


    /*******************NOIFICACIONES - SUBIR IMAGEN***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarSubirImagen($idUsuario){
        $consulta = $this->insertarRegistro("U5400","Se ha cambiado la foto de perfil","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha cambiado la foto de perfil");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorSubirImagenSistema($idUsuario){
        $consulta = $this->insertarRegistro("U5450","No se ha podido actualizar la imagen debido a un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar imagen. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorSubirImagen($idUsuario){
        $consulta = $this->insertarRegistro("U5451","No se ha podido actualizar la imagen debido a un fallo al subir la imagen","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar imagen. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }
    
    /*****************************************************/

    
    /*******************NOIFICACIONES - ELIMINAR CUENTA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarEliminarCuenta($idUsuario){
        $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha eliminado la cuenta");

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorEliminarCuentaUsuarioIncorrecto($idUsuario){
        $consulta = $this->insertarRegistro("U5650","No se ha eliminado la cuenta debido a un fallo al obtener usuario a eliminar","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al validar usuario");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCuentaBD($idUsuario){
        $consulta = $this->insertarRegistro("U5651","No se ha eliminado la cuenta debido a un fallo en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar cuenta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCuentaSistema($idUsuario){
        $consulta = $this->insertarRegistro("U5652","No se ha eliminado la cuenta debido a un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar cuenta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCuentaSistema2($idUsuario){
        $consulta = $this->insertarRegistro("U5653","No se ha eliminado la cuenta debido a que la carpeta del usuario no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar cuenta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCuentaSistema3($idUsuario){
        $consulta = $this->insertarRegistro("U5654","No se ha eliminado la cuenta debido a un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar cuenta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCuentaSistema4($idUsuario){
        $consulta = $this->insertarRegistro("U5655","No se ha eliminado la cuenta debido a un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar cuenta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }
    
    /*****************************************************/
}
?>