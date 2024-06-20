import { mover } from "../../contextMenu/acciones/mover.js";

/**
 * Función asíncrona que mueve el contenido (carpetas y archivos) a una nueva ubicación
 * @param {string[]} idRutasCarpetas - Los IDs de las carpetas a mover
 * @param {string[]} idRutasArchivos - Los IDs de los archivos a mover
 * @param {string} idRutaDestino - El ID de la nueva ubicación destino
 */
export async function moverContenido(idRutasCarpetas, idRutasArchivos, idRutaDestino) {
    try {
        // Mueve carpetas a la nueva ubicación
        idRutasCarpetas.forEach(idRutaCarpeta => {
            // Llama a la función para mover la carpeta a la nueva ubicación
            mover(idRutaCarpeta, "carpeta", idRutaDestino);
        });

        // Mueve archivos a la nueva ubicación
        idRutasArchivos.forEach(idRutaArchivo => {
            // Llama a la función para mover el archivo a la nueva ubicación
            mover(idRutaArchivo, "archivo", idRutaDestino);
        });
    } catch (error) {
        // Maneja cualquier error que ocurra durante el proceso de movimiento
        console.error(error);
    }
}
