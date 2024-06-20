import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

/**
 * Función asíncrona para crear una nueva carpeta
 * @param {Event} e - El evento de clic que desencadenó la creación de la carpeta
 */
export async function crearCarpeta(e) {
    // Obtener los elementos del DOM necesarios
    const inputCrearCarpeta = document.querySelector(".input_nombre_carpeta");
    const inputRutaCarpeta = document.querySelector(".input_hidden");

    // Obtener el nombre de la carpeta y la ruta de la carpeta padre
    const nombreCarpeta = inputCrearCarpeta.value;
    const idCarpeta = inputRutaCarpeta.value;
    const enviar = e.target.textContent;

    // Verificar si se proporcionó un nombre de carpeta
    if (nombreCarpeta) {
        try {
            // Crear un objeto FormData y agregar los datos necesarios
            let formData = new FormData();
            formData.append("enviar_crear_carpeta", enviar);
            formData.append("nombre_carpeta", nombreCarpeta);
            formData.append("id_carpeta", idCarpeta);

            // Realizar la solicitud al servidor para crear la carpeta
            const respuesta = await fetchDataBody(`mvc/controllers/carpetas/inicio/crear_carpeta.php`, formData);

            // Mostrar notificación con la respuesta del servidor
            mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

            // Si la creación fue exitosa, agregar la estructura de la nueva carpeta al contenedor de carpetas en el DOM
            if (respuesta.respuesta === "exito") {
                const $contenedorCarpetas = document.getElementById("carpetas");
                $contenedorCarpetas.innerHTML += respuesta.estructura;
            }
        } catch (error) {
            // Manejar cualquier error que ocurra durante el proceso
            console.error(error);
        }
    }
}
