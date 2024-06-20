import { eliminarDePapelera } from "../../contextMenu/acciones/eliminar.js";

/**
 * Función asíncrona que elimina contenido de la papelera según los IDs de carpetas y archivos proporcionados
 * @param {string[]} idRutasCarpetas - Los IDs de las carpetas a eliminar de la papelera
 * @param {string[]} idRutasArchivos - Los IDs de los archivos a eliminar de la papelera
 */
export async function eliminarContenido(idRutasCarpetas, idRutasArchivos) {
    try {
        // Elimina carpetas de la papelera
        idRutasCarpetas.forEach(idRutaCarpeta => {
            // Obtiene el ID de la papelera correspondiente a la carpeta
            let idRutaPapelera = document.querySelector(`.carpeta[data-id="${idRutaCarpeta}"`).dataset.id_papelera;
            // Llama a la función para eliminar la carpeta de la papelera
            eliminarDePapelera(idRutaCarpeta, "carpeta", idRutaPapelera);
        });

        // Elimina archivos de la papelera
        idRutasArchivos.forEach(idRutaArchivo => {
            // Obtiene el ID de la papelera correspondiente al archivo
            let idRutaPapelera = document.querySelector(`.archivo[data-id="${idRutaArchivo}"`).dataset.id_papelera;
            // Llama a la función para eliminar el archivo de la papelera
            eliminarDePapelera(idRutaArchivo, "archivo", idRutaPapelera);
        });
    } catch (error) {
        // Maneja cualquier error que ocurra durante el proceso de eliminación
        console.error(error);
    }
}
