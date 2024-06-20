import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza una solicitud al servidor para mover un elemento a la papelera
 * @param {string} idRuta - El ID de la ruta del elemento a mover
 * @param {string} tipo - El tipo de elemento a mover (carpeta o archivo)
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function moverPapeleraPeticion(idRuta, tipo) {
    try {
        let url = "";
        let formData = new FormData();

        // Determinar la URL según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/inicio/acciones/mover_carpeta_papelera.php`;
        } else {
            url = `mvc/controllers/archivos/inicio/acciones/mover_archivo_papelera.php`;
        }

        // Agregar el dato que se va a enviar
        formData.append("idRuta", idRuta);

        // Realizar la solicitud al servidor
        const data = await fetchDataBody(url, formData);

        // Obtener respuesta
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que mueve un elemento a la papelera
 * @param {string} idRuta - El ID de la ruta del elemento a mover
 * @param {string} tipo - El tipo de elemento a mover (carpeta o archivo)
 */
export async function moverAPapelera(idRuta, tipo) {
    try {
        // Realiza la solicitud al servidor para mover el elemento a la papelera
        const respuesta = await moverPapeleraPeticion(idRuta, tipo);

        // Muestra la notificación con la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la operación fue exitosa y es una carpeta, elimina el elemento del DOM
        if (respuesta.tipo === "carpeta" && respuesta.respuesta === "exito") {
            const carpetaAEliminar = document.querySelector(
                `.carpeta[data-id="${idRuta}"]`
            );
            const padreCarpeta = carpetaAEliminar.parentNode;
            padreCarpeta.removeChild(carpetaAEliminar);
        }
        // Si la operación fue exitosa y es un archivo, elimina el elemento del DOM
        else if (respuesta.tipo === "archivo" && respuesta.respuesta === "exito") {
            const archivoAEliminar = document.querySelector(
                `.archivo[data-id="${idRuta}"]`
            );
            const padreArchivo = archivoAEliminar.parentNode;
            padreArchivo.removeChild(archivoAEliminar);
        }
    } catch (error) {
        console.error(error);
    }
}
