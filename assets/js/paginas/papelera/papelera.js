import { fetchDataBody } from "../../funcionalidades/fetchData.js";
import { cambiarColorSidebar } from "../../funcionalidades/sidebar.js";
import { obtenerDatosAlmacenamiento } from "../../funcionalidades/almacenamiento/datosAlmacenamiento.js";
import { obtenerCarpetasPapelera, mostrarCarpetas } from "./../../componentes/carpetas.js";
import { obtenerArchivosPapelera, mostrarArchivos } from "./../../componentes/archivos.js";
import { mostrarModal, ocultarModal } from "./../../funcionalidades/modalBoxPapelera.js";
import { manejarClic, modoEdicion } from "./eventos/eventosClick.js";
import { mostrarNotificacion } from "../../funcionalidades/notificaciones/notificaciones.js";
import { verificarInputVacios } from "./eventos/eventosInput.js";

// Selección de elementos del DOM
const $conPrincipal = document.querySelector(".con_principal");
const $contenedorCarpetas = document.getElementById("carpetas");
const $contenedorArchivos = document.getElementById("archivos");
const sidebarMenu = document.querySelectorAll(".menu ul li");
const $modalVaciarPapelera = document.querySelector(".modal-vaciar-papelera");
const $conModal = document.querySelector(".modal");

/**
 * Función asíncrona principal para iniciar la aplicación
 * Obtiene y muestra las carpetas y archivos de la papelera
 */
async function iniciarAplicacion() {
    const idPapelera = sessionStorage.getItem("papelera");

    const carpetas = await obtenerCarpetasPapelera(idPapelera);
    const archivos = await obtenerArchivosPapelera(idPapelera);

    mostrarCarpetas(carpetas, $contenedorCarpetas);
    mostrarArchivos(archivos, $contenedorArchivos);
}

/**
 * Función asíncrona que realiza la petición para vaciar la papelera
 * @returns {Promise<Object>} - Respuesta de la petición
 */
async function vaciarPapeleraPeticion() {
    try {
        let url = "mvc/controllers/carpetas/papelera/acciones/vaciar_papelera.php";
        let formData = new FormData();

        formData.append("vaciar_papelera", "true");
        const data = await fetchDataBody(url, formData);

        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que vacía la papelera, muestra una notificación y actualiza la interfaz de usuario
 */
async function vaciarPapelera() {
    try {
        const respuesta = await vaciarPapeleraPeticion();

        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);
        // Si la respuesta es exitosa, entonces elimina las carpetas y archivos
        if (respuesta.respuesta === "exito") {
            const carpetas = document.getElementById("carpetas");
            const archivos = document.getElementById("archivos");

            carpetas.innerHTML = "";
            archivos.innerHTML = "";
        }
    } catch (error) {
        console.error(error);
    }
}

// Evento que espera a que se cargue el contenido del DOM
document.addEventListener("DOMContentLoaded", async () => {
    cambiarColorSidebar(sidebarMenu);
    // Mostrar un loader mientras se cargan los datos
    let conLoader = document.createElement("div");
    let loader = document.createElement("span");
    conLoader.classList.add("con_loader");
    loader.classList.add("loader");
    $conPrincipal.appendChild(conLoader);
    $conPrincipal.appendChild(loader);

    // Se obtienen los datos de almacenamiento y se inicia la aplicación
    await obtenerDatosAlmacenamiento();
    await iniciarAplicacion();

    // Remover el loader después de cargar los datos
    conLoader.remove();
    loader.remove();
});

// Manejo de eventos de clic
document.addEventListener("click", async (e) => {

    manejarClic(e);
    modoEdicion(e);

    // Muestra el modal para vaciar papelera
    if (e.target.matches(".btn_modal_vaciar_papelera")) {
        mostrarModal($modalVaciarPapelera, $conModal);
    }

    // Si se confirma el modal, entonces se procede a llamar la función vaciar papelera
    if (e.target.matches(".button_vaciar_papelera")) {
        await vaciarPapelera();
        ocultarModal($modalVaciarPapelera, $conModal);
    }

    const btnCerrar = e.target.closest(".icon-button");

    // Se cierra el modal
    if (btnCerrar || e.target.matches(".button-cancelar")) {
        ocultarModal($modalVaciarPapelera, $conModal);
    }

    // Si se hace clic en un elemento para ver detalles de registro, actualizar la ruta actual en la sesión
    if (e.target.matches(".ver_detalles_registro")) {
        sessionStorage.setItem("rutaActual", "registros");
    }
});

// Manejo de eventos de input
document.addEventListener("input", (e) => {
    verificarInputVacios(e);
});
