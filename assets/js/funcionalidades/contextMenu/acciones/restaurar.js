import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza una solicitud al servidor para restaurar un elemento desde la papelera
 * @param {string} idRuta - El ID de la ruta del elemento a restaurar
 * @param {string} tipo - El tipo de elemento a restaurar (carpeta o archivo)
 * @param {string} idRutaPapelera - El ID de la ruta de la papelera desde donde se restaura el elemento
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function restaurarPeticion(idRuta, tipo, idRutaPapelera) {
    try {
        let url = "";
        let formData = new FormData();

        // Determinar la URL según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/papelera/acciones/restaurar_carpeta_papelera.php`;
        } else {
            url = `mvc/controllers/archivos/papelera/acciones/restaurar_archivo_papelera.php`;
        }

        // Agregar los datos que se van a enviar
        formData.append("idRuta", idRuta);
        formData.append("idRutaPapelera", idRutaPapelera);
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
 * Función asíncrona que restaura un elemento desde la papelera
 * @param {string} idRuta - El ID de la ruta del elemento a restaurar
 * @param {string} tipo - El tipo de elemento a restaurar (carpeta o archivo)
 * @param {string} idRutaPapelera - El ID de la ruta de la papelera desde donde se restaura el elemento
 */
export async function restaurar(idRuta, tipo, idRutaPapelera) {
    try {
        // Realiza la solicitud al servidor para restaurar el elemento desde la papelera
        const respuesta = await restaurarPeticion(idRuta, tipo, idRutaPapelera);

        // Muestra la notificación con la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la operación fue exitosa y es una carpeta, elimina el elemento del DOM
        if (respuesta.respuesta === "exito" && respuesta.tipo === "carpeta") {
            const carpetaAEliminar = document.querySelector(
                `.carpeta[data-id="${idRuta}"][data-id_papelera="${idRutaPapelera}"]`
            );
            const padreCarpeta = carpetaAEliminar.parentNode;
            padreCarpeta.removeChild(carpetaAEliminar);
        }
        // Si la operación fue exitosa y es un archivo, elimina el elemento del DOM
        else if (respuesta.respuesta === "exito" && respuesta.tipo === "archivo") {
            const archivoAEliminar = document.querySelector(
                `.archivo[data-id="${idRuta}"][data-id_papelera="${idRutaPapelera}"]`
            );
            const padreArchivo = archivoAEliminar.parentNode;
            padreArchivo.removeChild(archivoAEliminar);
        }
    } catch (error) {
        console.error(error);
    }
}
