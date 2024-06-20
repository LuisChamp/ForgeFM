<?php 

require_once "notificacion.php";

class NotificacionArchivo extends Notificacion {
    
/*****************************************************/

    /***************************NOTIFICACIONES ARCHIVOS********************************/

    /*******************NOIFICACIONES AL SUBIR ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarSubirArchivo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A2800","Se ha subido el archivo $nombreArchivo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha subido el archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorTransferirArchivo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A2850","No se ha subido el archivo $nombreArchivo debido a un error al transferir archivo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al subir archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorSubirArchivoExistente($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A2851","No se ha subido el archivo $nombreArchivo debido a que ya existe un archivo con ese mismo nombre","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Ya existe un archivo con el nombre $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEspacioInsuficiente($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A2852","No se ha subido el archivo $nombreArchivo debido a que no hay espacio disponible","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No hay espacio disponible");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorSubirArchivoBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A2853","No se ha podido agregar el archivo $nombreArchivo a la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al subir archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvSubirArchivoMapeo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A2900","No se ha podido hacer el mapeo del archivo $nombreArchivo","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al mapear archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    /*******************NOIFICACIONES - CAMBIAR NOMBRE ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCambiarNombreArchivo($nombreAnterior,$nombreNuevo,$idUsuario){
        $consulta = $this->insertarRegistro("A3000","Se ha cambiado el nombre del archivo $nombreAnterior a $nombreNuevo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Cambio de nombre $nombreAnterior a $nombreNuevo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCambiarNombreArchivoExistente($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3050","No se ha cambiado el nombre al archivo $nombreArchivo debido a que ya existe un archivo con ese mismo nombre","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Ya existe un archivo con el nombre $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3051","No se ha cambiado el nombre al archivo $nombreArchivo debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar de nombre archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoServidor($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3052","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor debido a que no se han pasado todos los parametros","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar de nombre archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoServidor2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3053","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor ya que el archivo no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar de nombre archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoServidor3($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3054","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor debido a que ya existe un archivo con el mismo nombre","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar de nombre archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoServidor4($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3055","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor debido a un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar de nombre archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambioArchivoNombre($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3056","No se ha podido cambiar el nombre del archivo $nombreArchivo debido a que su nombre no puede llevar /","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener '/'");
        }

        return $estructuraNotificacion;
    } 

    public function notificarErrorCambioArchivoNombre2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3057","No se ha podido cambiar el nombre del archivo $nombreArchivo debido a que su nombre no puede llevar una barra invertida","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener '\\'");
        }

        return $estructuraNotificacion;
    } 

    public function notificarErrorCambioArchivoNombre3($idUsuario){
        $consulta = $this->insertarRegistro("A3058","No se ha podido cambiar el nombre debido a que su nombre no puede llevar comillas simples","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener comillas simples");
        }

        return $estructuraNotificacion;
    } 

    public function notificarErrorNombreLargo($idUsuario){
        $consulta = $this->insertarRegistro("C1056","El nombre del archivo no puede ser muy largo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Nombre de archivo muy largo");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*****************************************************/

    /*******************NOIFICACIONES - MOVER ARCHIVO PAPELERA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarMoverArchivoPapelera($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3200","Se ha movido el archivo $nombreArchivo a la papelera","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha movido el archivo $nombreArchivo a la papelera");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorMoverArchivoBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3250","No se ha movido el archivo $nombreArchivo a la papelera debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverArchivoServidor($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3251","No se ha movido el archivo $nombreArchivo a la papelera en el servidor debido a que no se han pasado todos los parametros","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverArchivoServidor2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3252","No se ha movido el archivo $nombreArchivo a la papelera en el servidor debido a que el archivo no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverArchivoServidor3($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3253","No se ha movido el archivo $nombreArchivo a la papelera en el servidor debido a que no se ha encontrado la papelera","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverArchivoServidor4($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3254","No se ha movido el archivo $nombreArchivo a la papelera en el servidor debido a que ha ocurrido un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*****************************************************/

    /*******************NOIFICACIONES - MOVER ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarMoverArchivo($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3400","Se ha movido el archivo $nombreArchivo a la carpeta $nombreCarpeta","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha movido el archivo $nombreArchivo a la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarMoverArchivoMismaCarpeta($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3450","No se ha movido el archivo $nombreArchivo debido a que ya se encuentra en la carpeta $nombreCarpeta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El archivo $nombreArchivo ya se encuentra en la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarMoverArchivoExistente($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3451","No se ha movido el archivo $nombreArchivo debido a que ya se encuentra en la carpeta $nombreCarpeta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Ya existe un archivo con el nombre $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarMoverArchivoBD($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3452","No se ha movido el archivo $nomberArchivo debido a que ha ocurrido un fallo en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo en la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarMoverArchivoServidor($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3453","No se ha movido el archivo $nombreArchivo en el servidor debido a que no se han pasado todos los parametros","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo en la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarMoverArchivoServidor2($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3454","No se ha movido el archivo $nombreArchivo en el servidor debido a que el archivo no existe en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo en la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarMoverArchivoServidor3($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3455","No se ha movido el archivo $nombreArchivo en el servidor debido a que la carpeta donde se quiere mover no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo en la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarMoverArchivoServidor4($nombreArchivo,$nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("A3456","No se ha movido el archivo $nombreArchivo en el servidor debido a que ha ocurrido un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover archivo $nombreArchivo en la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*****************************************************/

    /*******************NOIFICACIONES - COMPARTIR ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCompartirArchivo($nombreArchivo,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("A3600","Se ha compartido el archivo $nombreArchivo al usuario $nombreReceptor","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha compartido el archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarArchivoCompartido($nombreArchivo,$nombreEmisor,$idUsuario){
        $consulta = $this->insertarRegistro("A3601","El usuario $nombreEmisor te ha compartido el archivo $nombreArchivo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se te ha compartido el archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarQuitarAccesoArchivo($nombreArchivo,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("A3602","Se ha quitado acceso al usuario $nombreReceptor del archivo $nombreArchivo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha quitado acceso al usuario $nombreReceptor del archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCompartirArchivoUsuarioInexistente($nombrePropietario,$idUsuario){
        $consulta = $this->insertarRegistro("A3650","El usuario $nombrePropietario no es correcto, ya que no existe en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al obtener propietario");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirArchivoPropietario($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3651","No eres propietario del archivo $nombreArchivo, por lo que no se ha compartido el archivo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al compartir archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirArchivoEmailInexistente($nombreArchivo,$emailReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("A3652","No se ha compartido el archivo $nombreArchivo, ya que el email $emailReceptor no pertenece a ningún usuario","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Email $emailReceptor no pertenece a ningun usuario");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirArchivoMismoUsuario($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3653","No te puedes compartir tu propio archivo $nombreArchivo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No te puedes compartir tu propio archivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirArchivoYaCompartido($nombreArchivo,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("A3654","No se ha compartido el archivo $nombreArchivo ya que al usuario $nombreReceptor ya se le ha compartido la carpeta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al compartir archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirArchivoBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3655","No se ha compartido el archivo $nombreArchivo debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al compartir archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorQuitarAccesoArchivo($nombreArchivo,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("A3656","No se ha quitado acceso al usuario $nombreReceptor ya que no tiene compartido el archivo $nombreArchivo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al quitar acceso al usuario $nombreReceptor");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorQuitarAccesoArchivoBD($nombreArchivo,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("A3657","No se ha quitado acceso al usuario $nombreReceptor del archivo $nombreArchivo debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al quitar acceso al usuario $nombreReceptor. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*******************NOIFICACIONES - DESCARGAR ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarDescargarArchivo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3800","Se ha descargado el archivo $nombreArchivo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha descargado el archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorDescargarArchivo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A3850","No se puede descargar el archivo $nombreArchivo ya que no se ha encontrado el archivo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al descargar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }
    
    /*****************************************************/


    /*******************NOIFICACIONES - CAMBIAR NOMBRE ARCHIVO PAPELERA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCambiarNombreArchivoPapelera($nombreAnterior,$nombreNuevo,$idUsuario){
        $consulta = $this->insertarRegistro("A4000","Se ha cambiado el nombre del archivo $nombreAnterior a $nombreNuevo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Cambio de nombre $nombreAnterior a $nombreNuevo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCambiarNombreArchivoPapeleraBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4050","No se ha cambiado el nombre del archivo $nombreArchivo debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoPapeleraServidor($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4051","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor debido a que no se han pasado todos los parametros","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoPapeleraServidor2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4052","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor ya que el archivo no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoPapeleraServidor3($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4053","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor ya que ya existe un archivo con el mismo nombre","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreArchivoPapeleraServidor4($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4054","No se ha cambiado el nombre del archivo $nombreArchivo en el servidor debido a un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    /*******************NOIFICACIONES - ELIMINAR ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarEliminarArchivo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4200","Se ha eliminado el archivo $nombreArchivo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha eliminado el archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorEliminarArchivoPapelera($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4250","No se ha eliminado el archivo $nombreArchivo ya que no se ha encontrado el archivo en la papelerao","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al encontrar archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarArchivoBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4251","No se ha eliminado el archivo $nombreArchivo ya que ha habido un fallo en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarArchivoServidor($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4252","No se ha eliminado el archivo $nombreArchivo en el servidor debido a que no se han pasado todos los parametros","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarArchivoServidor2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4253","No se ha eliminado el archivo $nombreArchivo en el servidor ya que el archivo no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarArchivoServidor3($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4254","No se ha eliminado el archivo $nombreArchivo en el servidor ya que ha ocurrido un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    /*******************NOIFICACIONES - RESTAURAR ARCHIVO***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarRestaurarArchivo($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4400","Se ha restaurado el archivo $nombreArchivo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha restaurado el archivo $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorRestaurarArchivoExistente($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4450","No se ha restaurado el archivo $nombreArchivo debido a que ya existe un archivo que se encuentra en la ruta donde se quiere restaurar, cambie de nombre al archivo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Ya existe un archivo con el nombre $nombreArchivo");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoBD($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4451","No se ha restaurado el archivo $nombreArchivo debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoBD2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4452","No se ha restaurado el archivo $nombreArchivo debido a un error al actualizar su ruta en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoBD3($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4453","No se ha restaurado el archivo $nombreArchivo debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoServidor($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4454","No se ha restaurado el archivo $nombreArchivo en el servidor debido a que no se han pasado todos los parámetros","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoServidor2($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4455","No se ha restaurado el archivo $nombreArchivo en el servidor ya que el archivo no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoServidor3($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4456","No se ha restaurado el archivo $nombreArchivo el servidor ya que el directorio de destino no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarArchivoServidor4($nombreArchivo,$idUsuario){
        $consulta = $this->insertarRegistro("A4457","No se ha restaurado el archivo $nombreArchivo en el servidor ya que ha ocurrido un fallo en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar archivo $nombreArchivo. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }
}
?>