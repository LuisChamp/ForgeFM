import {
    mostrarModal,
    ocultarModales,
} from "../../funcionalidades/modalBox.js";
import { cambiarCorreo } from "./acciones/cambiarCorreo.js";
import { cambiarPass } from "./acciones/cambiarPass.js";
import { eliminarCuenta } from "./acciones/eliminarCuenta.js";

// Selección de botones en el DOM
const $btnCambiarCorreo = document.querySelector(".btn_cambiar_correo");
const $btnCambiarPass = document.querySelector(".btn_cambiar_pass");
const $btnEliminarCuenta = document.querySelector(".btn_eliminar_cuenta");

// Selección de modales en el DOM
const $modales = {
    cambiarCorreo: document.querySelector(".modal-cambiar-correo"),
    cambiarPass: document.querySelector(".modal-cambiar-pass"),
    eliminarCuenta: document.querySelector(".modal-eliminar-cuenta"),
};

// Contenedor del modal
const $conModal = document.querySelector(".modal");

/**
 * Función que muestra el modal correspondiente basado en el botón que se elige
 * @param {Event} e - El evento de clic
 */
function mostrarModales(e) {
    switch (e.target) {
        case $btnCambiarCorreo:
            mostrarModal($modales.cambiarCorreo, $conModal);
            break;
        case $btnCambiarPass:
            mostrarModal($modales.cambiarPass, $conModal);
            break;
        case $btnEliminarCuenta:
            mostrarModal($modales.eliminarCuenta, $conModal);
            break;
        default:
            break;
    }
}

/**
 * Función que limpia los campos de entrada y deshabilita los botones de acción correspondientes
 */
function limpiarInputs() {
    const inputs = document.querySelectorAll("input");
    const btnCambiarCorreo = document.querySelector(".button_cambiar_correo");
    const btnCambiarPass = document.querySelector(".button_cambiar_pass");

    inputs.forEach((input) => {
        input.value = "";
    });

    btnCambiarCorreo.disabled = true;
    btnCambiarPass.disabled = true;
}

// Evento que se dispara cuando se hace clic en el documento
document.addEventListener("click", async (e) => {
    mostrarModales(e);

    // Si se elige el botón para cambiar correo
    if (e.target.matches(".button_cambiar_correo")) {
        // Se obtiene el correo ingresado y se manda a la función
        const nuevoCorreo = document.querySelector(".input_nuevo_correo").value;
        if (!nuevoCorreo) {
            return;
        }
        await cambiarCorreo(nuevoCorreo);
        // Se ocultan los modales y se limpian los campos
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }

    // Si se elige el botón para cambiar contraseña
    if (e.target.matches(".button_cambiar_pass")) {
        // Los datos ingresados en los campos de entrada se mandan a la función para cambiar la contraseña
        const passActual = document.querySelector(".pass_actual").value;
        const passNueva = document.querySelector(".pass_nueva").value;
        const passNuevaConf = document.querySelector(".pass_conf_nueva").value;
        if (!passActual || !passNueva || !passNuevaConf) {
            return;
        }
        await cambiarPass(passActual, passNueva, passNuevaConf);
        // Se ocultan los modales y se limpian los campos
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }

    // Si se elige el botón para eliminar cuenta
    if (e.target.matches(".button_eliminar_cuenta")) {
        const confirmacion = "si";
        // Y si se confirma, entonces se llama a la función para eliminar cuenta del usuario
        await eliminarCuenta(confirmacion);
        // Se ocultan los modales y se limpian los campos
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }

    const btnCerrar = e.target.closest(".icon-button");
    // Si se elige el botón cerrar o cancelar, se ocultan los modales y se limpian los campos
    if (btnCerrar || e.target.matches(".button-cancelar")) {
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }
});
