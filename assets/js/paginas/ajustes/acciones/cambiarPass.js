import { fetchDataBody } from "../../../funcionalidades/fetchData.js";
import { mostrarNotificacion } from "../../../funcionalidades/notificaciones/notificaciones.js";

/**
 * Función asíncrona que envía una petición para cambiar la contraseña del usuario
 * @param {string} passActual - La contraseña actual del usuario
 * @param {string} passNueva - La nueva contraseña
 * @param {string} passNuevaConf - La confirmación de la nueva contraseña
 * @returns {Promise<Object>} - Una promesa que devuelve la respuesta de la petición
 */
async function cambiarPassPeticion(passActual, passNueva, passNuevaConf) {
    try {
        // URL del controlador para cambiar la contraseña
        let url = "mvc/controllers/ajustes/cambiar_pass.php";

        let formData = new FormData();
        // Se agregan los datos que se van a enviar
        formData.append("passActual", passActual);
        formData.append("passNueva", passNueva);
        formData.append("passNuevaConf", passNuevaConf);

        // Se realiza la petición
        const data = await fetchDataBody(url, formData);

        // Devuelve la respuesta de la petición
        return data;
    } catch (error) {
        // Muestra el error en la consola
        console.error(error);
    }
}

/**
 * Función asíncrona que gestiona el proceso de cambiar la contraseña del usuario,
 * incluyendo la notificación del resultado
 * @param {string} passActual - La contraseña actual del usuario
 * @param {string} passNueva - La nueva contraseña
 * @param {string} passNuevaConf - La confirmación de la nueva contraseña
 */
export async function cambiarPass(passActual, passNueva, passNuevaConf) {
    try {
        // Llama a la función para enviar la petición de cambio de contraseña
        const respuesta = await cambiarPassPeticion(passActual, passNueva, passNuevaConf);

        // Muestra una notificación basada en la respuesta de la petición
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

    } catch (error) {
        // Muestra el error en la consola
        console.error(error);
    }
}
