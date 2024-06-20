<?php 

require_once "notificacion.php";

class NotificacionCarpeta extends Notificacion {

/***************************NOTIFICACIONES CARPETAS********************************/

    /*******************NOIFICACIONES AL CREAR CARPETA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCreacionCarpeta($nombreCarpeta, $idUsuario){
        $consulta = $this->insertarRegistro("C1000","Se ha creado la nueva carpeta con nombre $nombreCarpeta","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Carpeta '$nombreCarpeta' creada correctamente");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorNombreCarpeta($idUsuario){
        $consulta = $this->insertarRegistro("C1050","No se ha creado la carpeta debido a que su nombre no puede llevar /","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener '/'");
        }

        return $estructuraNotificacion;
    } 

    public function notificarErrorCarpetaExistente($idUsuario){
        $consulta = $this->insertarRegistro("C1051","No se ha creado la carpeta debido a que ya existe una carpeta que tiene ese nombre","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Ya existe una carpeta con ese nombre");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorInsertarCarpeta($idUsuario){
        $consulta = $this->insertarRegistro("C1052","No se ha podido crear la carpeta debido a que ha habido un error al momento de hacer la inserción en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al crear carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCrearCarpetaSistema($idUsuario){
        $consulta = $this->insertarRegistro("C1053","No se ha podido crear la carpeta debido a que ha habido un error al momento de crear la carpeta en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error del servidor al crear la carpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorNombreCarpeta2($idUsuario){
        $consulta = $this->insertarRegistro("C1054","No se ha creado la carpeta debido a que su nombre no puede llevar una barra invertida","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener '\\'");
        }

        return $estructuraNotificacion;
    } 

    public function notificarErrorNombreCarpeta3($idUsuario){
        $consulta = $this->insertarRegistro("C1055","No se ha creado la carpeta debido a que su nombre no puede llevar comillas simples","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener comillas simples");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorNombreLargo($idUsuario){
        $consulta = $this->insertarRegistro("C1056","El nombre de la carpeta no puede ser muy largo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Nombre de carpeta muy largo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvMapeoCarpeta($idUsuario){
        $consulta = $this->insertarRegistro("C1100","No se ha podido realizar el mapeo de la carpeta en la base de datos","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Error al hacer mapeo de la carpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    /*******************NOIFICACIONES AL CAMBIAR NOMBRE DE CARPETAS***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCambioNombreCarpeta($nombreAnterior,$nombreNuevo,$idUsuario){
        $consulta = $this->insertarRegistro("C1200","Se ha cambiado el nombre de la carpeta $nombreAnterior a $nombreNuevo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Cambio de nombre $nombreAnterior a $nombreNuevo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCarpetaExistente2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1250","No se ha cambiado el nombre de la carpeta $nombreCarpeta debido a que ya existe una carpeta con ese nombre en la misma ruta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Nombre de carpeta ya existente");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambioCarpetaBD($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1251","No se ha cambiado el nombre de la carpeta $nombreCarpeta debido a que hubo un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error en el cambio de nombre. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambioCarpetaServidor($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1252","No se ha cambiado el nombre de la carpeta $nombreCarpeta ya que la carpeta no existe en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error en el cambio de nombre. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambioCarpetaServidor2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1253","No se ha cambiado el nombre de la carpeta $nombreCarpeta debido a que en la misma ruta ya se tiene una carpeta con ese nombre en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error en el cambio de nombre. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambioCarpetaServidor3($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1254","No se ha podido cambiar el nombre de la carpeta $nombreCarpeta debido a un error al hacer el cambio de nombre en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error en el cambio de nombre. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambioCarpetaNombre($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1255","No se ha podido cambiar el nombre de la carpeta $nombreCarpeta debido a que su nombre no puede llevar /","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El nombre no puede contener '/'");
        }

        return $estructuraNotificacion;
    } 

    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvCambioNombreSubcarpetas($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1300","No se ha podido actualizar el nuevo nombre de la carpeta $nombreCarpeta en sus subcarpetas","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al cambiar nombre de carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarAdvCambioNombreArchivos($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1301","No se ha podido actualizar el nuevo nombre de la carpeta $nombreCarpeta en sus archivos","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al cambiar nombre de carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    /*******************NOIFICACIONES AL MOVER CARPETA A LA PAPELERA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarMoverCarpetaPapelera($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1400","Se ha movido la carpeta $nombreCarpeta a la papelera","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha movido la carpeta $nombreCarpeta a la papelera");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorMoverCarpetaPapeleraBD($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1450","No se ha podido mover la carpeta $nombreCarpeta a la papelera debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover $nombreCarpeta a la papelera. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaPapeleraServidor($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1451","No se ha podido mover la carpeta $nombreCarpeta a la papelera en el servidor debido a que no se han pasado todos los parametros necesarios","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover $nombreCarpeta a la papelera. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaPapeleraServidor2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1452","No se ha podido mover la carpeta $nombreCarpeta a la papelera en el servidor, debido a que la carpeta no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover $nombreCarpeta a la papelera. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaPapeleraServidor3($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1453","No se ha podido mover la carpeta $nombreCarpeta a la papelera en el servidor ya que hubo un error al encontrar la papelera","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover $nombreCarpeta a la papelera. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaPapeleraServidor4($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1454","No se ha podido mover la carpeta $nombreCarpeta a la papelera en el servidor debido a un fallo del sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover $nombreCarpeta a la papelera. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }
    /*****************************************************/

    
    /*******************NOIFICACIONES AL MOVER CARPETA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarMoverCarpeta($nombreOrigen,$nombreDestino,$idUsuario){
        $consulta = $this->insertarRegistro("C1600","Se ha movido la carpeta $nombreOrigen y todo su contenido a la carpeta elegida","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Carpeta $nombreOrigen movida a $nombreDestino");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/
    
    public function notificarErrorMoverCarpeta($nombreOrigen,$nombreDestino,$idUsuario){
        $consulta = $this->insertarRegistro("C1650","No se ha podido mover la carpeta $nombreOrigen dentro de la carpeta $nombreDestino","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover carpeta $nombreOrigen");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaSiMisma($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1651","No se ha podido mover la carpeta $nombreOrigen dentro de si misma","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No se puede mover la carpeta dentro de si misma");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaEnSubcarpeta($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1652","No se ha podido mover la carpeta $nombreOrigen dentro de una de sus subcarpetas","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No se puede mover carpeta en una subcarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaMismoNombre($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1653","No se ha podido mover la carpeta $nombreOrigen ya que existe una carpeta con su mismo nombre dentro de la carpeta elegida","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Ya existe una carpeta con el mismo nombre");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaBD($nombreOrigen,$nombreDestino,$idUsuario){
        $consulta = $this->insertarRegistro("C1654","No se ha podido mover la carpeta $nombreOrigen debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaServidor($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1655","No se ha podido mover la carpeta $nombreOrigen debido a un error en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaServidor2($nombreOrigen,$nombreDestino,$idUsuario){
        $consulta = $this->insertarRegistro("C1656","No se ha podido mover la carpeta $nombreOrigen ya que existe una carpeta con su mismo nombre dentro de la carpeta elegida en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaServidor3($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1657","No se ha podido mover la carpeta $nombreOrigen debido a que ha habido un error al mover la carpeta en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorMoverCarpetaExistente($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1658","No se ha movido el archivo $nombreOrigen debido a que ya se encuentra en la carpeta elegida","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al mover carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvMoverCarpetaSubcarpetas($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1700","No se ha podido actualizar la nueva ruta en las subcarpetas de la carpeta $nombreOrigen","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al mover subcarpetas");
        }

        return $estructuraNotificacion;
    }

    public function notificarAdvMoverCarpetaArchivos($nombreOrigen,$idUsuario){
        $consulta = $this->insertarRegistro("C1701","No se ha podido actualizar la nueva ruta en los archivos de la carpeta $nombreOrigen","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al mover archivos");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    
    /*******************NOIFICACIONES AL COMPARTIR CARPETA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCompartirCarpetaReceptor($nombreCarpeta,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1800","Se ha compartido la carpeta $nombreCarpeta y su contenido al usuario $nombreReceptor","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha compartido la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarCompartirCarpetaEmisor($nombreCarpeta,$nombreEmisor,$idUsuario){
        $consulta = $this->insertarRegistro("C1801","El usuario $nombreEmisor te ha compartido la carpeta $nombreCarpeta y su contenido","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se te ha compartido la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarQuitarAccesoCarpeta($nombreCarpeta,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1802","Al usuario $nombreReceptor se le ha quitado acceso a la carpeta $nombreCarpeta y su contenido","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha dejado de compartir la carpeta $nombreCarpeta al usuario $nombreReceptor");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCompartirCarpetaNoExistente($nombreCarpeta,$nombrePropietario, $idUsuario){
        $consulta = $this->insertarRegistro("C1850","No se ha compartido la carpeta $nombreCarpeta debido a que el usuario $nombrePropietario no existe en el sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al compartir carpeta. Usuario no existente.");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirCarpetaNoExistente2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1851","No se ha compartido la carpeta $nombreCarpeta debido a que no eres propietario de esta carpeta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al compartir carpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirCarpetaEmailInvalido($nombreCarpeta,$emailReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1852","No se ha compartido la carpeta $nombreCarpeta debido a que el email $emailReceptor no pertenece a ningun usuario","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El email $emailReceptor es incorrecto");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirCarpetaMismoReceptor($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1853","No se ha compartido la carpeta $nombreCarpeta ya que no te puedes compartir la carpeta contigo mismo","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No te puedes compartir tu propia carpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCompartirCarpetaBD($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C1854","No se ha compartido la carpeta $nombreCarpeta debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al compartir carpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorQuitarAcceso($nombreCarpeta,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1855","No se ha podido quitar acceso de la carpeta $nombreCarpeta al usuario $nombreReceptor ya que no tiene compartida la carpeta","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","El usuario $nombreReceptor no tiene compartida la carpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvCompartirCarpetaExistente($nombreCarpeta,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1900","No se ha compartido la carpeta $nombreCarpeta al usuario $nombreReceptor ya que tiene compartida la carpeta","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","El usuario $nombreReceptor ya tiene compartida la carpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarAdvQuitarAccesoSubcarpetas($nombreCarpeta,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1901","No se ha podido quitar acceso al usuario $nombreReceptor de las subcarpetas de la carpeta $nombreCarpeta","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al quitar acceso a las subcarpetas");
        }

        return $estructuraNotificacion;
    }

    public function notificarAdvQuitarAccesoArchivos($nombreCarpeta,$nombreReceptor,$idUsuario){
        $consulta = $this->insertarRegistro("C1902","No se ha podido quitar acceso al usuario $nombreReceptor de los archivos de la carpeta $nombreCarpeta","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al quitar acceso a los archivos");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/

    
    /*******************NOIFICACIONES AL DESCARGAR CARPETA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarDescargarCarpeta($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2000","Se ha descargado el zip de la carpeta $nombreCarpeta","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha descargado la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorObtenerCarpetaDescargar($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2050","No se ha podido obtener la carpeta $nombreCarpeta para descargar","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al descargar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorComprimirCarpeta($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2051","No se ha podido comprimir la carpeta $nombreCarpeta para poder descargar","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al descargar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*******************NOIFICACIONES AL CAMBIAR NOMBRE CARPETA PAPELERA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarCambioNombreCarpetaPapelera($nombreAnterior,$nombreNuevo,$idUsuario){
        $consulta = $this->insertarRegistro("C2600","Se ha cambiado el nombre de la carpeta $nombreAnterior a $nombreNuevo","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Cambio de nombre de carpeta $nombreAnterior a $nombreNuevo");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorCambiarNombreCarpetaPapeleraBD($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2650","Hubo un error al cambiar el nombre de la carpeta $nombreCarpeta en la  base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de carpeta $nombreCarpeta. Inténtalo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreCarpetaPapeleraServidor($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2651","No se ha cambiado el nombre de la carpeta $nombreCarpeta en el servidor debido a que no se han pasado todos los parámetros necesarios","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreCarpetaPapeleraServidor2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2652","No se ha cambiado el nombre de la carpeta $nombreCarpeta en el servidor debido a que ya existe una carpeta con el mismo nombre","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreCarpetaPapeleraServidor3($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2653","No se ha cambiado el nombre de la carpeta $nombreCarpeta en el servidor debido a que la carpeta no existe en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorCambiarNombreCarpetaPapeleraServidor4($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2654","No se ha cambiado el nombre de la carpeta $nombreCarpeta debido a un error del sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al cambiar nombre de carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvCambioNombreSubcarpetasPapelera($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2700","No se ha podido actualizar el nombre de la carpeta $nombreCarpeta en sus subcarpetas","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al actualizar subcarpetas de la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarAdvCambioNombreArchivosPapelera($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2701","No se ha podido actualizar el nombre de la carpeta $nombreCarpeta en sus archivos","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al actualizar archivos de la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*******************NOIFICACIONES AL ELIMINAR CARPETA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarEliminarCarpeta($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2400","Se ha eliminado la carpeta $nombreCarpeta y todo su contenido","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha eliminado la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*****************NOTIFICACION - ERROR****************/

    public function notificarErrorEliminarCarpetaBD($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2450","No se ha eliminado la carpeta $nombreCarpeta debido a que hubo un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCarpetaServidor($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2451","No se ha eliminado la carpeta $nombreCarpeta en el servidor debido a que no se han pasado todos los parámetros necesarios","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCarpetaServidor2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2452","No se ha eliminado la carpeta $nombreCarpeta en el servidor ya que la carpeta no existe","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorEliminarCarpetaServidor3($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2453","No se ha eliminado la carpeta $nombreCarpeta en el servidor ya que ha habido un fallo del sistema","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al eliminar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    /*****************************************************/


    /*******************NOIFICACIONES AL RESTAURAR CARPETA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarRestaurarCarpeta($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2200","Se ha restaurado la carpeta $nombreCarpeta","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha restaurado la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }
    
    /*****************NOTIFICACION - ERROR****************/
    
    public function notificarErrorRestaurarCarpetaExistente($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2250","No se ha restaurado la carpeta $nombreCarpeta ya que existe existe una carpeta con ese nombre. Se debe cambiar el nombre de la carpeta para poder restaurar","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Carpeta $nombreCarpeta ya existente en Inicio. Cambie el nombre de carpeta.");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarCarpetaBD($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2251","No se ha restaurado la carpeta $nombreCarpeta debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarCarpetaBD2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2252","No  se ha restaurado la carpeta $nombreCarpeta debido a un error al actualizar su ruta en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }
    
    public function notificarErrorRestaurarCarpetaBD3($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2253","No se ha restaurado la carpeta $nombreCarpeta debido a un error al actualizar la ruta de sus subcarpetas","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarCarpetaBD4($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2254","No se ha restaurado la carpeta $nombreCarpeta debido a un error al actualizar la ruta de sus archivos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarCarpetaServidor($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2255","No se ha restaurado la carpeta $nombreCarpeta, debido a que la carpeta no existe en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarCarpetaServidor2($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2256","No se ha restaurado la carpeta $nombreCarpeta, debido a que su carpeta padre no existe en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorRestaurarCarpetaServidor3($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2257","No se ha restaurado la carpeta $nombreCarpeta, debido a que ha ocurrido un error al restaurar la carpeta en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","Error al restaurar carpeta $nombreCarpeta. Intentalo mas tarde");
        }

        return $estructuraNotificacion;
    }


    /*****************NOTIFICACION - ADVERTENCIA****************/

    public function notificarAdvRestaurarSubcarpetas($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2300","No se ha actualizado la ruta de las subcarpetas de la carpeta $nombreCarpeta debido a un error en la base de datos","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al restaurar subcarpetas de la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    public function notificarAdvRestaurarArchivos($nombreCarpeta,$idUsuario){
        $consulta = $this->insertarRegistro("C2301","No se ha actualizado la ruta de los archivos de la carpeta $nombreCarpeta debido a un error en la base de datos","ADVERTENCIA",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ADVERTENCIA","Fallo al restaurar archivos de la carpeta $nombreCarpeta");
        }

        return $estructuraNotificacion;
    }

    /*******************NOIFICACIONES AL VACIAR PAPELERA***********************/

    /*****************NOTIFICACION - EXITO****************/

    public function notificarVaciarPapelera($idUsuario){
        $consulta = $this->insertarRegistro("C2800","Se ha vaciado la papelera","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se ha vaciado la papelera");
        }

        return $estructuraNotificacion;
    }
    
    /*****************NOTIFICACION - ERROR****************/
    
    public function notificarErrorVaciarPapeleraBD($idUsuario){
        $consulta = $this->insertarRegistro("C2850","No se ha vaciado la papelera debido a un error en la base de datos","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No se ha vaciado la papelera. Inténtelo más tarde");
        }

        return $estructuraNotificacion;
    }

    public function notificarErrorVaciarPapeleraServidor($idUsuario){
        $consulta = $this->insertarRegistro("C2851","No se ha vaciado la papelera debido a un error en el servidor","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No se ha vaciado la papelera. Inténtelo más tarde");
        }

        return $estructuraNotificacion;
    }


    /**********************************************************/

    /******************* DESCARGAR CARPETA CONTENIDO *******************/

    /******************* NOTIFICACION - EXITO *******************/

    public function notificarComprimirCarpetaContenido($idUsuario){
        $consulta = $this->insertarRegistro("C2900","Se han descargado los elementos","EXITO",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("EXITO","Se han descargado los elementos");
        }

        return $estructuraNotificacion;
    }

    /******************* NOTIFICACION - ERROR *******************/

    public function notificarErrorComprimirCarpetaContenido($idUsuario){
        $consulta = $this->insertarRegistro("C2950","No se han descargado los elementos debido a un fallo al momento de comprimir el zip","ERROR",$idUsuario);

        if($consulta){
            $estructuraNotificacion = $this->mostrarNotificacion("ERROR","No se han descargado los elementos. Inténtelo más tarde");
        }

        return $estructuraNotificacion;
    }
}
?>