import { fetchDataBody } from "../fetchData.js";

/**
 * Función asíncrona que construye la paginación para una lista de registros
 * @param {number} paginaActual - La página actual de la paginación
 * @param {number} totalPaginas - El total de páginas disponibles
 * @returns {Promise} - Una promesa que devuelve la paginación
 */
export async function construirPaginacion(paginaActual, totalPaginas) {
    try {
        // URL del controlador
        let url = "mvc/controllers/registros/construir_paginacion.php";

        let formData = new FormData();
        // Agregar los datos que se van a enviar
        formData.append("paginaActual", paginaActual);
        formData.append("totalPaginas", totalPaginas);

        // Realizar la solicitud al controlador
        const data = await fetchDataBody(url, formData);

        // Devolver la paginación
        return data;
    } catch (error) {
        // Capturar cualquier error que ocurra durante la solicitud
        console.error(error);
    }
}
