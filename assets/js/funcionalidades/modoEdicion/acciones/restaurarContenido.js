import { restaurar } from "../../contextMenu/acciones/restaurar.js";

/**
 * Función asíncrona que restaura el contenido (carpetas y archivos) desde la papelera
 * @param {string[]} idRutasCarpetas - Los IDs de las carpetas a restaurar desde la papelera
 * @param {string[]} idRutasArchivos - Los IDs de los archivos a restaurar desde la papelera
 */
export async function restaurarContenido(idRutasCarpetas, idRutasArchivos) {
    try {
        // Restaurar carpetas desde la papelera
        idRutasCarpetas.forEach(idRutaCarpeta => {
            // Obtener el ID de la carpeta en la papelera
            let idRutaPapelera = document.querySelector(`.carpeta[data-id="${idRutaCarpeta}"`).dataset.id_papelera;
            // Llama a la función para restaurar la carpeta desde la papelera
            restaurar(idRutaCarpeta, "carpeta", idRutaPapelera);
        });

        // Restaurar archivos desde la papelera
        idRutasArchivos.forEach(idRutaArchivo => {
            // Obtener el ID del archivo en la papelera
            let idRutaPapelera = document.querySelector(`.archivo[data-id="${idRutaArchivo}"`).dataset.id_papelera;
            // Llama a la función para restaurar el archivo desde la papelera
            restaurar(idRutaArchivo, "archivo", idRutaPapelera);
        });
    } catch (error) {
        // Maneja cualquier error que ocurra durante el proceso de restauración desde la papelera
        console.error(error);
    }
}
