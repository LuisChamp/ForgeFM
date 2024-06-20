import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza una petición para descargar contenido, como carpetas o archivos
 * @param {string} idRutasCarpetas - Los IDs de las carpetas a descargar
 * @param {string} idRutasArchivos - Los IDs de los archivos a descargar
 * @returns {Promise<Object>} - Los datos obtenidos en la respuesta
 */
export async function descargarContenidoPeticion(idRutasCarpetas, idRutasArchivos) {
    try {
        // URL del controlador para descargar contenido
        let url = "mvc/controllers/modoEdicion/inicio/descargar_contenido.php";
        // Crea un objeto FormData para enviar los IDs de carpetas y archivos
        let formData = new FormData();
        formData.append("idRutasCarpetas", idRutasCarpetas);
        formData.append("idRutasArchivos", idRutasArchivos);
        // Realiza la solicitud de descarga y obtiene los datos de la respuesta
        const data = await fetchDataBody(url, formData);
        return data;
    } catch (error) {
        // Maneja cualquier error que ocurra durante la solicitud
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que descarga el contenido especificado por los IDs de carpetas y archivos
 * @param {string} idRutasCarpetas - Los IDs de las carpetas a descargar
 * @param {string} idRutasArchivos - Los IDs de los archivos a descargar
 */
export async function descargarContenido(idRutasCarpetas, idRutasArchivos) {
    try {
        // Realiza la petición para descargar el contenido
        const respuesta = await descargarContenidoPeticion(idRutasCarpetas, idRutasArchivos);
        // Muestra una notificación basada en la respuesta obtenida
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la descarga fue exitosa, crea un enlace y descarga automáticamente el archivo ZIP
        if (respuesta.respuesta === "exito") {
            const link = document.createElement('a');
            link.href = respuesta.url;
            // Establece el atributo de descarga con el nombre del archivo
            link.setAttribute('download', respuesta.url.split('/').pop());
            // Agrega el enlace al cuerpo del documento, lo ejecuta y luego lo elimina
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    } catch (error) {
        // Maneja cualquier error que ocurra durante el proceso de descarga
        console.error(error);
    }
}
