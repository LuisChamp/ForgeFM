import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza una solicitud al servidor para mover un elemento
 * @param {string} idRutaOrigen - El ID de la ruta de origen del elemento
 * @param {string} tipo - El tipo de elemento a mover (carpeta o archivo)
 * @param {string} idRutaDestino - El ID de la ruta de destino del elemento
 * @returns {Promise<Object>} - La respuesta del servidor
 */
async function moverPeticion(idRutaOrigen, tipo, idRutaDestino) {
    try {
        let url = "";
        let formData = new FormData();

        // Definir url del controlador en base al tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/inicio/acciones/mover_carpeta.php`;
        } else {
            url = `mvc/controllers/archivos/inicio/acciones/mover_archivo.php`;
        }

        // Agregar datos que se van a enviar
        formData.append("idRutaOrigen", idRutaOrigen);
        formData.append("idRutaDestino", idRutaDestino);

        // Realizar solicitud al servidor
        const data = await fetchDataBody(url, formData);

        // Obtener respuesta
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que mueve un elemento
 * @param {string} idRutaOrigen - El ID de la ruta de origen del elemento
 * @param {string} tipo - El tipo de elemento a mover (carpeta o archivo)
 * @param {string} idRutaDestino - El ID de la ruta de destino del elemento
 */
export async function mover(idRutaOrigen, tipo, idRutaDestino) {
    try {
        // Realiza la solicitud al servidor para mover el elemento
        const respuesta = await moverPeticion(idRutaOrigen, tipo, idRutaDestino);

        // Muestra la notificación con la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la operación fue exitosa y es una carpeta, elimina el elemento del DOM
        if (respuesta.tipo === "carpeta" && respuesta.respuesta === "exito") {
            const carpetaAEliminar = document.querySelector(
                `.carpeta[data-id="${idRutaOrigen}"]`
            );
            const padreCarpeta = carpetaAEliminar.parentNode;
            padreCarpeta.removeChild(carpetaAEliminar);
        }
        // Si la operación fue exitosa y es un archivo, elimina el elemento del DOM
        else if (respuesta.tipo === "archivo" && respuesta.respuesta === "exito") {
            const archivoAEliminar = document.querySelector(
                `.archivo[data-id="${idRutaOrigen}"]`
            );
            const padreArchivo = archivoAEliminar.parentNode;
            padreArchivo.removeChild(archivoAEliminar);
        }
    } catch (error) {
        console.error(error);
    }
}

/**
 * Función asíncrona que realizar la acción para mover un elemento
 * @param {string} idRutaDestino - El ID de la ruta de destino del elemento
 */
export async function moverAccion(idRutaDestino) {
    const contextMenu = document.querySelector("#context-menu");
    const menu = contextMenu.querySelector(".menu");
    const idRutaOrigen = menu.dataset.id;
    const tipo = menu.dataset.tipo;

    mover(idRutaOrigen, tipo, idRutaDestino);
}
