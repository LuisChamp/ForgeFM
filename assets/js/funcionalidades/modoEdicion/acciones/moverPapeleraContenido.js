import { moverAPapelera } from "../../contextMenu/acciones/moverPapelera.js";

/**
 * Función asíncrona que mueve el contenido (carpetas y archivos) a la papelera
 * @param {string[]} idRutasCarpetas - Los IDs de las carpetas a mover a la papelera
 * @param {string[]} idRutasArchivos - Los IDs de los archivos a mover a la papelera
 */
export async function moverPapeleraContenido(idRutasCarpetas, idRutasArchivos) {
    try {
        // Mueve carpetas a la papelera
        idRutasCarpetas.forEach(idRutaCarpeta => {
            // Llama a la función para mover la carpeta a la papelera
            moverAPapelera(idRutaCarpeta, "carpeta");
        });

        // Mueve archivos a la papelera
        idRutasArchivos.forEach(idRutaArchivo => {
            // Llama a la función para mover el archivo a la papelera
            moverAPapelera(idRutaArchivo, "archivo");
        });
    } catch (error) {
        // Maneja cualquier error que ocurra durante el proceso de movimiento a la papelera
        console.error(error);
    }
}
