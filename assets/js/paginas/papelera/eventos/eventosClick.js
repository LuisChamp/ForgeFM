/**
 * Función que activa o desactiva el modo de edición en la interfaz de usuario
 * @param {Event} e - El evento del clic
 */
export function modoEdicion(e) {
    // Verifica si el clic fue en el toggle o en el botón del toggle
    if (e.target.matches(".toggle") || e.target.matches(".toggle_button")) {
        // Selecciona los elementos necesarios del DOM
        let toggle = document.querySelector(".toggle");
        let texto = document.querySelector(".toggle_text");
        let modoEdicion = document.querySelector(".modo_edicion");
        let carpetas = document.querySelectorAll(".carpeta");
        let archivos = document.querySelectorAll(".archivo");
        let totalSeleccionados = document.querySelector(".total_seleccionados");

        // Alterna la clase 'active' en el toggle
        toggle.classList.toggle("active");

        // Verifica si el toggle está activo
        if (toggle.classList.contains("active")) {
            // Cambia el texto para indicar que el modo edición está activado
            texto.textContent = "Modo Edición: ON";
            // Añade la clase 'active' al modo edición 
            modoEdicion.classList.add("active");
        } else {
            // Cambia el texto para indicar que el modo edición está desactivado
            texto.textContent = "Modo Edición: OFF";
            // Remueve la clase 'active' del modo edición 
            modoEdicion.classList.remove("active");
            // Remueve la clase 'active' de todas las carpetas y archivos
            carpetas.forEach((carpeta) => {
                carpeta.classList.remove("active");
            });
            archivos.forEach((archivo) => {
                archivo.classList.remove("active");
            });
            // Reinicia el contador de seleccionados
            totalSeleccionados.textContent = 0;
        }
    }
}

/**
 * Función que recalcula el total de elementos seleccionados y actualiza el contador en la interfaz
 * @param {HTMLElement} totalSeleccionados - El elemento del DOM que muestra el total de elementos seleccionados
 */
function actualizarContador(totalSeleccionados) {
    // Inicializa el contador
    let cont = 0;

    // Itera sobre todos los elementos con clase 'active' y los cuenta
    document.querySelectorAll(".carpeta.active, .archivo.active").forEach(() => {
        cont++;
    });

    // Actualiza el texto del contador con el nuevo valor
    totalSeleccionados.textContent = cont;
}

/**
 * Función que maneja el evento de clic en las carpetas y archivos, activando o desactivando su estado seleccionado
 * @param {Event} e - El evento del clic
 */
export function manejarClic(e) {
    // Selecciona los elementos del DOM relacionados con la carpeta, archivo y toggle
    let carpeta = e.target.closest(".carpeta");
    let archivo = e.target.closest(".archivo");
    let toggle = document.querySelector(".toggle");
    let totalSeleccionados = document.querySelector(".total_seleccionados");

    // Verifica si el clic fue en una carpeta o archivo y si el toggle está activo
    if ((carpeta || archivo) && toggle.classList.contains("active")) {
        // Alterna la clase 'active' en la carpeta o archivo seleccionado
        e.target.closest(".carpeta, .archivo").classList.toggle("active");
        // Actualiza el contador de elementos seleccionados
        actualizarContador(totalSeleccionados);
    }
}
