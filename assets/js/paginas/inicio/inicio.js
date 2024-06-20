import { cambiarColorSidebar } from "../../funcionalidades/sidebar.js";
import { obtenerCarpetas, mostrarCarpetas, construirRutaApp } from "./../../componentes/carpetas.js";
import { obtenerArchivos, mostrarArchivos } from "./../../componentes/archivos.js";
import { obtenerUsuario, obtenerRutasUsuario } from "./../../componentes/usuario.js";
import { manejarDobleClic, manejarClic, manejarClicMovil, modoEdicion, mostrarLoader, eliminarLoader } from "./eventos/eventosClick.js";
import { obtenerDatosAlmacenamiento } from "../../funcionalidades/almacenamiento/datosAlmacenamiento.js";
import { filtrarCarpetas, filtrarArchivos, verificarInputVacios } from "./eventos/eventosFilter.js";

// Selección de elementos del DOM
const $conPrincipal = document.querySelector(".con_principal");
const $contenedorCarpetas = document.getElementById("carpetas");
const $rutaApp = document.getElementById("ruta");
const $unidadUsuario = document.querySelector(".ruta_unidad");
const $contenedorArchivos = document.getElementById("archivos");
const $inputHidden = document.querySelectorAll(".input_hidden");
const $btn_atras = document.querySelector("#btn_retroceder");
const $con_btn_atras = document.querySelector(".con_btn_atras");
const sidebarMenu = document.querySelectorAll(".menu ul li");

/**
 * Función asíncrona para iniciar la aplicación
 * Configura el entorno del usuario y carga las carpetas y archivos
 */
async function iniciarAplicacion() {
    // Establece los datos del usuario en los elementos del DOM
    $unidadUsuario.dataset.usuario = sessionStorage.getItem("usuario");
    $unidadUsuario.dataset.ruta = sessionStorage.getItem("carpeta");

    let carpetas, archivos;

    // Verificar si hay una ruta actual almacenada en la sesión
    const idRutaActual = sessionStorage.getItem("idUltimaRuta");

    if (idRutaActual) {
        // Si hay una ruta actual, obtener las carpetas y archivos correspondientes
        carpetas = await obtenerCarpetas(idRutaActual);
        archivos = await obtenerArchivos(idRutaActual);
        // Actualizar la ruta actual en el almacenamiento de sesión
        sessionStorage.setItem("idUltimaRuta", idRutaActual);
        // Establecer el valor de la ruta actual en los input hidden
        $inputHidden.forEach((input) => {
            input.value = idRutaActual;
        });
    } else {
        // Si no hay una ruta actual almacenada, obtener las carpetas y archivos del directorio raíz del usuario
        const idCarpetaRaiz = sessionStorage.getItem("carpeta");
        carpetas = await obtenerCarpetas(idCarpetaRaiz);
        archivos = await obtenerArchivos(idCarpetaRaiz);
        // Establecer la ruta actual en el directorio raíz del usuario en el almacenamiento de sesión
        sessionStorage.setItem("idUltimaRuta", idCarpetaRaiz);
        // Establecer el valor de la ruta actual en los input hidden
        $inputHidden.forEach((input) => {
            input.value = idCarpetaRaiz;
        });
    }

    // Mostrar las carpetas y archivos obtenidos en la interfaz de usuario
    mostrarCarpetas(carpetas, $contenedorCarpetas, $rutaApp);
    mostrarArchivos(archivos, $contenedorArchivos);
}

// Evento donde se espera a que se cargue el contenido del DOM
document.addEventListener("DOMContentLoaded", async () => {
    // Cambiar el color del sidebar
    cambiarColorSidebar(sidebarMenu);

    // Mostrar el loader mientras se cargan los datos
    mostrarLoader($conPrincipal);

    // Obtener los datos del usuario
    const usuario = await obtenerUsuario();

    // Configurar datos del usuario en la sesión si no están ya establecidos
    if (!sessionStorage.getItem("usuario")) {
        sessionStorage.setItem("usuario", usuario);
    }

    if (!sessionStorage.getItem("rutaActual")) {
        sessionStorage.setItem("rutaActual", "inicio");
    }

    // Obtener las rutas del usuario
    const rutas = await obtenerRutasUsuario();

    // Configurar rutas del usuario en la sesión si no están ya establecidas
    if (!sessionStorage.getItem("carpeta")) {
        sessionStorage.setItem("carpeta", rutas[0]);
    }
    if (!sessionStorage.getItem("papelera")) {
        sessionStorage.setItem("papelera", rutas[1]);
    }

    // Obtener datos de almacenamiento e iniciar la aplicación
    await obtenerDatosAlmacenamiento();
    await iniciarAplicacion();

    // Eliminar el loader después de cargar los datos
    eliminarLoader();
});

// Agregar un evento de doble clic a la lista de carpetas
document.addEventListener("dblclick", (e) => {
    manejarDobleClic(e, $contenedorCarpetas, $contenedorArchivos, $rutaApp, $inputHidden, obtenerCarpetas, obtenerArchivos, construirRutaApp, mostrarCarpetas, mostrarArchivos, $con_btn_atras);
});

// Agregar un evento de clic a la lista de carpetas y archivos
document.addEventListener("click", (e) => {
    manejarClic(e, $rutaApp, $contenedorCarpetas, $contenedorArchivos, $inputHidden, obtenerCarpetas, obtenerArchivos, construirRutaApp, mostrarCarpetas, mostrarArchivos, $con_btn_atras);

    // Realizar evento si se esta en el movil
    if (/Mobi|Android/i.test(navigator.userAgent)) {
        if (!e.target.closest(".icon-dots")) {
            manejarClicMovil(e, $contenedorCarpetas, $contenedorArchivos, $rutaApp, $inputHidden, obtenerCarpetas, obtenerArchivos, construirRutaApp, mostrarCarpetas, mostrarArchivos, $con_btn_atras);
        }
    }

    modoEdicion(e);
    // Si se hace clic en un elemento para ver detalles de registro, actualizar la ruta actual en la sesión
    if (e.target.matches(".ver_detalles_registro")) {
        sessionStorage.setItem("rutaActual", "registros");
    }
});

// Agregar eventos de entrada para filtrar carpetas y archivos y verificar inputs vacíos
document.addEventListener("input", (e) => {
    filtrarCarpetas(e);
    filtrarArchivos(e);
    verificarInputVacios(e);
});
