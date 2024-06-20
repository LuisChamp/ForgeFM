import { fetchDataBody } from "../fetchData.js";

/**
 * Función asíncrona que obtiene los registros del usuario
 * @param {number} numObjetos - El número de objetos a solicitar
 * @param {number} paginaActual - La página actual de la paginación
 * @returns {Promise} - Una promesa que devuelve los registros obtenidos
 */
export async function obtenerRegistros(numObjetos, paginaActual) {
    try {
        // URL del controlador
        let url = "mvc/controllers/registros/obtener_registros.php";

        let formData = new FormData();
        // Agregar los datos que se van a enviar
        formData.append("numObjetos", numObjetos);
        formData.append("paginaActual", paginaActual);

        // Realizar la solicitud al controlador
        const data = await fetchDataBody(url, formData);

        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Capturar cualquier error que ocurra durante la solicitud
        console.error(error);
    }
}
