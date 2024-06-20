import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza una solicitud al servidor para descargar una carpeta
 * @param {string} idRuta - El ID de la ruta de la carpeta a descargar
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function descargarPeticion(idRuta) {
    try {
        let url = "mvc/controllers/carpetas/inicio/acciones/descargar_carpeta.php";
        let formData = new FormData();

        // Agregar el dato que se va a enviar
        formData.append("idRuta", idRuta);

        // Se realiza la solicitud
        const data = await fetchDataBody(url, formData);

        // Se obtiene la respuesta
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que descarga un archivo o carpeta del servidor
 * @param {string} idRuta - El ID de la ruta del archivo o carpeta a descargar
 * @param {string} tipo - El tipo de elemento a descargar (archivo o carpeta)
 */
export async function descargar(idRuta, tipo) {
    try {
        // Si es un archivo, redirige al enlace de descarga directa
        if (tipo === "archivo") {
            window.location.href = `mvc/controllers/archivos/inicio/acciones/descargar_archivo.php?idRuta=${idRuta}`;
            return;
        }

        // Si es una carpeta, realiza la solicitud al servidor
        const respuesta = await descargarPeticion(idRuta);

        // Muestra la notificación con la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la descarga fue exitosa y es una carpeta, crea un enlace y se descarga el archivo ZIP automáticamente
        if (respuesta.respuesta === "exito" && tipo === "carpeta") {
            const link = document.createElement('a');
            link.href = respuesta.url;
            link.setAttribute('download', respuesta.url.split('/').pop());
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    } catch (error) {
        console.error(error);
    }
}

/**
 * Función asíncrona que realiza una solicitud al servidor para descargar una carpeta compartida
 * @param {string} idRuta - El ID de la ruta de la carpeta compartida a descargar
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function descargarCompartidosPeticion(idRuta) {
    try {
        let url = "mvc/controllers/carpetas/compartidos/acciones/descargar_compartidos.php";
        let formData = new FormData();

        // Agregar dato que se va a enviar
        formData.append("idRuta", idRuta);

        // Realizar solicitud al servidor
        const data = await fetchDataBody(url, formData);

        // Obtener respuesta del servidor
        return data;
    } catch (error) {
        console.error(error);
    }
}

/**
 * Descarga un archivo o carpeta compartida del servidor
 * @param {string} idRuta - El ID de la ruta del archivo o carpeta compartida a descargar
 * @param {string} tipo - El tipo de elemento a descargar (archivo o carpeta)
 */
export async function descargarCompartidos(idRuta, tipo) {
    try {
        // Si es un archivo, redirige al enlace de descarga directa
        if (tipo === "archivo") {
            window.location.href = `mvc/controllers/archivos/inicio/acciones/descargar_archivo.php?idRuta=${idRuta}`;
            return;
        }

        // Si es una carpeta, realiza la solicitud al servidor
        const respuesta = await descargarCompartidosPeticion(idRuta);

        // Muestra la notificación con la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la descarga fue exitosa y es una carpeta, crea un enlace y se descarga el archivo ZIP automáticamente
        if (respuesta.respuesta === "exito" && tipo === "carpeta") {
            const link = document.createElement('a');
            link.href = respuesta.url;
            link.setAttribute('download', respuesta.url.split('/').pop());
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    } catch (error) {
        console.error(error);
    }
}
