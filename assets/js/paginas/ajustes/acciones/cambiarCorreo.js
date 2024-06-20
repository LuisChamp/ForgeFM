import { fetchDataBody } from "../../../funcionalidades/fetchData.js";
import { mostrarNotificacion } from "../../../funcionalidades/notificaciones/notificaciones.js";

/**
 * Función asíncrona que envía una petición para cambiar el correo electrónico del usuario
 * @param {string} nuevoCorreo - El nuevo correo electrónico
 * @returns {Promise<Object>} - Una promesa que devuelve la respuesta de la petición
 */
export async function cambiarCorreoPeticion(nuevoCorreo) {
    try {
        // URL del controlador para cambiar el correo
        let url = "mvc/controllers/ajustes/cambiar_correo.php";
        
        let formData = new FormData();
        // Se agrega el dato que se va a enviar
        formData.append("nuevoCorreo", nuevoCorreo);

        // Realiza la petición
        const data = await fetchDataBody(url, formData);

        // Devuelve la respuesta de la petición
        return data;
    } catch (error) {
        // Muestra el error en la consola
        console.error(error);
    }
}

/**
 * Función asíncrona que gestiona el proceso de cambiar el correo electrónico del usuario,
 * incluyendo la actualización de la interfaz de usuario
 * @param {string} nuevoCorreo - El nuevo correo electrónico
 */
export async function cambiarCorreo(nuevoCorreo) {
    try {
        // Llama a la función para enviar la petición de cambio de correo
        const respuesta = await cambiarCorreoPeticion(nuevoCorreo);

        // Muestra una notificación basada en la respuesta de la petición
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la respuesta indica éxito, actualiza el contenido de la interfaz de usuario con el nuevo correo
        if (respuesta.respuesta === "exito") {
            const correo = document.querySelector(`.ajustes_email`);
            const correoActual = document.querySelector(".modal_correo_actual");

            // Actualiza el contenido del correo en los elementos del DOM correspondientes
            correo.textContent = respuesta.correo;
            correoActual.textContent = respuesta.correo;
        }
    } catch (error) {
        // Muestra el error en la consola
        console.error(error);
    }
}
