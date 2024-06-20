// Imports
import {
    mostrarModal, ocultarModales, obtenerDatosModalMover, mostrarModalMover,
    obtenerDatosModalCompartir, mostrarModalCompartir, mostrarModalInfoCarpeta,
    mostrarModalInfoArchivo, obtenerNombreElemento
} from "../modalBox.js";
import { crearCarpeta } from "./acciones/crearCarpeta.js";
import { moverAccion } from "./acciones/mover.js";
import { moverAPapelera } from "./acciones/moverPapelera.js";
import { eliminarDePapelera } from "./acciones/eliminar.js";
import { restaurar } from "./acciones/restaurar.js";
import { descargar, descargarCompartidos } from "./acciones/descargar.js";
import { cambiarNombreAccion, cambiarNombrePapeleraAccion } from "./acciones/cambiarNombre.js";
import { compartirAccion } from "./acciones/compartir.js";
import { construirRutaMover } from "../../componentes/carpetas.js";
import { mostrarMenuContextual, limpiarInputs, limpiarSubirArchivos } from "./eventos/eventosMenu.js";

// Variables globales para los elementos del DOM
const contextMenu = document.querySelector("#context-menu");
const $btnModalCrearCarpeta = document.querySelector(".btn_modal_crear_carpeta");
const $btnModalSubirArchivo = document.querySelector(".btn_modal_subir_archivo");
const $btnModalCambiarNombre = document.querySelector(".btn_modal_cambiar_nombre");
const $btnModalMover = document.querySelector(".btn_modal_mover");
const $btnModalCompartir = document.querySelector(".btn_modal_compartir");
const $btnModalCambiarNombrePapelera = document.querySelector(".btn_modal_cambiar_nombre_papelera");
const $btnModalInformacion = document.querySelector(".btn_modal_informacion");
const $btnModalInformacionCompartidos = document.querySelector(".btn_modal_informacion_compartidos");
const $modalMover = document.querySelector(".modal-carpetas-ruta-mover");
const $conRutaMover = document.querySelector(".modal-mover-ruta");
const $conModal = document.querySelector(".modal");
const $conNombreElemento = document.querySelector(".compartir_nombre_elemento");
const $conPropietarioNombre = document.querySelector(".compartido-nombre-propietario");
const $conPropietarioEmail = document.querySelector(".compartido-email-propietario");
const $conPropietarioImg = document.querySelector(".compartido-img-propietario");
const $conLectores = document.querySelector(".lectores");

// Modales para cada accion del menú contextual
const $modales = {
    subirArchivo: document.querySelector(".modal-subir-archivo"),
    crearCarpeta: document.querySelector(".modal-crear-carpeta"),
    cambiarNombre: document.querySelector(".modal-cambiar-nombre"),
    mover: document.querySelector(".modal-mover"),
    compartir: document.querySelector(".modal-compartir"),
    cambiarNombrePapelera: document.querySelector(".modal-cambiar-nombre-papelera"),
    infoCarpeta: document.querySelector(".modal-informacion-carpeta"),
    infoArchivo: document.querySelector(".modal-informacion-archivo"),
};

/**
 * Función asíncrona que maneja la lógica para mostrar el modal de mover
 * @param {HTMLElement} menu - El menú contextual
 */
async function manejarModalMover(menu) {
    const idRutaActual = sessionStorage.getItem("idUltimaRuta");
    // Obtener nombre del elemento elegido
    await obtenerNombreElemento(menu.dataset.id, menu.dataset.tipo);
    let carpetas, rutaMover;
    if (idRutaActual) {
        // Obtener datos y ruta para la carpeta actual
        carpetas = await obtenerDatosModalMover(idRutaActual);
        rutaMover = await construirRutaMover(idRutaActual);
    } else {
        // Obtener datos y ruta para la carpeta predeterminada (carpeta raíz)
        carpetas = await obtenerDatosModalMover(sessionStorage.getItem("carpeta"));
        rutaMover = await construirRutaMover(sessionStorage.getItem("carpeta"));
    }
    // Mostrar el modal de mover con los datos obtenidos
    await mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
    mostrarModal($modales.mover, $conModal);
}

/**
 * Función asíncrona que maneja la lógica para mostrar el modal de compartir
 * @param {HTMLElement} menu - El menú contextual
 */
async function manejarModalCompartir(menu) {
    // Obtener datos para compartir el archivo o carpeta
    const response = await obtenerDatosModalCompartir(menu.dataset.id, menu.dataset.tipo);
    // Mostrar el modal de compartir con los datos obtenidos
    await mostrarModalCompartir(
        $conNombreElemento, $conPropietarioNombre, $conPropietarioEmail,
        $conPropietarioImg, $conLectores, response.nombre,
        response.propietario_nombre, response.propietario_email,
        response.propietario_img, response.lectores
    );
    mostrarModal($modales.compartir, $conModal);
}

/**
 * Función asíncrona que maneja la lógica para mostrar el modal de información
 * @param {HTMLElement} menu - El menú contextual
 */
async function manejarModalInformacion(menu) {
    if (menu.dataset.tipo === "carpeta") {
        // Mostrar modal de información para carpetas
        mostrarModal($modales.infoCarpeta, $conModal);
        await mostrarModalInfoCarpeta(menu.dataset.id, menu.dataset.tipo);
    } else {
        // Mostrar modal de información para archivos
        mostrarModal($modales.infoArchivo, $conModal);
        await mostrarModalInfoArchivo(menu.dataset.id, menu.dataset.tipo);
    }
}

/**
 * Función asíncrona que maneja la lógica para mostrar el modal de información de elementos compartidos
 * @param {HTMLElement} menu - El menú contextual
 */
async function manejarModalInformacionCompartidos(menu) {
    if (menu.dataset.tipo === "carpeta") {
        // Mostrar modal de información para carpetas compartidas
        await mostrarModalInfoCarpeta(menu.dataset.id, menu.dataset.tipo);
        mostrarModal($modales.infoCarpeta, $conModal);
    } else {
        // Mostrar modal de información para archivos compartidos
        await mostrarModalInfoArchivo(menu.dataset.id, menu.dataset.tipo);
        mostrarModal($modales.infoArchivo, $conModal);
    }
}

/**
 * Función asíncrona que maneja la selección de una carpeta donde se va a mover un elemento
 * @param {Event} e - El evento de clic
 */
async function manejarCarpetaMover(e) {
    const carpetaMover = e.target.closest(".modal-carpeta-mover");
    carpetaMover.classList.add("active");
    const carpetasMover = document.querySelectorAll(".modal-carpeta-mover");
    carpetasMover.forEach((carpeta) => {
        if (carpeta !== carpetaMover) {
            carpeta.classList.remove("active");
        }
    });
}

/**
 * Función asíncrona que maneja la selección de una parte de la ruta dentro del modal Mover
 * @param {Event} e - El evento de clic
 */
async function manejarParteRutaMover(e) {
    const idRuta = e.target.dataset.id;
    const carpetas = await obtenerDatosModalMover(idRuta);
    const rutaMover = await construirRutaMover(idRuta);
    await mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
}

/**
 * Función asíncrona que maneja las acciones de compartir
 * @param {Event} e - El evento de clic
 */
async function manejarAccionCompartir(e) {
    let emailReceptor, accion;
    const nombrePropietario = sessionStorage.getItem("usuario");
    // Si se quiere compartir un elemento con un usuario
    if (e.target.matches(".btn_agregar_lector")) {
        emailReceptor = document.querySelector(".input_compartir").value;
        accion = "agregar";
        limpiarInputs();
    } else { // Si se quiere quitar acceso a un usuario
        emailReceptor = e.target.closest("div .compartido-lector").dataset.email;
        accion = "quitar";
    }

    await compartirAccion(nombrePropietario, emailReceptor, accion);
    limpiarInputs();
}

/**
 * Función asíncrona que maneja la acción de mover un archivo o carpeta
 */
async function manejarMoverAccion() {
    const carpetaSeleccionada = $modalMover.querySelector(".active");
    let idRutaDestino;
    // Se verifica si se ha seleccionado una carpeta o si se esta dentro de una carpeta, para saber la carpeta de destino del elemento
    if (carpetaSeleccionada) {
        idRutaDestino = carpetaSeleccionada.dataset.id;
    } else {
        idRutaDestino = document.querySelector(".parte_ruta_mover.active").dataset.id;
    }
    // Se llama a la función para mover el elemento
    await moverAccion(idRutaDestino);
    ocultarModales($modales, $conModal);
}

/**
 * Función asíncrona que ejecuta la acción seleccionada en el menú contextual
 * @param {string} accion - La acción seleccionada
 * @param {string} idRuta - El ID de la ruta
 * @param {string} tipo - El tipo de elemento (archivo o carpeta)
 * @param {string} idPapelera - El ID de la papelera (si tiene)
 */
async function ejecutarAccion(accion, idRuta, tipo, idRutaPapelera) {
    switch (accion) {
        case "descargar":
            descargar(idRuta, tipo);
            break;
        case "papelera":
            moverAPapelera(idRuta, tipo);
            break;
        case "restaurar":
            restaurar(idRuta, tipo, idRutaPapelera);
            break;
        case "eliminar":
            eliminarDePapelera(idRuta, tipo, idRutaPapelera);
            break;
        case "descargar_compartidos":
            descargarCompartidos(idRuta, tipo);
            break;
        default:
            break;
    }
}

/**
 * Función asíncrona que muestra el modal correspondiente al evento click
 * @param {Event} e - El evento click
 */
async function mostrarModales(e) {
    const menu = document.querySelector("#context-menu .menu");

    if ($btnModalCrearCarpeta && $btnModalCrearCarpeta.contains(e.target)) {
        // Mostrar modal para crear carpeta
        mostrarModal($modales.crearCarpeta, $conModal);
    } else if ($btnModalSubirArchivo && $btnModalSubirArchivo.contains(e.target)) {
        // Mostrar modal para subir archivo
        mostrarModal($modales.subirArchivo, $conModal);
    } else if ($btnModalCambiarNombre && $btnModalCambiarNombre.contains(e.target)) {
        // Mostrar modal para cambiar nombre
        mostrarModal($modales.cambiarNombre, $conModal);
    } else if ($btnModalMover && $btnModalMover.contains(e.target)) {
        // Manejar modal para mover archivos o carpetas
        await manejarModalMover(menu);
    } else if ($btnModalCompartir && $btnModalCompartir.contains(e.target)) {
        // Manejar modal para compartir archivos o carpetas
        await manejarModalCompartir(menu);
    } else if ($btnModalCambiarNombrePapelera && $btnModalCambiarNombrePapelera.contains(e.target)) {
        // Mostrar modal para cambiar nombre en la papelera
        mostrarModal($modales.cambiarNombrePapelera, $conModal);
    } else if ($btnModalInformacion && $btnModalInformacion.contains(e.target)) {
        // Manejar modal de información
        await manejarModalInformacion(menu);
    } else if ($btnModalInformacionCompartidos && $btnModalInformacionCompartidos.contains(e.target)) {
        // Manejar modal de información de elementos compartidos
        await manejarModalInformacionCompartidos(menu);
    }
}

/**
 * Función asíncrona que maneja los eventos de clic en el documento
 * @param {Event} e - El evento de clic
 */
async function manejarClick(e) {
    // Ocultar menú contextual si está activo
    if (contextMenu.classList.contains("active")) {
        contextMenu.classList.remove("active");
    }

    const menuOpciones = e.target.closest(".icon-dots");
    if (menuOpciones) {
        // Mostrar menú contextual al hacer clic en los 3 puntos (opciones)
        mostrarMenuContextual(e, contextMenu);
    }

    const item = e.target.closest(".item");

    if (item) {
        const menu = e.target.closest(".menu");
        const tipo = menu.dataset.tipo;
        const idRuta = menu.dataset.id;
        const idPapelera = menu.dataset.id_papelera;
        const accion = item.dataset.label;
        // Ejecutar la acción seleccionada en el menú contextual
        ejecutarAccion(accion, idRuta, tipo, idPapelera);
    }
    // Mostrar el modal correspondiente al botón presionado
    mostrarModales(e);

    if (e.target.matches(".button-crear")) {
        // llamar a la función para crear una nueva carpeta
        crearCarpeta(e);
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }

    if (e.target.matches(".button-cambiar-nombre")) {
        // llamar a la función para cambiar de nombre al elemento
        cambiarNombreAccion();
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }

    if (e.target.matches(".button-cambiar-nombre-papelera")) {
        // llamar a la función para cambiar de nombre al elemento en la papelera
        cambiarNombrePapeleraAccion();
        ocultarModales($modales, $conModal);
        limpiarInputs();
    }

    if (e.target.closest(".modal-carpeta-mover")) {
        // llamar a la función para mover el elemento elegido
        manejarCarpetaMover(e);
    }

    if (e.target.matches(".parte_ruta_mover")) {
        // llamar a la función para construir la ruta dentro del modal mover y actualizar vista de carpetas
        manejarParteRutaMover(e);
    }

    if (e.target.matches(".btn_agregar_lector") || e.target.matches(".btn_quitar_acceso")) {
        // llamar a la función para compartir o quitar acceso a un usuario
        manejarAccionCompartir(e);
    }

    if (e.target.matches(".button-mover")) {
        // llamar a la función para mover un elemento a una carpeta
        manejarMoverAccion();
    }

    const btnCerrar = e.target.closest(".icon-button");
    if (e.target.matches(".button-cancelar") || btnCerrar) {
        // Si se cierra el modal de cualquier forma, entonces se limpia tanto los inputs y el modal de subir archivos
        ocultarModales($modales, $conModal);
        limpiarInputs();
        limpiarSubirArchivos();
    }

    // Funcion para usar modal mover en móvil
    if (/Mobi|Android/i.test(navigator.userAgent)) {
        if (e.target.closest(".modal-carpeta-mover")) {
            const carpetaMover = e.target.closest(".modal-carpeta-mover");
            const idRuta = carpetaMover.dataset.id;
            const carpetas = await obtenerDatosModalMover(idRuta);
            const rutaMover = await construirRutaMover(idRuta);
            await mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
        }
    }

}

// Eventos del documento
document.addEventListener("contextmenu", (e) => {
    // Mostrar menú contextual al hacer clic derecho
    mostrarMenuContextual(e, contextMenu);
});

document.addEventListener("click", async (e) => {
    manejarClick(e);
});

document.addEventListener("dblclick", async (e) => {
    // Acceder al contenido de una carpeta en el modal Mover
    if (e.target.closest(".modal-carpeta-mover")) {
        const carpetaMover = e.target.closest(".modal-carpeta-mover");
        const idRuta = carpetaMover.dataset.id;
        const carpetas = await obtenerDatosModalMover(idRuta);
        const rutaMover = await construirRutaMover(idRuta);
        await mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
    }
});