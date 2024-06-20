<?php 
// Incluir conexión a la base de datos
require_once "conexion.php";
// Definición de la clase Archivo
class Archivo {
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

    private function ejecutarConsulta($sql) {
         // Se instancia un objeto de la clase Conexion para establecer la conexión a la base de datos
        $conexion = new Conexion();
        $con = $conexion->con;

        $consulta = $con->query($sql);

        if (!$consulta) {
            // Si hay un error en la consulta, lanzamos una excepción
            throw new Exception("Error en la consulta: " . $con->error);
        }

        return true;
        
    }

    // Método para validar si existe un archivo en la ruta que se pasa como parámetro
    public function validarRutaArchivo($ruta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE ruta_archivo = '$ruta' 
                AND estado_papelera='no'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para obtener el id posterior al último id de archivo
    public function ultimoIdArchivo(){
        $consulta = $this->obtenerDatos(
            "SELECT id_archivo 
            FROM archivos 
            ORDER BY id_archivo 
            DESC LIMIT 1;"
        );
    
        if(count($consulta) === 0){
            $id_posterior = 1;
        } else {
            $id_posterior = $consulta["0"]["id_archivo"] + 1;
        }
        return $id_posterior;
    }

    // Método para obtener id de un archivo según una ruta
    public function obtenerIdArchivo($ruta_archivo){
        $consulta = $this->obtenerDatos("SELECT id_archivo FROM archivos WHERE ruta_archivo = '$ruta_archivo';");
    
        if(count($consulta) !== 0){
            $id_archivo = $consulta["0"]["id_archivo"];
        } else {
            $id_archivo = false;
        }

        return $id_archivo;
    }

    // Método para obtener los archivos hijos de primer nivel de una carpeta
    public function obtenerArchivos($usuario, $rutaEsperada, $rutaNoEsperada){
        return $this->obtenerDatos(
            "SELECT m.id, a.nombre_archivo, u.nombre, a.ruta_icono, a.tamanio, a.fecha_creacion 
            FROM archivos a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            JOIN id_mapping m ON a.id_archivo = m.id_archivo 
            WHERE u.nombre = '$usuario' 
                AND a.ruta_archivo LIKE '$rutaEsperada' 
                AND a.ruta_archivo NOT LIKE '$rutaNoEsperada' 
                AND a.estado_papelera = 'no';"
        );
    }

    // Método para obtener los archivos hijos de primer nivel de una carpeta que esta en papelera
    public function obtenerArchivosPapelera($usuario, $rutaEsperada, $rutaNoEsperada){
        return $this->obtenerDatos(
            "SELECT m.id, a.nombre_archivo, a.id_archivo_papelera, u.nombre, a.ruta_icono, a.tamanio, a.fecha_creacion 
            FROM archivos a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            JOIN id_mapping m ON a.id_archivo = m.id_archivo 
            WHERE u.nombre = '$usuario' 
                AND a.ruta_archivo_papelera LIKE '$rutaEsperada' 
                AND a.ruta_archivo_papelera NOT LIKE '$rutaNoEsperada' 
                AND a.estado_papelera = 'si';"
            );
    }

    // Método para asignar un ícono al archivo que se sube
    public function setIconoArchivoPorExtension($file_nombre) {
        $extension = pathinfo($file_nombre, PATHINFO_EXTENSION);
        $iconos_archivos = ["sh" => "/assets/imgs/files/text.png",
            "php" => "/assets/imgs/files/php.png",
            "sql" => "/assets/imgs/files/sql.png",
            "desconocido" => "/assets/imgs/files/desconocido.png",
        ];

        if (array_key_exists($extension, $iconos_archivos)) {
            return $iconos_archivos[$extension];
        } else {
            return $iconos_archivos['desconocido'];
        }
    }

    // Método para asignar un ícono al archivo que se sube
    public function setIconoArchivo($file_nombre,$mimeTipo){
        if ($mimeTipo === 'application/octet-stream') {
            // Si el tipo MIME es octet-stream, llamamos a otra función
            return $this->setIconoArchivoPorExtension($file_nombre);
        } else {
            $partes = explode('/', $mimeTipo);
            $tipo = end($partes);

            $iconos_archivos = ["avi" => "/assets/imgs/files/video.png",
                    "bmp" => "/assets/imgs/files/image.png",
                    "webp" => "/assets/imgs/files/image.png",
                    "css" => "/assets/imgs/files/css.png",
                    "desconocido" => "/assets/imgs/files/desconocido.png",
                    "doc" => "/assets/imgs/files/word.png",
                    "msword" => "/assets/imgs/files/word.png",
                    "docx" => "/assets/imgs/files/word.png",
                    "vnd.openxmlformats-officedocument.wordprocessingml.document" => "/assets/imgs/files/word.png",
                    "vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "/assets/imgs/files/excel.png",
                    "gif" => "/assets/imgs/files/image.png",
                    "html" => "/assets/imgs/files/html.png",
                    "iso" => "/assets/imgs/files/iso.png",
                    "jpg" => "/assets/imgs/files/image.png",
                    "jpeg" => "/assets/imgs/files/image.png",
                    "js" => "/assets/imgs/files/js-file.png",
                    "x-javascript" => "/assets/imgs/files/js-file.png",
                    "javascript" => "/assets/imgs/files/js-file.png",
                    "x-sh" => "/assets/imgs/files/text.png",
                    "sh" => "/assets/imgs/files/text.png",
                    "mp3" => "/assets/imgs/files/music.png",
                    "mpg" => "/assets/imgs/files/music.png",
                    "mpeg" => "/assets/imgs/files/music.png",
                    "mp4" => "/assets/imgs/files/video.png",
                    "pdf" => "/assets/imgs/files/pdf.png",
                    "php" => "/assets/imgs/files/php.png",
                    "png" => "/assets/imgs/files/image.png",
                    "ppt" => "/assets/imgs/files/ppt.png",
                    "vnd.openxmlformats-officedocument.presentationml.presentation" => "/assets/imgs/files/ppt.png",
                    "sql" => "/assets/imgs/files/sql.png",
                    "svg" => "/assets/imgs/files/svg.png",
                    "svg+xml" => "/assets/imgs/files/svg.png",
                    "txt" => "/assets/imgs/files/txt.png",
                    "plain" => "/assets/imgs/files/text.png",
                    "text" => "/assets/imgs/files/text.png",
                    "octet-stream" => "/assets/imgs/files/zip.png",
                    "zip" => "/assets/imgs/files/zip.png",
                    "x-tar" => "/assets/imgs/files/zip.png",
                    "x-zip-compressed" => "/assets/imgs/files/zip.png"];

            if (array_key_exists($tipo, $iconos_archivos)) {
                    return $iconos_archivos[$tipo];
            } else {
                    return $iconos_archivos['desconocido'];
            }
        }
    }

    // Método para insertar un nuevo archivo en la tabla archivos
    public function agregarArchivo($id_posterior,$ruta_archivo,$file_nombre,$file_tamanio,$fecha_creacion,$file_tipo,$id_usuario,$id_carpeta_padre){
        $rutaIcono = $this->setIconoArchivo($file_nombre,$file_tipo);

        return $this->ejecutarConsulta("INSERT INTO archivos VALUES ($id_posterior,'$ruta_archivo','$file_nombre',$file_tamanio,'$fecha_creacion','$file_tipo','no',null,$id_usuario,$id_carpeta_padre,null,null,'$rutaIcono');");
    }

    // Método para insertar el mapeo del archivo que se sube
    public function agregarMapeo($id_unico,$id_archivo){
        return $this->ejecutarConsulta("INSERT INTO id_mapping VALUES ('$id_unico',null, $id_archivo, 'archivo');");
    }

    // Método para obtener id de mapeo de un archivo
    public function obtenerIdMap($id_archivo){
        return $this->obtenerDatos(
            "SELECT id 
            FROM id_mapping 
            WHERE id_carpeta = $id_archivo"
        );
    }

    // Método para obtener id de mapeo de un archivo según su ruta
    public function obtenerIdMapRuta($ruta){
        return $this->obtenerDatos(
            "SELECT id 
            FROM id_mapping 
            WHERE id_archivo = (SELECT id_archivo 
                                FROM archivos 
                                WHERE ruta_archivo = '$ruta' 
                                AND estado_papelera = 'no')"
        );
    }

    // Método para obtener id de un archivo según su id de mapeo
    public function obtenerIdDesdeMap($idRuta){
        return $this->obtenerDatos(
            "SELECT id_archivo 
            FROM id_mapping 
            WHERE id = '$idRuta' 
                AND tipo_elemento = 'archivo'"
        );
    }

    // Método para obtener ruta de un archivo según su id de mapeo
    public function obtenerRutaArchivo($id_ruta){
        return $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE id_archivo = (SELECT id_archivo 
                                FROM id_mapping 
                                WHERE id = '$id_ruta' 
                                AND tipo_elemento = 'archivo');"
        );
    }

    // Método para obtener ruta de un archivo que esta en la papelera según su id de mapeo
    public function obtenerRutaArchivoPapelera($id_ruta){
        return $this->obtenerDatos(
            "SELECT ruta_archivo_papelera 
            FROM archivos 
            WHERE id_archivo_papelera = '$id_ruta' 
                AND estado_papelera = 'si';"
        );
    }

    // Método para obtener formato del tamaño del archivo
    public function obtenerFormatoTamanio($tamanio){
        if ($tamanio >= 1073741824) {
            $formatoTamanio = number_format($tamanio / 1073741824, 2) . 'GB';
        } elseif ($tamanio >= 1048576) {
            $formatoTamanio  = number_format($tamanio / 1048576, 2) . 'MB';
        } elseif ($tamanio >= 1024) {
            $formatoTamanio  = number_format($tamanio / 1024, 2) . 'KB';
        } elseif ($tamanio > 1) {
            $formatoTamanio  = $tamanio . 'B';
        } elseif ($tamanio == 1) {
            $formatoTamanio  = $tamanio . 'b';
        } else {
            $formatoTamanio  = '0B';
        }
        return $formatoTamanio ;
    }

    // Método para obtener formato del tamaño del archivo
    public function obtenerFormatoTamanio2($tamanio){
        if ($tamanio >= 1073741824) {
            $formatoTamanio = number_format($tamanio / 1073741824, 2) . ' GB';
        } elseif ($tamanio >= 1048576) {
            $formatoTamanio  = number_format($tamanio / 1048576, 2) . ' MB';
        } elseif ($tamanio >= 1024) {
            $formatoTamanio  = number_format($tamanio / 1024, 2) . ' KB';
        } elseif ($tamanio > 1) {
            $formatoTamanio  = $tamanio . ' B';
        } elseif ($tamanio == 1) {
            $formatoTamanio  = $tamanio . ' b';
        } else {
            $formatoTamanio  = '0 B';
        }
        return $formatoTamanio ;
    }

    // Método para obtener formato de fecha
    public function obtenerFormatoFecha($fecha){
        $timestamp = strtotime($fecha);

        $formatoFecha = date("d/m/Y", $timestamp);
        
        return $formatoFecha;
    }

    // Método para crear estructura HTML de un archivo
    public function crearArchivo($id_archivo,$nombre_archivo,$nombre_usuario,$ruta_icono,$tamanio,$fecha){

        $estructuraArchivo = '
                    <div class="archivo" data-id="' . $id_archivo . '" data-tipo="archivo" data-usuario="' . $nombre_usuario . '">
                        <img src="'.$ruta_icono.'" alt="" class="icon_archivo">
                        <div class="con_archivo">
                          <p data-id="' . $id_archivo . '" data-tipo="archivo" data-usuario="' . $nombre_usuario . '"  class="fichero_elm">' . $nombre_archivo . '</p>
                          <div class="con_archivo_detalles">
                            <p>'.$this->obtenerFormatoTamanio($tamanio).'</p>
                            <p>'.$this->obtenerFormatoFecha($fecha).'</p>
                          </div>
                        </div>
                        <i class="icon-dots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M112,60a16,16,0,1,1,16,16A16,16,0,0,1,112,60Zm16,52a16,16,0,1,0,16,16A16,16,0,0,0,128,112Zm0,68a16,16,0,1,0,16,16A16,16,0,0,0,128,180Z"></path></svg>
                      </i>
                    </div>';

        return $estructuraArchivo;
    }

    // Método para obtener los datos de un nuevo archivo y llamar al método para crear estructura HTML
    public function obtenerYCrearArchivo($id_archivo, $id_usuario){
        $consulta = $this->obtenerDatos(
            "SELECT m.id, a.nombre_archivo, u.nombre, a.ruta_icono, a.tamanio, a.fecha_creacion 
            FROM archivos a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            JOIN id_mapping m ON a.id_archivo = m.id_archivo 
            WHERE a.id_usuario = $id_usuario 
                AND a.id_archivo = $id_archivo 
                AND a.estado_papelera = 'no';"
        );

        return $this->crearArchivo($consulta[0]["id"],$consulta[0]["nombre_archivo"],$consulta[0]["nombre"],$consulta[0]["ruta_icono"],$consulta[0]["tamanio"],$consulta[0]["fecha_creacion"]);
    }

    // FUNCIONES PARA MOVER ARCHIVO A LA PAPELERA

    // Método para crear estructura de archivo en la papelera
    public function crearArchivoPapelera($id_archivo,$nombre_archivo,$nombre_usuario,$id_archivo_papelera,$ruta_icono,$tamanio,$fecha){
        $estructuraArchivo = '
                    <div class="archivo" data-id="' . $id_archivo . '" data-tipo="archivo" data-id_papelera="'.$id_archivo_papelera.'" data-usuario="' . $nombre_usuario . '">
                        <img src="'.$ruta_icono.'" alt="" class="icon_archivo">
                        <div class="con_archivo">
                          <p data-id="' . $id_archivo . '" data-tipo="archivo" data-usuario="' . $nombre_usuario . '"  class="fichero_elm">' . $nombre_archivo . '</p>
                          <div class="con_archivo_detalles">
                            <p>'.$this->obtenerFormatoTamanio($tamanio).'</p>
                            <p>'.$this->obtenerFormatoFecha($fecha).'</p>
                          </div>
                        </div>
                        <i class="icon-dots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M112,60a16,16,0,1,1,16,16A16,16,0,0,1,112,60Zm16,52a16,16,0,1,0,16,16A16,16,0,0,0,128,112Zm0,68a16,16,0,1,0,16,16A16,16,0,0,0,128,180Z"></path></svg>
                      </i>
                    </div>';

        return $estructuraArchivo;
    }

    // Método para crear estructura de archivo compartido
    public function crearArchivoCompartido($id_archivo,$nombre_archivo,$nombre_usuario,$ruta_icono,$tamanio,$fecha){
        $estructuraArchivo = '
                    <div class="archivo" data-id="' . $id_archivo . '" data-tipo="archivo" data-usuario="' . $nombre_usuario . '">
                        <img src="'.$ruta_icono.'" alt="" class="icon_archivo">
                        <div class="con_archivo">
                          <p data-id="' . $id_archivo . '" data-tipo="archivo" data-usuario="' . $nombre_usuario . '"  class="fichero_elm">' . $nombre_archivo . '</p>
                          <div class="con_archivo_detalles">
                            <p>'.$this->obtenerFormatoTamanio($tamanio).'</p>
                            <p>'.$this->obtenerFormatoFecha($fecha).'</p>
                          </div>
                        </div>
                        <i class="icon-dots">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M112,60a16,16,0,1,1,16,16A16,16,0,0,1,112,60Zm16,52a16,16,0,1,0,16,16A16,16,0,0,0,128,112Zm0,68a16,16,0,1,0,16,16A16,16,0,0,0,128,180Z"></path></svg>
                      </i>
                    </div>';

    return $estructuraArchivo;
    }

    // Método para mover archivo a la papelera actualizando su ruta
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

    // Método para obtener id de papelera de un archivo
    public function obtenerIdArchivoPapelera($ruta){
        $consulta = $this->obtenerDatos(
            "SELECT id_archivo_papelera 
            FROM archivos 
            WHERE ruta_archivo = '$ruta'"
        );

        return $consulta[0]["id_archivo_papelera"];
    }

    // Método para obtener id de papepelra de un archivo
    public function obtenerIdArchivoPapelera2($idArchivo){
        $consulta = $this->obtenerDatos(
            "SELECT id_archivo_papelera 
            FROM archivos 
            WHERE id_archivo = $idArchivo"
        );

        return $consulta[0]["id_archivo_papelera"];
    }

    // FUNCIONES PARA ELIMINAR ARCHIVO DE LA PAPELERA
    
    // Método para eliminar un archivo 
    public function eliminarArchivo($ruta,$ruta_papelera){
        return $this->ejecutarConsulta(
            "DELETE FROM archivos 
            WHERE ruta_archivo = '$ruta' 
                AND estado_papelera = 'si' 
                AND ruta_archivo_papelera = '$ruta_papelera';")
        ;
    }

    // Método para validar si existe una un archivo en la papelera
    public function validarRutaArchivoPapelera($ruta_papelera){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE ruta_archivo_papelera = '$ruta_papelera' 
                AND estado_papelera='si'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    //FUNCIONES PARA CAMBIAR DE NOMBRE A UN ARCHIVO
    
    // Método para verificar si existe un archivo en una ruta en específico
     public function verificarNombreArchivo($ruta_archivo){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE ruta_archivo = '$ruta_archivo' 
                AND estado_papelera = 'no'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para actualizar nombre de archivo
    public function actualizarNombreArchivo($idArchivo, $nombre, $nuevaRuta){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET nombre_archivo = '$nombre', ruta_archivo = '$nuevaRuta' 
            WHERE id_archivo = $idArchivo  
                AND estado_papelera = 'no'"
        );
    }

    // Método para verificar si existe un archivo en una ruta
    public function verificarRutaArchivo($idArchivo,$ruta_archivo){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE ruta_archivo = '$ruta_archivo' 
                AND estado_papelera = 'no' 
                AND id_archivo != $idArchivo"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // FUNCIONES PARA CAMBIAR NOMBRE EN LA PAPELERA
    
    // Método para actualizar nombre y ruta de archivo en la papelera
    public function actualizarNombreArchivoPapelera($idArchivo, $nombre, $nuevaRuta, $nuevaRutaPapelera){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET nombre_archivo = '$nombre', ruta_archivo = '$nuevaRuta', ruta_archivo_papelera = '$nuevaRutaPapelera'
            WHERE id_archivo = $idArchivo 
                AND estado_papelera = 'si'"
        );
    }


    // FUNCIONES PARA RESTAURAR UN ARCHIVO

    // Método para restaurar un archivo actualizando sus datos, moviéndolo de la papelera
    public function restaurarArchivo($idArchivo){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET id_archivo_papelera = null, ruta_archivo_papelera = null, estado_papelera = 'no', fecha_papelera = null 
            WHERE id_archivo = $idArchivo 
                AND estado_papelera = 'si'"
        );
    }
    
    // Método para actualizar ruta de archivo
    public function actualizarRutaArchivo($idArchivo, $ruta_archivo,$idCarpetaPadre){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = '$ruta_archivo', id_carpeta = $idCarpetaPadre 
            WHERE id_archivo = $idArchivo"
        );
    }
    
    // Método para obtener ruta de donde se va a restaurar el archivo
    public function ex_obtenerRutaRestauracionArchivo($idArchivo, $rutaRaiz) {
        $conexion = new Conexion();
        $con = $conexion->con;

        $consulta = $con->query("CALL EncontrarRutaRestauracionArchivo($idArchivo, '$rutaRaiz', @ruta);");

        $resultado = $con->query("SELECT @ruta AS ruta_restauracion");

        $fila = $resultado->fetch_assoc();

        return $fila;
    }

    // MOVER

    // Método para verificar si hay un archivo con el mismo nombre en la carpeta donde se va a mover el archivo
    public function verificarNombreArchivoAMover($ruta_carpeta, $nombre_carpeta){
        $consulta = $this->obtenerDatos(
            "SELECT ruta_archivo 
            FROM archivos 
            WHERE ruta_archivo = '$ruta_carpeta/$nombre_carpeta' 
                AND estado_papelera = 'no'"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }
    
    // Método para mover un archivo a otra carpeta, actualizando su ruta
    public function moverArchivo($idArchivo, $nuevaRuta, $idCarpetaPadre){
        return $this->ejecutarConsulta(
            "UPDATE archivos 
            SET ruta_archivo = '$nuevaRuta', id_carpeta = $idCarpetaPadre 
            WHERE id_archivo = $idArchivo"
        );
    }

    // FUNCIONES PARA COMPARTIR ARCHIVOS
    
    // Método para construir estructura de lector (persona a la que se le comparte un elemento)
    public function agregarLector($email, $nombre, $apellido, $img, $idArchivo){
        $estructuraLector = '<div class="compartido-lector"  data-email="'.$email.'">
            <div class="compartido-lector-izq" data-email="'.$email.'" data-id="'.$idArchivo.'" data-usuario="'.$nombre.'">
                <img src="'.$img.'" alt="" class="icon-usuario compartido-img-lector">
                <div class="compartido-lector-izq-datos">
                    <h3 class="compartido-nombre-lector">'.$nombre.' '.$apellido.'</h3>
                    <p class="compartido-email-lector">'.$email.'</p>
                </div> 
            </div>
            <div class="compartido-lector-der">
                <button class="btn_quitar_acceso" data-usuario="'.$nombre.'" data-id="'.$idArchivo.'">Quitar acceso</button>
            </div>
        </div>';

        return $estructuraLector;
    }
    
    // Método para obtener los usuarios a los que se les ha compartido un archivo
    public function obtenerIdLectores($idArchivo){
        return $this->obtenerDatos(
            "SELECT id_receptor 
            FROM compartidos 
            WHERE id_archivo = $idArchivo"
        );
    }
    
    // Método para obtener los datos de los usuarios a los que se les ha compartido un archivo
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
    
    // Método para obtener los datos de un usuario al que se le ha compartido un archivo
    public function obtenerDatosLector($idLector){
        return $this->obtenerDatos(
            "SELECT nombre, apellido, correo, ruta_imagen 
            FROM usuarios 
            WHERE id_usuario = $idLector"
        );
    }
    
    // Método para validar si un usuario es propietario de un archivo en concreto
    public function validarPropietarioArchivo($idPropietario,$idArchivo){
        $consulta = $this->obtenerDatos(
            "SELECT id_archivo 
            FROM archivos 
            WHERE id_archivo = $idArchivo 
                AND id_usuario = $idPropietario"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }
    
    // Método para validar si a un usuario en específico se la ha compartido un archivo
    public function validarReceptor($idPropietario,$idReceptor,$idArchivo){
        $consulta = $this->obtenerDatos(
            "SELECT id_compartido 
            FROM compartidos 
            WHERE id_archivo = $idArchivo 
                AND id_propietario = $idPropietario 
                AND id_receptor = $idReceptor"
        );

        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }
    
    // Método para compartir un archivo con un usuario
    public function compartirArchivo($idArchivo,$idPropietario,$idReceptor,$fechaCompartido){
        return $this->ejecutarConsulta("INSERT INTO compartidos VALUES (NULL, $idArchivo, NULL, $idPropietario, $idReceptor, '$fechaCompartido');");
    }
    
    // Método para obtener los archivos compartidos en la raiz
    public function obtenerArchivosCompartidosRaiz($idReceptor){
        return $this->obtenerDatos(
            "SELECT m.id, a.nombre_archivo, u.nombre,a.ruta_icono,a.tamanio,a.fecha_creacion 
            FROM archivos a 
            LEFT JOIN carpetas c ON a.id_carpeta = c.id_carpeta 
            JOIN id_mapping m ON a.id_archivo = m.id_archivo 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            WHERE a.estado_papelera = 'no' 
                AND ((a.id_carpeta IS NULL 
                AND EXISTS (SELECT 1 
                            FROM compartidos sa 
                            WHERE sa.id_archivo = a.id_archivo 
                                AND sa.id_receptor = $idReceptor)) 
                                OR (a.id_carpeta IS NOT NULL 
                                    AND NOT EXISTS (SELECT 1 
                                    FROM compartidos s 
                                    WHERE s.id_carpeta = c.id_carpeta AND s.id_receptor = $idReceptor) 
                                        AND EXISTS (SELECT 1 
                                                    FROM compartidos sa 
                                                    WHERE sa.id_archivo = a.id_archivo 
                                                    AND sa.id_receptor = $idReceptor)));"
        );
    }

    // Método para obtener los datos de los archivos hijos compartidos de una carpeta compartida
    public function obtenerArchivosCompartidos($rutaCarpeta,$idReceptor){
        return $this->obtenerDatos(
            "SELECT m.id, a.nombre_archivo, u.nombre,a.ruta_icono,a.tamanio,a.fecha_creacion 
            FROM archivos a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            JOIN id_mapping m ON a.id_archivo = m.id_archivo 
            JOIN compartidos s ON a.id_archivo = s.id_archivo 
            WHERE a.ruta_archivo LIKE '$rutaCarpeta/%' 
                AND a.ruta_archivo NOT LIKE '$rutaCarpeta/%/%' 
                AND a.estado_papelera = 'no' 
                AND s.id_receptor = $idReceptor;"
        );
    }

     // Método para validar si un archivo ha sido compartido a un usuario en específico
     public function esArchivoCompartido($idArchivo,$idReceptor){
        $consulta = $this->obtenerDatos(
            "SELECT id_archivo
            FROM compartidos 
            WHERE id_archivo = $idArchivo 
                AND id_receptor = $idReceptor"
        );
        
        if(count($consulta) === 0){
            return false;
        } else {
            return true;
        }
    }

    // Método para quitar acceso a los archivos hijos de una carpeta, eliminando los registros de la tabla
    public function quitarAccesoArchivo($idPropietario,$idReceptor,$idArchivo){
        return $this->ejecutarConsulta(
            "DELETE FROM compartidos 
            WHERE id_archivo = $idArchivo 
                AND id_propietario = $idPropietario 
                AND id_receptor = $idReceptor"
        );
    }

    // Método para eliminar compartidos de un archivo
    public function eliminarCompartirArchivo($idArchivo){
        return $this->ejecutarConsulta(
            "DELETE FROM compartidos 
            WHERE id_archivo = $idArchivo;"
        );
    }

    // Método para obtener los datos de un archivo en específico
    public function obtenerDatosArchivo($rutaArchivo){
        return $this->obtenerDatos(
            "SELECT a.nombre_archivo,u.nombre,u.apellido,a.fecha_creacion,a.tamanio 
            FROM archivos a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            WHERE a.ruta_archivo = '$rutaArchivo';"
        );
    }

}

?>