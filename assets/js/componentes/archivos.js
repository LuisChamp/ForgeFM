import { fetchDataBody } from './../funcionalidades/fetchData.js';

/**
 * Función asincrónica para obtener la lista de archivos
 * @param {string} idRuta - La ruta de la carpeta de la que se desean obtener los archivos
 */
export async function obtenerArchivos(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para obtener la lista de archivos en la ruta especificada
        const data = await fetchDataBody(`mvc/controllers/archivos/inicio/obtener_archivos.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para obtener la lista de archivos en la papelera
 * @param {string} idRuta - La ruta de la carpeta de la que se desean obtener los archivos de la papelera
 */
export async function obtenerArchivosPapelera(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para obtener la lista de archivos en la ruta especificada
        const data = await fetchDataBody(`mvc/controllers/archivos/papelera/obtener_archivos_papelera.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para obtener la lista de archivos compartidos
 * @param {string} idRuta - La ruta de la carpeta de la que se desean obtener los archivos compartidos
 */
export async function obtenerArchivosCompartidos(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para obtener la lista de archivos en la ruta especificada
        const data = await fetchDataBody(`mvc/controllers/archivos/compartidos/obtener_archivos_compartidos.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función para mostrar la lista de archivos en la interfaz de usuario
 * @param {string} archivos - El contenido HTML que representa la lista de archivos a mostrar
 * @param {HTMLElement} $contenedorArchivos - El elemento contenedor donde se mostrarán los archivos
 */
export async function mostrarArchivos(archivos, $contenedorArchivos) {
    // Asignar el contenido HTML de la lista de archivos al contenedor especificado
    $contenedorArchivos.innerHTML = archivos;
}
