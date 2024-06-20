import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función para realizar la solicitud de cambio de nombre de una carpeta o archivo
 * @param {string} idRuta - ID de la ruta del archivo o carpeta
 * @param {string} tipo - Tipo de elemento, puede ser "carpeta" o "archivo"
 * @param {string} nombre - Nuevo nombre para el archivo o carpeta
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function cambiarNombrePeticion(idRuta, tipo, nombre) {
    try {
        let url = "";
        let formData = new FormData();

        // Determinar la URL según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/inicio/acciones/cambiar_nombre_carpeta.php`;
        } else {
            url = `mvc/controllers/archivos/inicio/acciones/cambiar_nombre_archivo.php`;
        }

        // Agregar los datos que se van a enviar
        formData.append("idRuta", idRuta);
        formData.append("nombre", nombre);

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
 * Función para realizar la solicitud de cambio de nombre de una carpeta o archivo en la papelera
 * @param {string} idRuta - ID de la ruta del archivo o carpeta
 * @param {string} tipo - Tipo de elemento, puede ser "carpeta" o "archivo"
 * @param {string} nombre - Nuevo nombre para el archivo o carpeta
 * @param {string} idRutaPapelera - ID de la ruta en la papelera
 * @returns {Promise<Object>} - La respuesta del servidor
 */
export async function cambiarNombrePapeleraPeticion(idRuta, tipo, nombre, idRutaPapelera) {
    try {
        let url = "";
        let formData = new FormData();

        // Determinar la URL según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/papelera/acciones/cambiar_nombre_carpeta_papelera.php`;
        } else {
            url = `mvc/controllers/archivos/papelera/acciones/cambiar_nombre_archivo_papelera.php`;
        }

        // Agregar los datos que se van a enviar
        formData.append("idRuta", idRuta);
        formData.append("nombre", nombre);
        formData.append("idRutaPapelera", idRutaPapelera);

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
 * Función para cambiar el nombre de una carpeta o archivo y actualizar el DOM
 * @param {string} idRuta - ID de la ruta del archivo o carpeta
 * @param {string} tipo - Tipo de elemento, puede ser "carpeta" o "archivo"
 * @param {string} nombre - Nuevo nombre para el archivo o carpeta
 */
export async function cambiarNombre(idRuta, tipo, nombre) {
    try {
        // Realizar la solicitud para cambiar el nombre
        const respuesta = await cambiarNombrePeticion(idRuta, tipo, nombre);

        // Mostrar una notificación con el resultado
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Actualizar el nombre en el DOM si la respuesta fue exitosa
        if (respuesta.respuesta === "exito" && respuesta.tipo === "carpeta") {
            const carpetaSeleccionada = document.querySelector(`.carpeta p[data-id="${idRuta}"]`);
            carpetaSeleccionada.textContent = nombre;
        } else if (respuesta.respuesta === "exito" && respuesta.tipo === "archivo") {
            const archivoSeleccionado = document.querySelector(`.archivo p[data-id="${idRuta}"]`);
            archivoSeleccionado.textContent = nombre;
        }
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función para cambiar el nombre de una carpeta o archivo en la papelera y actualizar el DOM.
 * @param {string} idRuta - ID de la ruta del archivo o carpeta.
 * @param {string} tipo - Tipo de elemento, puede ser "carpeta" o "archivo".
 * @param {string} nombre - Nuevo nombre para el archivo o carpeta.
 * @param {string} idRutaPapelera - ID de la ruta en la papelera.
 */
export async function cambiarNombrePapelera(idRuta, tipo, nombre, idRutaPapelera) {
    try {
        // Realizar la solicitud para cambiar el nombre en la papelera
        const respuesta = await cambiarNombrePapeleraPeticion(idRuta, tipo, nombre, idRutaPapelera);

        // Mostrar una notificación con el resultado
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        // Actualizar el nombre en el DOM si la respuesta fue exitosa
        if (respuesta.respuesta === "exito" && respuesta.tipo === "carpeta") {
            const carpetaSeleccionada = document.querySelector(`.carpeta[data-id_papelera="${idRutaPapelera}"] p[data-id="${idRuta}"]`);
            carpetaSeleccionada.textContent = nombre;
        } else if (respuesta.respuesta === "exito" && respuesta.tipo === "archivo") {
            const archivoSeleccionado = document.querySelector(`.archivo[data-id_papelera="${idRutaPapelera}"] p[data-id="${idRuta}"]`);
            archivoSeleccionado.textContent = nombre;
        }
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función para gestionar la acción de cambiar el nombre de una carpeta o archivo
 */
export function cambiarNombreAccion() {
    const contextMenu = document.querySelector("#context-menu");
    const menu = contextMenu.querySelector(".menu");
    const inputCambiarNombre = document.querySelector(".input_cambiar_nombre");
    const nuevoNombre = inputCambiarNombre.value;
    const idRuta = menu.dataset.id;
    const tipo = menu.dataset.tipo;

    // Verificar si el nuevo nombre no está vacío
    if (nuevoNombre !== "") {
        cambiarNombre(idRuta, tipo, nuevoNombre);
    }
}

/**
 * Función para gestionar la acción de cambiar el nombre de una carpeta o archivo en la papelera
 */
export function cambiarNombrePapeleraAccion() {
    const contextMenu = document.querySelector("#context-menu");
    const menu = contextMenu.querySelector(".menu");
    const inputCambiarNombre = document.querySelector(".input_cambiar_nombre_papelera");
    const nuevoNombre = inputCambiarNombre.value;
    const idRuta = menu.dataset.id;
    const tipo = menu.dataset.tipo;
    const idRutaPapelera = menu.dataset.id_papelera;

    // Verificar si el nuevo nombre no está vacío
    if (nuevoNombre !== "") {
        cambiarNombrePapelera(idRuta, tipo, nuevoNombre, idRutaPapelera);
    }
}
