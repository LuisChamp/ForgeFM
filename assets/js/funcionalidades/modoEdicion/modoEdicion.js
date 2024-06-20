import { construirRutaMover } from "../../componentes/carpetas.js";
import { modoOcultarEdicion } from "../../paginas/inicio/eventos/eventosClick.js";
import {
    mostrarModal,
    ocultarModales,
    obtenerDatosModalMover,
    mostrarModalMover,
} from "../modalBox.js";
import { descargarContenido } from "./acciones/descargarContenido.js";
import { eliminarContenido } from "./acciones/eliminarContenido.js";
import { moverContenido } from "./acciones/moverContenido.js";
import { moverPapeleraContenido } from "./acciones/moverPapeleraContenido.js";
import { restaurarContenido } from "./acciones/restaurarContenido.js";

// Obtener los elementos del DOM
const $btnModoEdicionDescargar = document.querySelector(".modo_edicion_descargar");
const $btnModoEdicionMover = document.querySelector(".modo_edicion_mover");
const $btnModoEdicionMoverPapelera = document.querySelector(".modo_edicion_mover_papelera");
const $btnModoEdicionRestaurar = document.querySelector(".modo_edicion_restaurar");
const $btnModoEdicionEliminar = document.querySelector(".modo_edicion_eliminar");
const $modalMover = document.querySelector(".modal-carpetas-ruta-mover-multiple");
const $conRutaMover = document.querySelector(".modal-mover-ruta-multiple");
const $modales = {
    mover: document.querySelector(".modal-mover-multiple"),
};
const $conModal = document.querySelector(".modal");

/**
 * Función asíncrona que maneja la acción de descarga de contenido
 * @param {string[]} idRutasCarpetas - Array de IDs de carpetas seleccionadas
 * @param {string[]} idRutasArchivos - Array de IDs de archivos seleccionados
 */
async function manejarDescarga(idRutasCarpetas, idRutasArchivos) {
    descargarContenido(idRutasCarpetas, idRutasArchivos);
    modoOcultarEdicion();
}

/**
 * Función asíncrona que maneja la acción de mover contenido
 * @param {string} totalSeleccionados - Número total de elementos seleccionados
 */
async function manejarMover(totalSeleccionados) {
    const modalNombre = document.querySelectorAll(".modal_mover_nombre_item_multiple");
    modalNombre.forEach((modal) => {
        modal.textContent = `"${totalSeleccionados} elementos"`;
    });
    // Se muestra el modal
    mostrarModal($modales.mover, $conModal);
    const idRutaActual = sessionStorage.getItem("idUltimaRuta");
    let carpetas, rutaMover;
    // Se obtiene las carpetas y la ruta
    if (idRutaActual) {
        carpetas = await obtenerDatosModalMover(idRutaActual);
        rutaMover = await construirRutaMover(idRutaActual);
    } else {
        carpetas = await obtenerDatosModalMover(
            sessionStorage.getItem("carpeta")
        );
        rutaMover = await construirRutaMover(sessionStorage.getItem("carpeta"));
    }
    // Se cargan los datos en el modal
    await mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
}

/**
 * Función asíncrona que maneja la acción de mover a la papelera
 * @param {string[]} idRutasCarpetas - Array de IDs de carpetas seleccionadas
 * @param {string[]} idRutasArchivos - Array de IDs de archivos seleccionados
 */
async function manejarMoverPapelera(idRutasCarpetas, idRutasArchivos) {
    // Se llama a la función para mover elementos a la papelera
    moverPapeleraContenido(idRutasCarpetas, idRutasArchivos);
    modoOcultarEdicion();
}

/**
 * Función asíncrona que maneja la acción de restaurar contenido
 * @param {string[]} idRutasCarpetas - Array de IDs de carpetas seleccionadas
 * @param {string[]} idRutasArchivos - Array de IDs de archivos seleccionados
 */
async function manejarRestaurar(idRutasCarpetas, idRutasArchivos) {
    // Se llama a la función para restaurar elementos
    restaurarContenido(idRutasCarpetas, idRutasArchivos);
    modoOcultarEdicion();
}

/**
 * Función asíncrona que maneja la acción de eliminar contenido
 * @param {string[]} idRutasCarpetas - Array de IDs de carpetas seleccionadas
 * @param {string[]} idRutasArchivos - Array de IDs de archivos seleccionados
 */
async function manejarEliminar(idRutasCarpetas, idRutasArchivos) {
    // Se llama a la función para eliminar elementos
    eliminarContenido(idRutasCarpetas, idRutasArchivos);
    modoOcultarEdicion();
}

// Event listener para manejar acciones al hacer clic
document.addEventListener("click", async (e) => {
    // Se obtienen el total de seleccionados como las carpetas y archivos elegidos
    const totalSeleccionados = document.querySelector(".total_seleccionados").textContent;
    const listaCarpetas = document.querySelectorAll(".carpeta.active");
    const listaArchivos = document.querySelectorAll(".archivo.active");

    let idRutasCarpetas = [];
    let idRutasArchivos = [];

    // Se obtiene cada idRuta de cada elemento y se agrega en el array correspondiente
    listaCarpetas.forEach((carpeta) => {
        idRutasCarpetas.push(carpeta.dataset.id);
    });
    listaArchivos.forEach((archivo) => {
        idRutasArchivos.push(archivo.dataset.id);
    });

    if (totalSeleccionados === "0") {
        return;
    }

    // Si se elige la acción descargar
    if ($btnModoEdicionDescargar && $btnModoEdicionDescargar.contains(e.target)) {
        manejarDescarga(idRutasCarpetas, idRutasArchivos);
    }

    // Si se elige la acción mover
    if ($btnModoEdicionMover && $btnModoEdicionMover.contains(e.target)) {
        manejarMover(totalSeleccionados);
    }

    // Si se elige la acción mover a la papelera
    if ($btnModoEdicionMoverPapelera && $btnModoEdicionMoverPapelera.contains(e.target)) {
        manejarMoverPapelera(idRutasCarpetas, idRutasArchivos);
    }

    // Si se elige la acción restaurar
    if ($btnModoEdicionRestaurar && $btnModoEdicionRestaurar.contains(e.target)) {
        manejarRestaurar(idRutasCarpetas, idRutasArchivos);
    }

    // Si se elige la acción eliminar
    if ($btnModoEdicionEliminar && $btnModoEdicionEliminar.contains(e.target)) {
        manejarEliminar(idRutasCarpetas, idRutasArchivos);
    }

    // Si se elige una carpeta dentro del modal Mover, esta se selecciona
    if (e.target.closest(".modal-carpeta-mover")) {
        const carpetaMover = e.target.closest(".modal-carpeta-mover");
        carpetaMover.classList.add("active");
        const carpetasMover = document.querySelectorAll(".modal-carpeta-mover");
        carpetasMover.forEach((carpeta) => {
            if (carpeta !== carpetaMover) {
                carpeta.classList.remove("active");
            }
        });
    }

    // Si se elige una parte de la ruta de mover, se construye la ruta y se actualiza la vista de las carpetas
    if (e.target.matches(".parte_ruta_mover")) {
        const idRuta = e.target.dataset.id;
        const carpetas = await obtenerDatosModalMover(idRuta);
        const rutaMover = await construirRutaMover(idRuta);
        await mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
    }

    // Si se elige el botón Mover, se mueve los elementos seleccionados a la nueva ubicación
    if (e.target.matches(".button-mover-multiple")) {
        const carpetaSeleccionada = $modalMover.querySelector(".active");
        let idRutaDestino;
        if (carpetaSeleccionada) {
            idRutaDestino = carpetaSeleccionada.dataset.id;
        } else {
            idRutaDestino = document.querySelector(".parte_ruta_mover.active").dataset.id;
        }
        await moverContenido(idRutasCarpetas, idRutasArchivos, idRutaDestino);
        ocultarModales($modales, $conModal);
        modoOcultarEdicion();
    }

    // Si se elige el botón cancelar, cerrar o el ícono, se cierra el modal
    if (
        e.target.matches(".icon-button") ||
        e.target.matches(".button-cancelar")
    ) {
        ocultarModales($modales, $conModal);
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
});

// Event listener para manejar doble clic en elementos
document.addEventListener("dblclick", async (e) => {
    // Si se hace doble click dentro de una carpeta, se construye la ruta y se actualiza la vista de las carpetas
    if (e.target.closest(".modal-carpeta-mover")) {
        const carpetaMover = e.target.closest(".modal-carpeta-mover");
        const idRuta = carpetaMover.dataset.id;
        const carpetas = await obtenerDatosModalMover(idRuta);
        const rutaMover = await construirRutaMover(idRuta);
        mostrarModalMover(carpetas, $modalMover, $conRutaMover, rutaMover);
    }
});
