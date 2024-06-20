<?php 
// Incluir conexión a la base de datos
require_once "conexion.php";
// Definición de la clase Carpeta
class Carpeta {
    // Método privado para ejecutar consultas SQL
    private function obtenerDatos($sql){
        // Se instancia un objeto de la clase Conexion para establecer la conexión a la base de datos
        $conexion = new Conexion();
        $con = $conexion->con;

        $resultado = [];
        
        // Se ejecuta la consulta SQL
        $consulta = $con->query($sql);
        $i = 0;

        if (!$consulta) {
            // Si hay un error en la consulta, lanzamos una excepción
            throw new Exception("Error en la consulta: " . $con->error);
        }

        while ($fila = $consulta->fetch_assoc()) {
            $resultado[$i] = $fila;
            $i++;
        }

        return $resultado;
    }

    // Método privado para ejecutar consultas y retornar un booleano
    private function ejecutarConsulta($sql) {
         // Se instancia un objeto de la clase Conexion para establecer la conexión a la base de datos
        $conexion = new Conexion();
        $con = $conexion->con;

        $consulta = $con->query($sql);
        
        // Si hay un error en la consulta, retornamos false, de lo contrario, retornamos true
        if (!$consulta) {
            throw new Exception("Error en la consulta: " . $con->error);
        }

        return true;
    }

    // Método para obtener el id posterior al ultimo id de la tabla carpetas
    public function ultimoIdCarpeta(){
        $consulta = $this->obtenerDatos("SELECT id_carpeta FROM carpetas ORDER BY id_carpeta DESC LIMIT 1;");
    
        if(count($consulta) === 0){
            $id_posterior = 1;
        } else {
            $id_posterior = $consulta["0"]["id_carpeta"] + 1;
        }
        return $id_posterior;
    }

    // Método para obtener id de una carpeta según una ruta
    public function obtenerIdCarpeta($ruta_carpeta){
        $consulta = $this->obtenerDatos("SELECT id_carpeta FROM carpetas WHERE ruta_carpeta = '$ruta_carpeta';");
    
        if(count($consulta) !== 0){
            $id_carpeta = $consulta["0"]["id_carpeta"];
        } else {
            $id_carpeta = false;
        }

        return $id_carpeta;
    }

    // Método para validar una ruta de carpeta
    public function validarRutaCarpeta($ruta){
        $consulta = $this->obtenerDatos("SELECT ruta_carpeta FROM carpetas WHERE ruta_carpeta = '$ruta' AND estado_papelera='no'");

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método obtener la nueva carpeta de un usuario en específico y llamar al método para crear su estructura HTML
    public function obtenerYCrearCarpeta($id_carpeta, $id_usuario){
        $consulta = $this->obtenerDatos(
            "SELECT m.id, c.nombre_carpeta, u.nombre, c.fecha_creacion 
            FROM carpetas c 
            JOIN usuarios u ON c.id_usuario = u.id_usuario 
            JOIN id_mapping m ON c.id_carpeta = m.id_carpeta 
            WHERE c.id_usuario = $id_usuario 
                AND c.id_carpeta = $id_carpeta 
                AND c.estado_papelera = 'no';"
        );

        return $this->crearCarpeta($consulta[0]["id"],$consulta[0]["nombre_carpeta"],$consulta[0]["nombre"],$consulta[0]["fecha_creacion"]);
    }

    // Método para obtener las carpetas de un usuario según la ruta esperada y no esperada
    public function obtenerCarpetas($usuario, $rutaEsperada, $rutaNoEsperada){
        return $this->obtenerDatos(
            "SELECT m.id, c.nombre_carpeta, u.nombre, c.fecha_creacion 
            FROM carpetas c 
            JOIN usuarios u ON c.id_usuario = u.id_usuario 
            JOIN id_mapping m ON c.id_carpeta = m.id_carpeta 
            WHERE u.nombre = '$usuario' 
                AND c.ruta_carpeta LIKE '$rutaEsperada' 
                AND c.ruta_carpeta NOT LIKE '$rutaNoEsperada' 
                AND c.estado_papelera = 'no';"
        );
    }

    // Método para obtener las carpetas de la papelera de un usuario según la ruta esperada y no esperada
    public function obtenerCarpetasPapelera($usuario, $rutaEsperada, $rutaNoEsperada){
        return $this->obtenerDatos(
            "SELECT m.id, c.nombre_carpeta, c.id_carpeta_papelera, u.nombre, c.fecha_creacion 
            FROM carpetas c 
            JOIN usuarios u ON c.id_usuario = u.id_usuario 
            JOIN id_mapping m ON c.id_carpeta = m.id_carpeta 
            WHERE u.nombre = '$usuario' 
                AND c.ruta_carpeta_papelera LIKE '$rutaEsperada' 
                AND c.ruta_carpeta_papelera NOT LIKE '$rutaNoEsperada' 
                AND c.estado_papelera = 'si';"
        );
    }

    // Método para insertar una nueva carpeta
    public function agregarCarpeta($id_posterior,$ruta_carpeta,$nombre_carpeta,$fecha,$id_usuario,$id_carpeta_padre){
        return $this->ejecutarConsulta(
            "INSERT INTO carpetas 
            VALUES ($id_posterior,'$ruta_carpeta','$nombre_carpeta','$fecha',$id_usuario,$id_carpeta_padre,null,null,null,'no');"
        );
    }

    // Método para mapear una carpeta en una tabla de referencia
    public function agregarMapeo($id_unico,$id_carpeta){
        return $this->ejecutarConsulta(
            "INSERT INTO id_mapping 
            VALUES ('$id_unico',$id_carpeta, null, 'carpeta');"
        );
    }

    // Método para poder obtener el id de mapeo de una carpeta en concreto
    public function obtenerIdMap($id_carpeta){
        return $this->obtenerDatos(
            "SELECT id 
            FROM id_mapping 
            WHERE id_carpeta = $id_carpeta"
        );
    }

    // Método para obtener el id de mapeo de una carpeta en específico según su ruta y verificar que no este en papelera
    public function obtenerIdMapRuta($ruta){
        return $this->obtenerDatos(
            "SELECT id 
            FROM id_mapping 
            WHERE id_carpeta = (SELECT id_carpeta 
                                FROM carpetas 
                                WHERE ruta_carpeta = '$ruta' 
                                    AND estado_papelera = 'no')"
        );
    }

    // Método para obtener id de carpeta según el id de mapeo
    public function obtenerIdDesdeMap($idRuta){
        return $this->obtenerDatos(
            "SELECT id_carpeta 
            FROM id_mapping 
            WHERE id = '$idRuta' 
                AND tipo_elemento = 'carpeta'"
        );
    }

    // Método para obtener id de las carpetas iniciales
    public function obtenerIdCarpetasIniciales($nombre){
        return $this->obtenerDatos(
            "SELECT id_carpeta 
            FROM carpetas 
            WHERE id_usuario = (SELECT id_usuario 
                                FROM usuarios 
                                WHERE nombre = '$nombre') 
                                LIMIT 3"
        );    
    }

    // Método para obtener ruta de una carpeta en base a su id de mapeo
    public function obtenerRutaCarpeta($id_ruta){
        return $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE id_carpeta = (SELECT id_carpeta 
                                FROM id_mapping 
                                WHERE id = '$id_ruta' 
                                    AND tipo_elemento = 'carpeta');"
        );
    }

    // Método para obtener ruta de una carpeta en base a su id y que este en la papelera
    public function obtenerRutaCarpetaIdPapelera($idCarpeta){
        return $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE id_carpeta = $idCarpeta 
                AND estado_papelera = 'si'"
        );
    }

    // Método para obtener ruta de una carpeta y su ruta de papelera
    public function obtenerRutaCarpetaId($idCarpeta){
        return $this->obtenerDatos(
            "SELECT ruta_carpeta, ruta_carpeta_papelera 
            FROM carpetas 
            WHERE id_carpeta = $idCarpeta 
                AND estado_papelera = 'si'"
        );
    }

    // Método para obtener ruta de un archivo y su ruta de papelera
    public function obtenerRutaArchivoId($idArchivo){
        return $this->obtenerDatos(
            "SELECT ruta_archivo, ruta_archivo_papelera 
            FROM archivos 
            WHERE id_archivo = $idArchivo 
                AND estado_papelera = 'si'"
        );
    }

    // Método para obtener una fecha con el formato (d/m/Y)
    public function obtenerFormatoFecha($fecha){
        $timestamp = strtotime($fecha);

        $formatoFecha = date("d/m/Y", $timestamp);
        
        return $formatoFecha;
    }

    // Método para crear la estructura HTML de una carpeta con cierto parámetros que ayudan a su posterior referencia
    public function crearCarpeta($id_carpeta,$nombre_carpeta,$nombre_usuario,$fecha){
        $estructuraCarpeta = '<div class="carpeta" data-id="' . $id_carpeta . '" data-tipo="carpeta" data-usuario="' . $nombre_usuario . '" >
                      <img src="../../assets/imgs/svg/principal/folder.svg" alt="Folder">
                      <div class="con_carpeta">
                        <p data-id="' . $id_carpeta . '" data-tipo="carpeta" data-usuario="' . $nombre_usuario . '" class="carpeta_elm">' . $nombre_carpeta . '</p>
                        <p>'.$this->obtenerFormatoFecha($fecha).'</p>
                      </div>
                      <i class="icon-dots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M112,60a16,16,0,1,1,16,16A16,16,0,0,1,112,60Zm16,52a16,16,0,1,0,16,16A16,16,0,0,0,128,112Zm0,68a16,16,0,1,0,16,16A16,16,0,0,0,128,180Z"></path></svg>
                      </i>
                    </div>';

        return $estructuraCarpeta; 
    }

    // Método para crear la estructura HTML de una carpeta en papelera con cierto parámetros que ayudan a su posterior referencia
    public function crearCarpetaPapelera($id_carpeta,$nombre_carpeta,$nombre_usuario,$id_carpeta_papelera,$fecha){
        $estructuraCarpeta = '<div class="carpeta" data-id="' . $id_carpeta . '" data-tipo="carpeta" data-id_papelera="'.$id_carpeta_papelera.'" data-usuario="' . $nombre_usuario . '" >
                      <img src="../../assets/imgs/svg/principal/folder.svg" alt="Folder">
                      <div class="con_carpeta">
                        <p data-id="' . $id_carpeta . '" data-tipo="carpeta" data-usuario="' . $nombre_usuario . '" class="carpeta_elm">' . $nombre_carpeta . '</p>
                        <p>'.$this->obtenerFormatoFecha($fecha).'</p>
                      </div>
                      <i class="icon-dots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M112,60a16,16,0,1,1,16,16A16,16,0,0,1,112,60Zm16,52a16,16,0,1,0,16,16A16,16,0,0,0,128,112Zm0,68a16,16,0,1,0,16,16A16,16,0,0,0,128,180Z"></path></svg>
                      </i>
                    </div>';

        return $estructuraCarpeta; 
    }

    // Método para crear la estructura HTML de una carpeta compartida con cierto parámetros que ayudan a su posterior referencia
    public function crearCarpetaCompartida($id_carpeta,$nombre_carpeta,$nombre_usuario,$fecha){
        $estructuraCarpeta = '<div class="carpeta" data-id="' . $id_carpeta . '" data-tipo="carpeta" data-usuario="' . $nombre_usuario . '" >
                      <img src="../../assets/imgs/svg/principal/folder.svg" alt="Folder">
                      <div class="con_carpeta">
                        <p data-id="' . $id_carpeta . '" data-tipo="carpeta" data-usuario="' . $nombre_usuario . '" class="carpeta_elm">' . $nombre_carpeta . '</p>
                        <p>'.$this->obtenerFormatoFecha($fecha).'</p>
                      </div>
                      <i class="icon-dots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M112,60a16,16,0,1,1,16,16A16,16,0,0,1,112,60Zm16,52a16,16,0,1,0,16,16A16,16,0,0,0,128,112Zm0,68a16,16,0,1,0,16,16A16,16,0,0,0,128,180Z"></path></svg>
                      </i>
                    </div>';

        return $estructuraCarpeta; 
    }

    // Método para crear la estructura HTML de una carpeta la cual aparece en el modal Mover
    public function crearCarpetaMover($idCarpeta,$nombre_carpeta,$nombre_usuario){
        $estructuraCarpeta = '<div class="modal-carpeta-mover" data-id="'.$idCarpeta.'" data-tipo="carpeta" data-usuario="' . $nombre_usuario . '">
        <div class="modal-carpeta-mover-izq"  >
          <i class="modal-mover-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
              <path d="M216,64H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,80V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V80A16,16,0,0,0,216,64ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Z"></path>
            </svg>
          </i>
          <p class="modal-nombre-carpeta">'.$nombre_carpeta.'</p>
        </div>
        <i class="modal-mover-icon  modal-mover-icon-entrar">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
            <path d="M221.66,133.66l-80,80A8,8,0,0,1,128,208V147.31L61.66,213.66A8,8,0,0,1,48,208V48a8,8,0,0,1,13.66-5.66L128,108.69V48a8,8,0,0,1,13.66-5.66l80,80A8,8,0,0,1,221.66,133.66Z"></path>
          </svg>
        </i>
      </div>';

      return $estructuraCarpeta;
    }

    // FUNCIONES PARA MOVER A LA PAPELERA A LA CARPETA

    // Método para obtener el id de papelera de una carpeta segun su ruta
    public function obtenerIdCarpetaPapelera($ruta){
        $consulta = $this->obtenerDatos(
            "SELECT id_carpeta_papelera 
            FROM carpetas 
            WHERE ruta_carpeta = '$ruta'"
        );

        return $consulta[0]["id_carpeta_papelera"];
    }

    // Método para obtener el id de papelera de una carpeta segun su id
    public function obtenerIdCarpetaPapelera2($idCarpeta){
        $consulta = $this->obtenerDatos(
            "SELECT id_carpeta_papelera 
            FROM carpetas 
            WHERE id_carpeta = $idCarpeta"
        );

        return $consulta[0]["id_carpeta_papelera"];
    }

    // Método para obtener el total de carpetas que tiene una carpeta contando con la propia carpeta
    public function cantidadCarpetas($ruta){
        return $this->obtenerDatos(
            "SELECT count(DISTINCT ruta_carpeta) 
            FROM carpetas 
            WHERE (ruta_carpeta LIKE '$ruta/%' or ruta_carpeta = '$ruta') 
                AND estado_papelera='no';"
        );
    }

    // Método para obtener la rutas de carpetas que tiene una carpeta y que no esten en papelera
    public function obtenerRutas($ruta){
        return $this->obtenerDatos(
            "SELECT DISTINCT ruta_carpeta 
            FROM carpetas 
            WHERE (ruta_carpeta LIKE '$ruta/%' or ruta_carpeta = '$ruta') 
                AND estado_papelera='no';"
        );
    }

    // Método para obtener el id de la carpeta padre de una carpeta
    public function obtenerIdCarpetaPadre($idCarpeta){
        return $this->obtenerDatos(
            "SELECT id_carpetaPadre 
            FROM carpetas 
            WHERE id_carpeta = $idCarpeta"
        );
    }

    // Método para verificar si una carpeta es padre de un archivo según su ruta
    public function verificarCarpetaPadre($idCarpeta){
        return $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM carpetas 
            WHERE id_carpeta = $idCarpeta 
                AND estado_papelera = 'si'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para mover una carpeta a la papelera
    public function moverCarpetaPapelera($ruta_carpeta,$ruta_papelera,$usuario,$id_carpeta_papelera){
        $fecha=date("Y-m-d H:i:s");

        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET estado_papelera = 'si', id_carpeta_papelera = '$id_carpeta_papelera', ruta_carpeta_papelera = '$ruta_papelera', fecha_papelera = '$fecha' 
            WHERE ruta_carpeta = '$ruta_carpeta' 
                AND id_usuario = $usuario 
                AND estado_papelera='no'"
        );
    }

    // Método para obtener rutas de archivos de una carpeta que no esten en papelera
    public function obtenerRutasArchivos($ruta_carpeta,$usuario){
        return $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE ruta_archivo LIKE '$ruta_carpeta/%' 
                AND estado_papelera = 'no' 
                AND id_usuario = $usuario"
        );
    }

    // Método para mover un achivo a la papelera
    public function moverArchivoPapelera($ruta_archivo,$ruta_papelera,$usuario,$id_archivo_papelera){
        $fecha=date("Y-m-d H:i:s");

        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET estado_papelera = 'si', id_archivo_papelera = '$id_archivo_papelera', ruta_archivo_papelera = '$ruta_papelera', fecha_papelera = '$fecha' 
            WHERE ruta_archivo = '$ruta_archivo' 
                AND id_usuario = $usuario 
                AND estado_papelera='no'"
        );
    }

    // FUNCIONES PARA ELIMINAR CARPETA DE LA PAPELERA
    
    // Método para obtener ruta de una carpeta y su ruta de papelera según su id de papelera
    public function obtenerRutaCarpetaPapelera($idPapelera){
        return $this->obtenerDatos(
            "SELECT ruta_carpeta, ruta_carpeta_papelera 
            FROM carpetas 
            WHERE id_carpeta_papelera = '$idPapelera' 
                AND estado_papelera = 'si'"
        );
    }

    // Método para validar si una carpeta cumple con la ruta de papelera que se pasa como parámetro
    public function validarRutaCarpetaPapelera($ruta_papelera){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta_papelera = '$ruta_papelera' 
                AND estado_papelera='si'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para actualizar rutas de archivos de una carpeta que se va a eliminar
    public function cambiarArchivosDeCarpetaEliminada($rutaCarpeta, $rutaRaiz,$rutaCarpetaPapelera, $idCarpeta){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = REPLACE(ruta_archivo, '$rutaCarpeta', '$rutaRaiz'), id_carpeta = null 
            WHERE ruta_archivo LIKE '$rutaCarpeta/%' 
                AND ruta_archivo_papelera NOT LIKE '$rutaCarpetaPapelera/%' 
                AND estado_papelera = 'si' 
                AND id_carpeta = $idCarpeta"
        );
    }

    // Método para actualizar rutas de carpetas de una carpeta que se va a eliminar
    public function cambiarCarpetaDeCarpetaEliminada($rutaCarpeta, $rutaRaiz,$rutaCarpetaPapelera, $idCarpeta){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = REPLACE(ruta_carpeta, '$rutaCarpeta', '$rutaRaiz'), id_carpetaPadre = null 
            WHERE ruta_carpeta LIKE '$rutaCarpeta/%' 
                AND ruta_carpeta_papelera NOT LIKE '$rutaCarpetaPapelera/%' 
                AND estado_papelera = 'si' 
                AND id_carpetaPadre = $idCarpeta"
        );
    }

    // Método para obtener ids de carpetas de una carpeta que estan en la papelera
    public function obtenerIdCarpetasPapelera($rutaCarpetaPapelera){
        return $this->obtenerDatos(
            "SELECT id_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta_papelera = '$rutaCarpetaPapelera' 
                OR ruta_carpeta_papelera LIKE '$rutaCarpetaPapelera/%' 
                AND estado_papelera = 'si'"
        );
    }

    // Método para actualizar ruta y carpeta padre de una carpeta en específico
    public function actualizarRutaCarpeta($idCarpeta, $ruta_carpeta,$idCarpetaPadre){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = '$ruta_carpeta', id_carpetaPadre = $idCarpetaPadre 
            WHERE id_carpeta = $idCarpeta"
        );
    }

    // Método para llamar a un procedimiento almacenado para poder actualizar rutas de carpetas
    public function ex_actualizarRutasCarpetas($idCarpeta,$rutaCarpeta,$rutaNueva,$rutaRaiz){
        return $this->ejecutarConsulta("CALL ActualizarRutasCarpetas($idCarpeta, '$rutaCarpeta', '$rutaNueva', '$rutaRaiz');");
    }

    // Método para llamar a un procedimiento almacenado para poder actualizar rutas de archivos
    public function ex_actualizarRutasArchivos($idCarpeta,$rutaCarpeta,$rutaNueva,$rutaRaiz){
        return $this->ejecutarConsulta("CALL ActualizarRutasArchivos($idCarpeta, '$rutaCarpeta', '$rutaNueva', '$rutaRaiz');");
    }

    // Método para eliminar una carpeta junto a sus carpetas hijas
    public function eliminarCarpetaContenido($ruta_papelera){
        return $this->ejecutarConsulta(
            "DELETE FROM carpetas 
            WHERE (ruta_carpeta_papelera LIKE '$ruta_papelera/%' OR ruta_carpeta_papelera = '$ruta_papelera') 
                AND estado_papelera = 'si'"
        );
    }

    // Método para eliminar los archivos hijos de una carpeta
    public function eliminarCarpetaContenido2($ruta_papelera){
        return $this->ejecutarConsulta(
            "DELETE FROM archivos 
            WHERE ruta_archivo_papelera LIKE '$ruta_papelera/%' 
                AND estado_papelera = 'si'"
        );
    }

    // Método para obtener datos de las carpetas hijas de una carpeta
    public function obtenerCarpetasHijas($idCarpeta, $ruta_carpeta, $ruta_papelera){
        return $this->obtenerDatos(
            "SELECT id_carpeta, ruta_carpeta, ruta_carpeta_papelera 
            FROM carpetas 
            WHERE ruta_carpeta LIKE '$ruta_carpeta/%' 
                AND ruta_carpeta NOT LIKE '$ruta_carpeta/%/%' 
                AND id_carpetaPadre = $idCarpeta 
                AND estado_papelera = 'si' 
                AND ruta_carpeta_papelera NOT LIKE '$ruta_papelera%'"
        );
    }

    // Método para obtener datos de los archivos hijos de una carpeta
    public function obtenerArchivosHijos($idCarpeta, $ruta_carpeta, $ruta_papelera){
        return $this->obtenerDatos(
            "SELECT id_archivo, ruta_archivo, ruta_archivo_papelera 
            FROM archivos 
            WHERE ruta_archivo LIKE '$ruta_carpeta/%' 
                AND ruta_archivo NOT LIKE '$ruta_carpeta/%/%' 
                AND id_carpeta = $idCarpeta AND estado_papelera = 'si' 
                AND ruta_archivo_papelera NOT LIKE '$ruta_papelera%'"
        );
    }

    // Método para cambiar las rutas de las carpetas hijas de una carpeta en la papelera
    public function cambiarRutaHijas($ruta_antigua,$ruta_nueva,$ruta_papelera){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = REPLACE(ruta_carpeta, '$ruta_antigua', '$ruta_nueva') 
            WHERE ruta_carpeta_papelera LIKE '$ruta_papelera%' 
                AND estado_papelera = 'si'"
        );
    }

    // Método para cambiar las rutas de los archivos hijos de una carpeta en la papelera
    public function cambiarRutaHijasArchivos($ruta_antigua,$ruta_nueva,$ruta_papelera){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = REPLACE(ruta_archivo, '$ruta_antigua', '$ruta_nueva') 
            WHERE ruta_archivo_papelera LIKE '$ruta_papelera%' 
                AND estado_papelera = 'si'"
        );
    }

    //FUNCIONES PARA CAMBIAR DE NOMBRE A UNA CARPETA

    // Método para verificar si ya existe una carpeta en la ruta que se pasa como parámetro
    public function verificarNombreCarpeta($ruta_carpeta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta = '$ruta_carpeta' 
                AND estado_papelera = 'no'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para actualizar el nombre de una carpeta y su ruta
    public function actualizarNombreCarpeta($idCarpeta, $nombre, $nuevaRuta){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET nombre_carpeta = '$nombre', ruta_carpeta = '$nuevaRuta' 
            WHERE id_carpeta = $idCarpeta 
                AND estado_papelera = 'no'"
        );
    }

    // Método para actualizar las rutas de las carpetas hijas de una carpeta
    public function actualizarNombreCarpetaHijas($anteriorRuta, $nuevaRuta){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = REPLACE(ruta_carpeta, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_carpeta LIKE '$anteriorRuta/%'  
                AND estado_papelera = 'no'"
        );
    }

    // Método para actualizar las rutas de los archivos hijos de una carpeta
    public function actualizarNombreArchivosHijos($anteriorRuta, $nuevaRuta){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = REPLACE(ruta_archivo, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_archivo LIKE '$anteriorRuta/%'  
                AND estado_papelera = 'no'"
        );
    }

    //FUNCIONES PARA PODER RESTAURAR CARPETAS DE LA PAPELERA

    // Método para verificar si existe una carpeta con la ruta que se pasa como parámetro
    public function verificarRutaCarpeta($idCarpeta,$ruta_carpeta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta = '$ruta_carpeta' 
                AND estado_papelera = 'no' 
                AND id_carpeta != $idCarpeta"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para actualizar datos de una carpeta que esta en la papelera
    public function restaurarCarpeta($idCarpeta){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET id_carpeta_papelera = null, ruta_carpeta_papelera = null, estado_papelera = 'no', fecha_papelera = null 
            WHERE id_carpeta = $idCarpeta 
                AND estado_papelera = 'si'"
        );
    }

    // Método para actualizar datos de las carpetas hijas de una carpeta que esta en la papelera
    public function restaurarCarpetasHijas($ruta_carpeta, $ruta_carpeta_papelera){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET id_carpeta_papelera = null, ruta_carpeta_papelera = null, estado_papelera = 'no', fecha_papelera = null 
            WHERE ruta_carpeta LIKE '$ruta_carpeta/%' 
                AND ruta_carpeta_papelera LIKE '$ruta_carpeta_papelera/%' 
                AND estado_papelera = 'si'"
        );
    }

    // Método para actualizar datos los archivos hijos de una carpeta que esta en la papelera
    public function restaurarArchivosHijos($ruta_carpeta, $ruta_carpeta_papelera){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET id_archivo_papelera = null, ruta_archivo_papelera = null, estado_papelera = 'no', fecha_papelera = null 
            WHERE ruta_archivo LIKE '$ruta_carpeta/%' 
                AND ruta_archivo_papelera LIKE '$ruta_carpeta_papelera/%' 
                AND estado_papelera = 'si'"
        );
    }

    // Método para obtener la ruta donde se va a restaurar la carpeta
    public function ex_obtenerRutaRestauracion($idCarpeta, $rutaRaiz) {
        $conexion = new Conexion();
        $con = $conexion->con;

        $consulta = $con->query("CALL EncontrarRutaRestauracion($idCarpeta, '$rutaRaiz', @ruta);");

        $resultado = $con->query("SELECT @ruta AS ruta_restauracion");

        $fila = $resultado->fetch_assoc();

        return $fila;
    }

    // Método para actualizar las rutas de las carpetas hijas de una carpeta que esta en la papelera
    public function actualizarRutaCarpetas($anteriorRuta, $nuevaRuta, $rutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = REPLACE(ruta_carpeta, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_carpeta LIKE '$anteriorRuta/%' 
                AND ruta_carpeta_papelera LIKE '$rutaPapelera/%' 
                AND estado_papelera = 'si';"
        );
    }

    // Método para actualizar las rutas de los archivos hijos de una carpeta que esta en la papelera
    public function actualizarRutaArchivos($anteriorRuta, $nuevaRuta, $rutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = REPLACE(ruta_archivo, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_archivo LIKE '$anteriorRuta/%' 
                AND ruta_archivo_papelera LIKE '$rutaPapelera/%' 
                AND estado_papelera = 'si';"
        );
    }

    // FUNCIONES PARA CAMBIAR NOMBRE EN LA PAPELERA

    // Método para actualizar el nombre de una carpeta que esta en la papelera
    public function actualizarNombreCarpetaPapelera($idCarpeta, $nombre, $nuevaRuta, $nuevaRutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET nombre_carpeta = '$nombre', ruta_carpeta = '$nuevaRuta', ruta_carpeta_papelera = '$nuevaRutaPapelera' 
            WHERE id_carpeta = $idCarpeta 
                AND estado_papelera = 'si'"
        );
    }

    // Método para actualizar las rutas de las carpetas hijas de una carpeta que esta en la papelera
    public function actualizarNombreCarpetaHijasPapelera($anteriorRuta, $nuevaRuta, $anteriorRutaPapelera, $nuevaRutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = REPLACE(ruta_carpeta, '$anteriorRuta', '$nuevaRuta'), ruta_carpeta_papelera = REPLACE(ruta_carpeta_papelera, '$anteriorRutaPapelera', '$nuevaRutaPapelera') 
            WHERE ruta_carpeta LIKE '$anteriorRuta/%' 
                AND ruta_carpeta_papelera LIKE '$anteriorRutaPapelera/%' 
                AND estado_papelera = 'si';"
        );
    }

    // Método para actualizar las rutas de las carpetas hijas de una carpeta que esta en la papelera
    public function actualizarNombreCarpetaHijasPapelera2($anteriorRuta, $nuevaRuta, $anteriorRutaPapelera, $nuevaRutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE carpetas SET ruta_carpeta = REPLACE(ruta_carpeta, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_carpeta LIKE '$anteriorRuta/%' 
                AND ruta_carpeta_papelera NOT LIKE '$anteriorRutaPapelera/%' 
                AND estado_papelera = 'si';"
        );
    }

    // Método para actualizar las rutas de los archivos hijos de una carpeta que esta en la papelera
    public function actualizarNombreArchivosHijosPapelera($anteriorRuta, $nuevaRuta, $anteriorRutaPapelera, $nuevaRutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE archivos SET ruta_archivo = REPLACE(ruta_archivo, '$anteriorRuta', '$nuevaRuta'), ruta_archivo_papelera = REPLACE(ruta_archivo_papelera, '$anteriorRutaPapelera', '$nuevaRutaPapelera') 
            WHERE ruta_archivo LIKE '$anteriorRuta/%' 
                AND ruta_archivo_papelera LIKE '$anteriorRutaPapelera/%' 
                AND estado_papelera = 'si';"
        );
    }

    // Método para actualizar las rutas de los archivos hijos de una carpeta que esta en la papelera
    public function actualizarNombreArchivosHijosPapelera2($anteriorRuta, $nuevaRuta, $anteriorRutaPapelera, $nuevaRutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = REPLACE(ruta_archivo, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_archivo LIKE '$anteriorRuta/%'
            AND ruta_archivo_papelera NOT LIKE '$anteriorRutaPapelera/%' 
            AND estado_papelera = 'si';"
        );
    }

    // FUNCIONES PARA MOVER UNA CARPETA

    // Método para verificar si una carpeta existe en la ruta que se pasa como parámetro
    public function verificarCarpetaAMover($ruta_carpeta, $id_carpeta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta = '$ruta_carpeta' 
                AND estado_papelera = 'no' 
                AND id_carpeta = $id_carpeta"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para verificar si una carpeta se quiere mover en una de sus carpetas hijas
    public function verificarCarpetaHijaAMover($ruta_carpeta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_carpeta FROM carpetas 
            WHERE ruta_carpeta = '$ruta_carpeta/%' 
                AND estado_papelera = 'no'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para verificar si en la carpeta donde se va a mover una carpeta ya existe una carpeta con la misma ruta
    public function verificarNombreCarpetaAMover($ruta_carpeta, $nombre_carpeta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta = '$ruta_carpeta/$nombre_carpeta' 
                AND estado_papelera = 'no'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para actualizar la carpeta y de esta manera moverla a su nueva carpeta
    public function actualizarRutaCarpetaMovida($idCarpeta, $nuevaRuta, $idCarpetaPadre){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = '$nuevaRuta', id_carpetaPadre = $idCarpetaPadre 
            WHERE id_carpeta = $idCarpeta"
        );
    }

    // Método para actualizar las rutas de las carpetas hijas de una carpeta que se ha movido
    public function actualizarRutaCarpetaHijas($anteriorRuta, $nuevaRuta){
        return $this->ejecutarConsulta(
            "UPDATE carpetas 
            SET ruta_carpeta = REPLACE(ruta_carpeta, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_carpeta LIKE '$anteriorRuta/%' 
                AND estado_papelera = 'no';"
        );
    }

    // Método para actualizar las rutas de los archivos hijos de una carpeta que se ha movido
    public function actualizarRutaArchivosHijos($anteriorRuta, $nuevaRuta){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = REPLACE(ruta_archivo, '$anteriorRuta', '$nuevaRuta') 
            WHERE ruta_archivo LIKE '$anteriorRuta/%' 
                AND estado_papelera = 'no';"
        );
    }

    // FUNCIONES PARA COMPARTIR CARPETAS

    // Método para crear la estructura HTML de un lector (persona a la que se le comparte un elemento)
    public function agregarLector($email,$nombre, $apellido, $img, $idCarpeta){
        $estructuraLector = '<div class="compartido-lector"  data-email="'.$email.'">
            <div class="compartido-lector-izq" data-email="'.$email.'" data-id="'.$idCarpeta.'" data-usuario="'.$nombre.'">
                <img src="'.$img.'" alt="" class="icon-usuario compartido-img-lector">
                <div class="compartido-lector-izq-datos">
                    <h3 class="compartido-nombre-lector">'.$nombre.' '.$apellido.'</h3>
                    <p class="compartido-email-lector">'.$email.'</p>
                </div> 
            </div>
            <div class="compartido-lector-der">
                <button class="btn_quitar_acceso" data-usuario="'.$nombre.'" data-id="'.$idCarpeta.'">Quitar acceso</button>
            </div>
        </div>';

        return $estructuraLector;
    }

    // Método para obtener todos los ids de los usuarios que tienen compartido una carpeta en particular
    public function obtenerIdLectores($idCarpeta){
        return $this->obtenerDatos(
            "SELECT id_receptor 
            FROM compartidos 
            WHERE id_carpeta = $idCarpeta"
        );
    }

    // Método para obtener los datos de los usuarios que tienen compartido un elemento
    public function obtenerDatosLectores($idLectores){

        $idLectoresLista = array_column($idLectores, 'id_receptor');

        if (empty($idLectoresLista)) {
            return [];
        }
        
        $idLectoresStr = implode(',', $idLectoresLista);
        return $this->obtenerDatos(
            "SELECT nombre, apellido, correo, ruta_imagen 
            FROM usuarios 
            WHERE id_usuario IN ($idLectoresStr)"
        );
    }

    // Método para obtener los datos de un usuario que tiene compartido un elemento
    public function obtenerDatosLector($idLector){
        return $this->obtenerDatos(
            "SELECT nombre, apellido, correo, ruta_imagen 
            FROM usuarios 
            WHERE id_usuario = $idLector"
        );
    }

    // Método para validar si un usuario es propietario de una carpeta en particular
    public function validarPropietarioCarpeta($idPropietario,$idCarpeta){
        $consulta = $this->obtenerDatos(
            "SELECT id_carpeta 
            FROM carpetas 
            WHERE id_carpeta = $idCarpeta 
                AND id_usuario = $idPropietario"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para validar si a un usuario se le ha compartido una carpeta en específico
    public function validarReceptor($idPropietario,$idReceptor,$idCarpeta){
        $consulta = $this->obtenerDatos(
            "SELECT id_compartido 
            FROM compartidos 
            WHERE id_carpeta = $idCarpeta
                AND id_propietario = $idPropietario 
                AND id_receptor = $idReceptor"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para compartir una carpeta, agregando un nuevo registro en la tabla compartidos
    public function compartirCarpeta($idCarpeta,$idPropietario,$idReceptor,$fechaCompartido){
        return $this->ejecutarConsulta("INSERT INTO compartidos VALUES (NULL, NULL, $idCarpeta, $idPropietario, $idReceptor, '$fechaCompartido');");
    }

    // Método para obtener los ids de las carpetas hijas de una carpeta en particular (pueden estar en papelera o no)
    public function obtenerRutasCompartirCarpetas($ruta){
        return $this->obtenerDatos(
            "SELECT DISTINCT id_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta LIKE '$ruta/%';"
        );
    }

    // Método para obtener los ids de los archivos hijos de una carpeta en particular (pueden estar en papelera o no)
    public function obtenerRutasCompartirArchivos($ruta){
        return $this->obtenerDatos(
            "SELECT DISTINCT id_archivo 
            FROM archivos 
            WHERE ruta_archivo LIKE '$ruta/%';"
        );
    }
    
    // Método para obtener los ids de las carpetas hijas de una carpeta en particular (que no esten en papelera)
    public function obtenerRutasCompartirCarpetas2($ruta){
        return $this->obtenerDatos(
            "SELECT DISTINCT id_carpeta 
            FROM carpetas 
            WHERE ruta_carpeta LIKE '$ruta/%' 
            AND estado_papelera = 'no';"
        );
    }

    // Método para obtener los ids de los archivos hijos de una carpeta en particular (que no esten en papelera)
    public function obtenerRutasCompartirArchivos2($ruta){
        return $this->obtenerDatos(
            "SELECT DISTINCT id_archivo 
            FROM archivos 
            WHERE ruta_archivo LIKE '$ruta/%' 
            AND estado_papelera = 'no';"
        );
    }
    
    // Método para obtener los ids de las carpetas hijas de una carpeta que no han sido compartidas
    public function capturarIdCarpetasACompartir($idPropietario,$idReceptor,$ruta_carpeta){
        return $this->obtenerDatos(
            "SELECT id_carpeta 
            FROM carpetas 
            WHERE id_usuario = $idPropietario 
                AND ruta_carpeta LIKE '$ruta_carpeta/%' 
                AND id_carpeta NOT IN (SELECT ca.id_carpeta 
                                        FROM compartidos co 
                                        JOIN carpetas ca ON co.id_carpeta = ca.id_carpeta 
                                        WHERE ca.ruta_carpeta LIKE '$ruta_carpeta/%' 
                                            AND co.id_propietario = $idPropietario 
                                            AND co.id_receptor = $idReceptor);"
        );
    }

    // Método para obtener los ids de los archivos hijos de una carpeta que no han sido compartidos
    public function capturarIdArchivosACompartir($idPropietario,$idReceptor,$ruta_carpeta){
        return $this->obtenerDatos(
            "SELECT id_archivo 
            FROM archivos 
            WHERE id_usuario = $idPropietario 
                AND ruta_archivo LIKE '$ruta_carpeta/%' 
                AND id_archivo NOT IN (SELECT ar.id_archivo 
                                        FROM compartidos co JOIN archivos ar ON co.id_archivo = ar.id_archivo 
                                        WHERE ar.ruta_archivo LIKE '$ruta_carpeta/%' 
                                            AND co.id_propietario = $idPropietario 
                                            AND co.id_receptor = $idReceptor);"
        );
    }

    // Método para compartir un archivo, agregando un registro en la tabla compartidos
    public function compartirArchivo($idArchivo,$idPropietario,$idReceptor,$fechaCompartido){
        return $this->ejecutarConsulta("INSERT INTO compartidos VALUES (NULL, $idArchivo, NULL, $idPropietario, $idReceptor, '$fechaCompartido');");
    }

    // Método para obtener las carpetas compartidas que esten en la raiz
    public function obtenerCarpetasCompartidasRaiz($idReceptor){
        return $this->obtenerDatos(
            "SELECT m.id,c.nombre_carpeta,u.nombre, c.fecha_creacion 
            FROM compartidos s 
            JOIN carpetas c ON s.id_carpeta = c.id_carpeta 
            JOIN id_mapping m ON m.id_carpeta = c.id_carpeta 
            JOIN usuarios u ON c.id_usuario = u.id_usuario 
            WHERE s.id_receptor = $idReceptor 
                AND c.estado_papelera = 'no' 
                AND NOT EXISTS (SELECT 1 
                                FROM compartidos s2 
                                JOIN carpetas c2 ON s2.id_carpeta = c2.id_carpeta 
                                WHERE c2.ruta_carpeta != c.ruta_carpeta 
                                    AND c.ruta_carpeta LIKE CONCAT(c2.ruta_carpeta, '/%') 
                                    AND s2.id_receptor = $idReceptor 
                                    AND c2.estado_papelera = 'no' 
                                    AND c.id_carpetaPadre = c2.id_carpeta);"
        );
    }

    // Método para obtener los datos de las carpetas hijas compartidas de una carpeta compartida
    public function obtenerCarpetasCompartidas($rutaCarpeta,$idReceptor){
        return $this->obtenerDatos(
            "SELECT m.id, c.nombre_carpeta, u.nombre, c.fecha_creacion 
            FROM carpetas c 
            JOIN usuarios u ON c.id_usuario = u.id_usuario 
            JOIN id_mapping m ON c.id_carpeta = m.id_carpeta 
            JOIN compartidos s ON c.id_carpeta = s.id_carpeta 
            WHERE c.ruta_carpeta LIKE '$rutaCarpeta/%' 
                AND c.ruta_carpeta NOT LIKE '$rutaCarpeta/%/%' 
                AND c.estado_papelera = 'no' 
                AND s.id_receptor = $idReceptor;"
        );
    }

    // Método para validar si una carpeta ha sido compartida a un usuario en específico
    public function esCarpetaCompartida($idCarpeta,$idReceptor){
        $consulta = $this->obtenerDatos(
            "SELECT id_carpeta 
            FROM compartidos 
            WHERE id_carpeta = $idCarpeta 
                AND id_receptor = $idReceptor"
        );
        
        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }
    
    // Método para quitar acceso a una carpeta, eliminando el registro de la tabla
    public function quitarAccesoCarpeta($idPropietario,$idReceptor,$ruta_carpeta){    
        return $this->ejecutarConsulta(
            "DELETE co FROM compartidos co 
            JOIN carpetas ca on co.id_carpeta = ca.id_carpeta 
            WHERE ca.ruta_carpeta = '$ruta_carpeta' 
                AND co.id_propietario = $idPropietario 
                AND co.id_receptor = $idReceptor 
                AND ca.estado_papelera = 'no'"
        );
    }

    // Método para quitar acceso a las carpetas hijas de una carpeta, eliminando los registros de la tabla
    public function quitarAccesoCarpetas($idPropietario,$idReceptor,$ruta_carpeta){
        return $this->ejecutarConsulta(
            "DELETE co FROM compartidos co 
            JOIN carpetas ca on co.id_carpeta = ca.id_carpeta 
            WHERE ca.ruta_carpeta LIKE '$ruta_carpeta/%' 
                AND co.id_propietario = $idPropietario 
                AND co.id_receptor = $idReceptor 
                AND ca.estado_papelera = 'no'"
        );
    }

    // Método para quitar acceso a los archivos hijos de una carpeta, eliminando los registros de la tabla
    public function quitarAccesoArchivos($idPropietario,$idReceptor,$ruta_carpeta){
        return $this->ejecutarConsulta(
            "DELETE co FROM compartidos co 
            JOIN archivos ar on co.id_archivo = ar.id_archivo 
            WHERE ar.ruta_archivo LIKE '$ruta_carpeta/%' 
                AND co.id_propietario = $idPropietario 
                AND co.id_receptor = $idReceptor 
                AND ar.estado_papelera = 'no'"
        );
    }

    public function eliminarCompartirCarpeta($idCarpeta){
        return $this->ejecutarConsulta(
            "DELETE FROM compartidos 
            WHERE id_carpeta = $idCarpeta;"
        );
    }

    // FUNCIONES INFORMACION CARPETA

    // Método para obtener los datos de una carpeta en específico
    public function obtenerDatosCarpeta($rutaCarpeta){
        return $this->obtenerDatos(
            "SELECT ca.nombre_carpeta,u.nombre,u.apellido,ca.fecha_creacion 
            FROM carpetas ca 
            JOIN usuarios u ON ca.id_usuario = u.id_usuario 
            WHERE ca.ruta_carpeta = '$rutaCarpeta';"
        );
    }

    // FUNCIONES DESCARGAR CARPETA

    // Método que convierte el contenido de un directorio en un archivo ZIP
    public function convertirZip($dir, $zipArchivo, $zipDir = '') {
        // Verifica si la ruta proporcionada es un directorio válido
        if (is_dir($dir)) {
            // Abre el directorio para su lectura
            if ($dh = opendir($dir)) {
                // Si se proporciona un nombre para el directorio dentro del ZIP, lo agrega
                if (!empty($zipDir)) {
                    $zipArchivo->addEmptyDir($zipDir);
                }
                // Lee cada archivo o subdirectorio dentro del directorio
                while (($file = readdir($dh)) !== false) {
                    // Omite los directorios "." y ".."
                    if ($file == "." || $file == "..") {
                        continue;
                    }
                    // Construye la ruta completa del archivo o subdirectorio
                    $filePath = $dir . $file;
                    // Si es un directorio, llama recursivamente a convertirZip
                    if (is_dir($filePath)) {
                        $this->convertirZip($filePath . "/", $zipArchivo, $zipDir . $file . "/");
                    } else {
                        // Si es un archivo, lo agrega al ZIP
                        $zipArchivo->addFile($filePath, $zipDir . $file);
                    }
                }
                // Cierra el directorio después de leerlo
                closedir($dh);
            }
        }
    }

    // Método que crea un archivo ZIP de un directorio específico
    public function crearZipCarpeta($rutaCarpeta, $nombreCarpeta) {
        // Genera un nombre único para el archivo ZIP basado en la fecha y hora actual
        $fechaActual = date('Y_m_d_His') . '_' . substr(microtime(), 2, 8);
        $nombreZip = "../../../../../zip/" . $fechaActual . "_" . $nombreCarpeta . ".zip";
    
        // Crea una nueva instancia de ZipArchive
        $zip = new ZipArchive();
        // Abre el archivo ZIP para escribir
        if ($zip->open($nombreZip, ZipArchive::CREATE) !== TRUE) {
            exit("No se puede abrir el archivo <$nombreZip>\n");
        }
    
        // Convierte el contenido del directorio en un archivo ZIP
        $this->convertirZip($rutaCarpeta . '/', $zip, $nombreCarpeta . '/');
        // Cierra el archivo ZIP después de escribir
        $zip->close();
    
        // Verifica si el archivo ZIP fue creado correctamente y lo retorna
        if (file_exists($nombreZip)) {
            return $nombreZip;
        } else {
            return null;
        }
    }

    // Método que convierte el contenido de un directorio o archivo específico en un archivo ZIP
    public function convertirZipContenido($path, $zipArchivo, $zipDir = '') {
        // Verifica si la ruta proporcionada es un directorio válido
        if (is_dir($path)) {
            // Abre el directorio para su lectura
            if ($dh = opendir($path)) {
                // Si se proporciona un nombre para el directorio dentro del ZIP, lo agrega
                if (!empty($zipDir)) {
                    $zipArchivo->addEmptyDir($zipDir);
                }
                // Lee cada archivo o subdirectorio dentro del directorio
                while (($file = readdir($dh)) !== false) {
                    // Omite los directorios "." y ".."
                    if ($file == "." || $file == "..") {
                        continue;
                    }
                    // Construye la ruta completa del archivo o subdirectorio
                    $filePath = $path . '/' . $file;
                    // Si es un directorio, llama recursivamente a convertirZipContenido
                    if (is_dir($filePath)) {
                        $this->convertirZipContenido($filePath . "/", $zipArchivo, $zipDir . $file . "/");
                    } else {
                        // Si es un archivo, lo agrega al ZIP
                        $zipArchivo->addFile($filePath, $zipDir . $file);
                    }
                }
                // Cierra el directorio después de leerlo
                closedir($dh);
            }
        // Si la ruta proporcionada es un archivo, lo agrega directamente al ZIP
        } else if (file_exists($path)) {
            $zipArchivo->addFile($path, $zipDir . basename($path));
        }
    }

    // Método que crea un archivo ZIP de múltiples contenidos (archivos o directorios)
    public function crearZipContenidos($contenidos, $nombreZip) {
        // Genera un nombre único para el archivo ZIP basado en la fecha y hora actual
        $fechaActual = date('Y_m_d_His') . '_' . substr(microtime(), 2, 8);
        $nombreZip = "../../../../zip/" . $fechaActual . "_" . $nombreZip . ".zip";

        // Crea una nueva instancia de ZipArchive
        $zip = new ZipArchive();
        // Abre el archivo ZIP para escribir
        if ($zip->open($nombreZip, ZipArchive::CREATE) !== TRUE) {
            return "No se puede abrir el archivo <$nombreZip>\n";
        }

        // Añade cada contenido (carpeta o archivo) al ZIP
        foreach ($contenidos as $contenido) {
            if (is_dir($contenido)) {
                // Si el contenido es un directorio, obtiene su nombre y llama a convertirZipContenido
                $nombreCarpeta = basename($contenido);
                $this->convertirZipContenido($contenido . '/', $zip, $nombreCarpeta . '/');
            } else {
                // Si el contenido es un archivo, llama a convertirZipContenido directamente
                $this->convertirZipContenido($contenido, $zip);
            }
        }

        // Cierra el archivo ZIP después de escribir
        $zip->close();

        // Verifica si el archivo ZIP fue creado correctamente y lo retorna
        if (file_exists($nombreZip)) {
            return $nombreZip;
        } else {
            return null;
        }
    }

    /* FUNCIONES - COMPARTIDOS */

    // Método para obtener las rutas de los elementos compartidos en una carpeta específica
    public function obtenerRutasCompartidos($rutaCarpeta, $idReceptor){
        return $this->obtenerDatos("WITH RECURSIVE ElementosCompartidos AS (
            SELECT 'carpeta' AS tipo,ca.ruta_carpeta AS ruta
            FROM carpetas ca
            INNER JOIN compartidos c ON ca.id_carpeta = c.id_carpeta
            WHERE c.id_receptor = $idReceptor AND ca.estado_papelera = 'no' AND ca.ruta_carpeta LIKE '$rutaCarpeta/%'
            AND EXISTS (
                SELECT 1 FROM carpetas padre
                INNER JOIN compartidos compartidos_padre ON padre.id_carpeta = compartidos_padre.id_carpeta
                WHERE padre.id_carpeta = ca.id_carpetaPadre AND compartidos_padre.id_receptor = $idReceptor
            )
            UNION ALL
            SELECT 'archivo' AS tipo, a.ruta_archivo AS ruta
            FROM archivos a
            INNER JOIN compartidos c ON a.id_archivo = c.id_archivo
            WHERE c.id_receptor = $idReceptor AND a.estado_papelera = 'no' AND a.ruta_archivo LIKE '$rutaCarpeta/%'
            AND EXISTS (
                SELECT 1 FROM carpetas padre
                INNER JOIN compartidos compartidos_padre ON padre.id_carpeta = compartidos_padre.id_carpeta
                WHERE padre.id_carpeta = a.id_carpeta AND compartidos_padre.id_receptor = $idReceptor
                AND EXISTS (
                    SELECT 1 FROM carpetas abuelo
                    INNER JOIN compartidos compartidos_abuelo ON abuelo.id_carpeta = compartidos_abuelo.id_carpeta
                    WHERE abuelo.id_carpeta = padre.id_carpetaPadre AND compartidos_abuelo.id_receptor = $idReceptor
                )
            )
            UNION ALL
            SELECT 'archivo' AS tipo,a.ruta_archivo AS ruta
            FROM archivos a
            INNER JOIN compartidos c ON a.id_archivo = c.id_archivo
            WHERE c.id_receptor = $idReceptor AND a.estado_papelera = 'no' AND a.ruta_archivo LIKE '$rutaCarpeta/%' AND a.ruta_archivo NOT LIKE '$rutaCarpeta/%/%'
            AND EXISTS (
                SELECT 1 FROM carpetas padre
                INNER JOIN compartidos compartidos_padre ON padre.id_carpeta = compartidos_padre.id_carpeta
                WHERE padre.id_carpeta = a.id_carpeta AND compartidos_padre.id_receptor = $idReceptor
            )
        )
        SELECT DISTINCT tipo, ruta FROM ElementosCompartidos ORDER BY tipo DESC, ruta;");
    }

    // Método para convertir elementos compartidos en un archivo ZIP
    public function convertirZipCompartidos($rutas, $zipArchivo, $baseDir = '') {
        $tipo = $rutas['tipo'];
        $rutaCompleta = $rutas['ruta'];

        if ($tipo === 'carpeta') {
            // Agrega una carpeta vacía al ZIP
            $zipArchivo->addEmptyDir($baseDir);
        } else if ($tipo === 'archivo' && file_exists($rutaCompleta)) {
            // Agrega un archivo al ZIP
            $zipArchivo->addFile($rutaCompleta, $baseDir . basename($rutaCompleta));
        }
    }

    // Método para crear un archivo ZIP de elementos compartidos
    public function crearZipCompartidos($contenidos, $nombreZip, $vacio) {
        // Genera un nombre único para el archivo ZIP basado en la fecha y hora actual
        $fechaActual = date('Y_m_d_His') . '_' . substr(microtime(), 2, 8);
        $nombreZip = "../../../../../zip/" . $fechaActual . "_" . $nombreZip . ".zip";

        // Crea una nueva instancia de ZipArchive
        $zip = new ZipArchive();
        if ($zip->open($nombreZip, ZipArchive::CREATE) !== TRUE) {
            return null;
        }

        if ($vacio === true) {
            // Agrega una carpeta vacía al ZIP si el parámetro vacío es verdadero
            $nombreCarpeta = basename($contenidos) . '/';
            $zip->addEmptyDir($nombreCarpeta);
        } else {
            // Crear arrays para almacenar las carpetas y archivos que ya han sido añadidos
            $carpetasAgregadas = [];
            $archivosAgregados = [];

            // Añade cada contenido al ZIP
            foreach ($contenidos as $contenido) {
                // Convierte una ruta completa en una ruta relativa eliminando la parte común con el directorio base del primer contenido, 
                // por ejemplo, transformando /home/usuario/proyecto/carpeta1/archivo1.txt en carpeta1/archivo1.txt
                $rutaRelativa = str_replace(dirname(reset($contenidos)['ruta']) . '/', '', $contenido['ruta']);
                if ($contenido['tipo'] === 'carpeta') {
                    // Evita duplicados de carpetas
                    if (!in_array($contenido['ruta'], $carpetasAgregadas)) {
                        $this->convertirZipCompartidos(['tipo' => 'carpeta', 'ruta' => $contenido['ruta']], $zip, $rutaRelativa . '/');
                        $carpetasAgregadas[] = $contenido['ruta'];
                    }
                } else {
                    // Evita duplicados de archivos
                    if (!in_array($contenido['ruta'], $archivosAgregados)) {
                        $zip->addFile($contenido['ruta'], $rutaRelativa);
                        $archivosAgregados[] = $contenido['ruta'];
                    }
                }
            }
        }

        // Cierra el archivo ZIP después de agregar todo el contenido
        $zip->close();

        // Verifica si el archivo ZIP fue creado correctamente y lo retorna
        if (file_exists($nombreZip)) {
            return $nombreZip;
        } else {
            return null;
        }
    }

    // Método donde se ejecuta una consulta que obtiene las rutas de las carpetas en la papelera que cumplen con la condición de tener
    // exactamente cinco niveles de profundidad en la jerarquía de carpetas, y que no tengan más de cinco niveles
    public function obtenerRutasCarpetasAEliminar($idUsuario){
        return $this->obtenerDatos(
            "SELECT ruta_carpeta_papelera 
            FROM carpetas
            WHERE ruta_carpeta_papelera LIKE '/%/%/%/%/%' 
                AND ruta_carpeta_papelera NOT LIKE '/%/%/%/%/%/%' 
                AND id_usuario = $idUsuario;"
        );
    }

    // Método donde se ejecuta una consulta que obtiene las rutas de los archivos en la papelera que cumplen
    // con que la ruta debe tener exactamente 5 niveles de profundidad
    public function obtenerRutasArchivosAEliminar($idUsuario){
        return $this->obtenerDatos(
            "SELECT ruta_archivo_papelera 
            FROM archivos 
            WHERE ruta_archivo_papelera LIKE '/%/%/%/%/%' 
                AND ruta_archivo_papelera NOT LIKE '/%/%/%/%/%/%' 
                AND id_usuario = $idUsuario;"
        );
    }

    // Método donde se ejecuta una consulta que eliminar carpetas donde la ruta debe tener exactamente 5 niveles de profundidad
    public function vaciarPapeleraCarpetas($idUsuario){
        return $this->ejecutarConsulta(
            "DELETE FROM carpetas
            WHERE ruta_carpeta_papelera LIKE '/%/%/%/%/%' 
                AND ruta_carpeta_papelera NOT LIKE '/%/%/%/%/%/%' 
                AND id_usuario = $idUsuario;"
        );
    }

    // Método donde se ejecuta una consulta que eliminar archivos donde la ruta debe tener exactamente 5 niveles de profundidad
    public function vaciarPapeleraArchivos($idUsuario){
        return $this->ejecutarConsulta(
            "DELETE FROM archivos 
            WHERE ruta_archivo_papelera LIKE '/%/%/%/%/%' 
                AND ruta_archivo_papelera NOT LIKE '/%/%/%/%/%/%' 
                AND id_usuario = $idUsuario;"
        );
    }
}

?>

