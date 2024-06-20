import { fetchDataBody } from "../../../funcionalidades/fetchData.js";
import { mostrarNotificacion } from "../../../funcionalidades/notificaciones/notificaciones.js";

/**
 * Función asíncrona que envía una petición para eliminar la cuenta del usuario
 * @param {string} confirmacion - La confirmación de eliminación de cuenta
 * @returns {Promise<Object>} - Una promesa que devuelve la respuesta de la petición
 */
async function eliminarCuentaPeticion(confirmacion) {
    try {
        // URL del controlador para eliminar la cuenta
        let url = "mvc/controllers/ajustes/eliminar_cuenta.php";
        
        let formData = new FormData();
        // Enviar dato de confirmación
        formData.append("confirmacion", confirmacion);

        // Se realiza la petición
        const data = await fetchDataBody(url, formData);

        // Se devuelve la respuesta de la petición
        return data;
    } catch (error) {
        // Muestra el error en la consola
        console.error(error);
    }
}

/**
 * Función asícrona que gestiona el proceso de eliminar la cuenta del usuario,
 * incluyendo la notificación del resultado y la redirección al cerrar sesión
 * @param {string} confirmacion - La confirmación de eliminación de cuenta
 */
export async function eliminarCuenta(confirmacion) {
    try {
        // Llama a la función para enviar la petición de eliminación de cuenta
        const respuesta = await eliminarCuentaPeticion(confirmacion);

        // Muestra una notificación basada en la respuesta de la petición
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Si la respuesta indica éxito, limpia el almacenamiento de sesión y redirige al usuario
        if (respuesta.respuesta === "exito") {
            sessionStorage.clear();
            // Redirecciona al usuario al archivo donde se cierra la sesión
            window.location.href = "mvc/controllers/usuarios/credenciales/cerrar_sesion.php?sesion=true&eliminar=true";
        }
    } catch (error) {
        // Muestra el error en la consola
        console.error(error);
    }
}
