import { fetchDataBody, fetchData } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona que realiza la solicitud para compartir una carpeta o archivo con otro usuario
 * @param {string} nombrePropietario - Nombre del propietario del archivo o carpeta
 * @param {string} emailReceptor - Correo electrónico del usuario con el que se desea compartir
 * @param {string} accion - Acción a realizar, puede ser "agregar" o "quitar"
 * @param {string} idRuta - ID de la ruta del archivo o carpeta
 * @param {string} tipo - Tipo de elemento, puede ser "carpeta" o "archivo"
 * @returns {Promise<Object>} - La respuesta del servidor
 */
async function compartirPeticion(nombrePropietario, emailReceptor, accion, idRuta, tipo) {
    try {
        let url = "";

        // Determinar la URL según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/inicio/acciones/compartir_carpeta.php`;
        } else {
            url = `mvc/controllers/archivos/inicio/acciones/compartir_archivo.php`;
        }

        // Agregar los datos que se van a enviar
        let formData = new FormData();
        formData.append("nombrePropietario", nombrePropietario);
        formData.append("emailReceptor", emailReceptor);
        formData.append("idRuta", idRuta);
        formData.append("accion", accion);

        // Realizar la solicitud al servidor
        const data = await fetchDataBody(url, formData);

        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que comparte una carpeta o archivo con otro usuario y actualiza la interfaz de usuario
 * @param {string} nombrePropietario - Nombre del propietario del archivo o carpeta
 * @param {string} emailReceptor - Correo electrónico del usuario con el que se desea compartir
 * @param {string} accion - Acción a realizar, puede ser "agregar" o "quitar"
 * @param {string} idRuta - ID de la ruta del archivo o carpeta
 * @param {string} tipo - Tipo de elemento, puede ser "carpeta" o "archivo"
 */
async function compartir(nombrePropietario, emailReceptor, accion, idRuta, tipo) {
    try {
        // Realizar la solicitud para compartir
        const respuesta = await compartirPeticion(nombrePropietario, emailReceptor, accion, idRuta, tipo);

        // Seleccionar el contenedor de lectores
        const lectores = document.querySelector(".lectores");

        // Mostrar notificación
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Agregar lector a la lista de personas con acceso a la carpeta
        if (respuesta.respuesta === "exito" && respuesta.tipo === "carpeta" && accion === "agregar") {
            lectores.innerHTML += respuesta.estructura;
        }

        // Quitar lector de la carpeta
        if (respuesta.respuesta === "exito" && respuesta.tipo === "carpeta" && accion === "quitar") {
            const lectorAQuitar = document.querySelector(`.compartido-lector[data-email="${respuesta.email}"]`);
            lectores.removeChild(lectorAQuitar);
        }

        // Agregar lector a la lista de personas con acceso al archivo
        if (respuesta.respuesta === "exito" && respuesta.tipo === "archivo" && accion === "agregar") {
            lectores.innerHTML += respuesta.estructura;
        }

        // Quitar lector del archivo
        if (respuesta.respuesta === "exito" && respuesta.tipo === "archivo" && accion === "quitar") {
            const lectorAQuitar = document.querySelector(`.compartido-lector[data-email="${respuesta.email}"]`);
            lectores.removeChild(lectorAQuitar);
        }
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asíncrona para gestionar la acción de compartir una carpeta o archivo con otro usuario
 * @param {string} nombrePropietario - Nombre del propietario del archivo o carpeta
 * @param {string} emailReceptor - Correo electrónico del usuario con el que se desea compartir
 * @param {string} accion - Acción a realizar, puede ser "agregar" o "quitar"
 */
export async function compartirAccion(nombrePropietario, emailReceptor, accion) {
    const contextMenu = document.querySelector("#context-menu");
    const menu = contextMenu.querySelector(".menu");
    const idRuta = menu.dataset.id;
    const tipo = menu.dataset.tipo;

    // Realizar la acción de compartir
    compartir(nombrePropietario, emailReceptor, accion, idRuta, tipo);
}

/**
 * Función asíncrona que obtiene los correos electrónicos de los usuarios
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function obtenerCorreos() {
    try {
        let url = `mvc/controllers/usuarios/obtener_correos.php`;

        // Realizar la solicitud al servidor
        const data = await fetchData(url);

        // Devolver los correos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
        throw error;
    }
}
