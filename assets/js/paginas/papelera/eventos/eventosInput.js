// Selección del botón para cambiar el nombre de la papelera
const btnCambiarNombrePapelera = document.querySelector(".button-cambiar-nombre-papelera");

/**
 * Función que verifica si los inputs están vacíos para habilitar o deshabilitar el botón de cambiar nombre de la papelera
 * @param {Event} e - El evento del input
 */
export function verificarInputVacios(e) {
    if (e.target.matches(".input_cambiar_nombre_papelera")) {
        if (e.target.value.trim() === '') {
            // Deshabilita el botón si el input está vacío
            btnCambiarNombrePapelera.disabled = true;
        } else {
            // Habilita el botón si el input no está vacío
            btnCambiarNombrePapelera.disabled = false;
        }
    }
}
