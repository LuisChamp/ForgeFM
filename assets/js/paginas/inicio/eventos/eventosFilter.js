// Se seleccionan los elementos del DOM para los botones de los modales
const btnCrearCarpeta = document.querySelector(".button-crear");
const btnCambiarNombre = document.querySelector(".button-cambiar-nombre");
const btnCompartir = document.querySelector(".btn_agregar_lector");

/**
 * Función que verifica si los inputs están vacíos para habilitar o deshabilitar los botones de los modales
 * @param {Event} e - El evento del input
 */
export function verificarInputVacios(e) {
    if (e.target.matches(".input_nombre_carpeta")) {
        if (e.target.value.trim() === '') {
            btnCrearCarpeta.disabled = true;
        } else {
            btnCrearCarpeta.disabled = false;
        }
    }
    if (e.target.matches(".input_cambiar_nombre")) {
        if (e.target.value.trim() === '') {
            btnCambiarNombre.disabled = true;
        } else {
            btnCambiarNombre.disabled = false;
        }
    }
    if (e.target.matches(".input_compartir")) {
        if (e.target.value.trim() === '') {
            btnCompartir.disabled = true;
        } else {
            btnCompartir.disabled = false;
        }
    }
}

/**
 * Función que filtra las carpetas basándose en el texto ingresado en el input de búsqueda de carpetas
 * @param {Event} e - El evento del input
 */
export function filtrarCarpetas(e) {
    // Verifica si el input corresponde a la búsqueda de carpetas
    if (e.target.matches(".input_busqueda_carpetas")) {
        // Selecciona el input de búsqueda de carpetas
        const inputCarpetas = document.querySelector(".input_busqueda_carpetas");
        // Obtiene el valor del input y lo convierte a minúsculas
        const inputValor = inputCarpetas.value.toLowerCase();
        // Selecciona todas las carpetas
        const listaCarpetas = document.querySelectorAll(".carpeta");
        // Itera sobre cada carpeta para verificar si coincide con el valor de búsqueda
        listaCarpetas.forEach((carpeta) => {
            // Obtiene el nombre de la carpeta
            let nombre = carpeta.querySelector(".carpeta_elm").textContent;
            if (nombre.toLowerCase().includes(inputValor)) {
                // Muestra la carpeta si coincide con la búsqueda
                carpeta.style.display = "";
            } else {
                // Oculta la carpeta si no coincide con la búsqueda
                carpeta.style.display = "none";
            }
        });
    }
}

/**
 * Función que filtra las carpetas basándose en el texto ingresado en el input de búsqueda de archivos
 * @param {Event} e - El evento del input
 */
export function filtrarArchivos(e) {
    // Verifica si el input corresponde a la búsqueda de archivos
    if (e.target.matches(".input_busqueda_archivos")) {
        // Selecciona el input de búsqueda de archivos
        const inputArchivos = document.querySelector(".input_busqueda_archivos");
        // Obtiene el valor del input y lo convierte a minúsculas
        const inputValor = inputArchivos.value.toLowerCase();
        // Selecciona todos los archivos
        const listaArchivos = document.querySelectorAll(".archivo");

        // Itera sobre cada archivo para verificar si coincide con el valor de búsqueda
        listaArchivos.forEach((archivo) => {
            // Obtiene el nombre del archivo
            let nombre = archivo.querySelector(".fichero_elm").textContent;
            if (nombre.toLowerCase().includes(inputValor)) {
                // Muestra el archivo si coincide con la búsqueda
                archivo.style.display = "";
            } else {
                // Oculta el archivo si no coincide con la búsqueda
                archivo.style.display = "none";
            }
        });
    }
}
