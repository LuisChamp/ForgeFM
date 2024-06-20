// Selecciona el botón para cambiar el correo electrónico
const btnCambiarCorreo = document.querySelector(".button_cambiar_correo");
// Selecciona el botón para cambiar la contraseña
const btnCambiarPass = document.querySelector(".button_cambiar_pass");

// Selecciona los campos de entrada para la contraseña actual, nueva y la confirmación de la nueva contraseña
const inputPassActual = document.querySelector(".pass_actual");
const inputPassNueva = document.querySelector(".pass_nueva");
const inputPassConfNueva = document.querySelector(".pass_conf_nueva");

/**
 * Función que verifica si los campos de entrada están vacíos y habilita o deshabilita
 * los botones de acción correspondientes
 * @param {Event} e - El evento de entrada que desencadena la verificación
 */
export function verificarInputVacios(e) {
    // Verifica si el campo de entrada es para el nuevo correo
    if (e.target.matches(".input_nuevo_correo")) {
        // Si el valor del campo de correo está vacío, deshabilita el botón de cambiar correo
        if (e.target.value.trim() === '') {
            btnCambiarCorreo.disabled = true;
        } else {
            // Si el valor del campo de correo no está vacío, habilita el botón de cambiar correo
            btnCambiarCorreo.disabled = false;
        }
    }

    // Verifica si el campo de entrada es para la contraseña actual, nueva o la confirmación de la nueva contraseña
    if (e.target.matches(".pass_actual") || e.target.matches(".pass_nueva") || e.target.matches(".pass_conf_nueva")) {
        // Si alguno de los campos de contraseña está vacío, deshabilita el botón de cambiar contraseña
        if (inputPassActual.value.trim() === '' || inputPassNueva.value.trim() === '' || inputPassConfNueva.value.trim() === '') {
            btnCambiarPass.disabled = true;
        } else {
            // Si todos los campos de contraseña tienen valor, habilita el botón de cambiar contraseña
            btnCambiarPass.disabled = false;
        }
    }
}
