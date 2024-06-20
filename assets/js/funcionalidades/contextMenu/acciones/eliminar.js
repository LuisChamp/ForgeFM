import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza una solicitud al servidor para eliminar un elemento
 * @param {string} idRuta - El ID de la ruta del elemento a eliminar
 * @param {string} tipo - El tipo de elemento a eliminar (carpeta o archivo)
 * @param {string} idRutaPapelera - El ID de la ruta de la papelera
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function eliminarPeticion(idRuta, tipo, idRutaPapelera) {
    try {
        let url = "";
        let formData = new FormData();

        // Se define la url si es una carpeta o archivo
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/papelera/acciones/eliminar_carpeta_papelera.php`;
        } else {
            url = `mvc/controllers/archivos/papelera/acciones/eliminar_archivo_papelera.php`;
        }

        // Agregar datos que se van a enviar
        formData.append("idRuta", idRuta);
        formData.append("idRutaPapelera", idRutaPapelera);

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
 * Función asíncrona que elimina un elemento de la papelera
 * @param {string} idRuta - El ID de la ruta del elemento a eliminar de la papelera
 * @param {string} tipo - El tipo de elemento a eliminar (carpeta o archivo)
 * @param {string} idRutaPapelera - El ID de la ruta de la papelera
 */
export async function eliminarDePapelera(idRuta, tipo, idRutaPapelera) {
    try {
        // Realiza la solicitud al servidor para eliminar el elemento de la papelera
        const respuesta = await eliminarPeticion(idRuta, tipo, idRutaPapelera);

        // Muestra la notificación con la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la eliminación fue exitosa y es una carpeta, elimina el elemento del DOM
        if (respuesta.respuesta === "exito" && respuesta.tipo === "carpeta") {
            const carpetaAEliminar = document.querySelector(
                `.carpeta[data-id="${idRuta}"][data-id_papelera="${idRutaPapelera}"]`
            );
            const padreCarpeta = carpetaAEliminar.parentNode;
            padreCarpeta.removeChild(carpetaAEliminar);
        }
        // Si la eliminación fue exitosa y es un archivo, elimina el elemento del DOM
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
