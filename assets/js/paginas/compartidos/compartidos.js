import { cambiarColorSidebar } from "../../funcionalidades/sidebar.js";
import { obtenerCarpetasCompartidas, mostrarCarpetasCompartidas, construirRutaCompartidos } from "./../../componentes/carpetas.js";
import { obtenerArchivosCompartidos, mostrarArchivos } from "./../../componentes/archivos.js";
import { manejarDobleClic, manejarClic, manejarClicMovil, mostrarLoader, eliminarLoader } from "./eventos/eventosClick.js";
import { obtenerDatosAlmacenamiento } from "../../funcionalidades/almacenamiento/datosAlmacenamiento.js";

// Selección de elementos del DOM
const $conPrincipal = document.querySelector(".con_principal");
const $contenedorCarpetas = document.getElementById("carpetas");
const $contenedorArchivos = document.getElementById("archivos");
const $rutaCompartidos = document.getElementById("ruta");
const $unidadCompartidos = document.querySelector(".ruta_compartidos");
const sidebarMenu = document.querySelectorAll(".menu ul li");

/**
 * Función asíncrona para iniciar aplicación en Compartidos
 */
async function iniciarAplicacion() {
    // Se obtiene el nombre de usuario y el id de su ruta de carpeta raíz y se asignan en "Compartidos"
    $unidadCompartidos.dataset.usuario = sessionStorage.getItem("usuario");
    $unidadCompartidos.dataset.ruta = sessionStorage.getItem("carpeta");

    let carpetas, archivos;

    const idRutaActual = sessionStorage.getItem("idUltimaRutaCompartidos");
    // Si existe una ruta actual en Compartidos
    if (idRutaActual) {
        // Se obtienen sus carpetas y archivos
        carpetas = await obtenerCarpetasCompartidas(idRutaActual);
        archivos = await obtenerArchivosCompartidos(idRutaActual);
        // Se asigna el id de ruta en la sesión
        sessionStorage.setItem("idUltimaRutaCompartidos", idRutaActual);
    } else {
        // Si no existe, se toma como referencia el id de ruta de la carpeta raíz
        const idCarpetaRaiz = sessionStorage.getItem("carpeta");
        // Se obtienen las carpetas y archivos compartidos
        carpetas = await obtenerCarpetasCompartidas(idCarpetaRaiz);
        archivos = await obtenerArchivosCompartidos(idCarpetaRaiz);
        sessionStorage.setItem("idUltimaRutaCompartidos", idCarpetaRaiz);
    }

    // Se muestran los elementos compartidos en la interfaz
    mostrarCarpetasCompartidas(carpetas, $contenedorCarpetas, $rutaCompartidos);
    mostrarArchivos(archivos, $contenedorArchivos);
}

// Evento donde se espera a que se cargue el contenido del DOM
document.addEventListener("DOMContentLoaded", async () => {
    // Se comprueba si se debe cambiar el color del sidebar
    cambiarColorSidebar(sidebarMenu);

    // Se muestra un loader
    mostrarLoader($conPrincipal);

    // Se carga los datos de almacenamiento
    await obtenerDatosAlmacenamiento();
    // Se inicia la aplicaciópn
    iniciarAplicacion();

    // Se elimina el loader
    eliminarLoader();
});

// Evento de doble click
document.addEventListener("dblclick", (e) => {
    // Función donde se maneja el doble clic sobre elementos
    manejarDobleClic(e, $contenedorCarpetas, $contenedorArchivos, $rutaCompartidos, obtenerCarpetasCompartidas, obtenerArchivosCompartidos, construirRutaCompartidos, mostrarCarpetasCompartidas, mostrarArchivos);
});

// Evento de click
document.addEventListener("click", (e) => {
    // Función donde se maneja al hacer clic sobre elementos
    manejarClic(e, $rutaCompartidos, $contenedorCarpetas, $contenedorArchivos, obtenerCarpetasCompartidas, obtenerArchivosCompartidos, construirRutaCompartidos, mostrarCarpetasCompartidas, mostrarArchivos);
    // Si se hace clic en "ver detalles" se asigna registros como ruta actual
    if (e.target.matches(".ver_detalles_registro")) {
        sessionStorage.setItem("rutaActual", "registros");
    }

    // Realizar evento si se esta en el movil
    if (/Mobi|Android/i.test(navigator.userAgent)) {
        if (!e.target.closest(".icon-dots")) {
            manejarClicMovil(e, $contenedorCarpetas, $contenedorArchivos, $rutaCompartidos, obtenerCarpetasCompartidas, obtenerArchivosCompartidos, construirRutaCompartidos, mostrarCarpetasCompartidas, mostrarArchivos);
        }
    }
});