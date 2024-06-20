import { fetchData, fetchDataBody } from "../../funcionalidades/fetchData.js";
import { obtenerDatosAlmacenamiento } from "../../funcionalidades/almacenamiento/datosAlmacenamiento.js";
import { cambiarColorSidebar } from "../../funcionalidades/sidebar.js";
import { mostrarNotificacion } from "../../funcionalidades/notificaciones/notificaciones.js";
import { verificarInputVacios } from "./eventos/eventosInput.js";

// Selección de elementos del DOM
const $conPrincipal = document.querySelector(".con_principal_ajustes");
const sidebarMenu = document.querySelectorAll(".menu ul li");
const correo = document.querySelector(".ajustes_email");
const correoActual = document.querySelector(".modal_correo_actual");
const imagenAjustes = document.getElementById("output");
const imagenSidebar = document.querySelector(".sidebar .user-img img");
const nombre = document.querySelector(".usuario_info_nombre");

/**
 * Función asíncrona que obtiene el correo del usuario desde el servidor
 * @returns {Promise<Object>} - Una promesa que obtiene el correo
 */
async function obtenerCorreo() {
    try {
        let url = "mvc/controllers/ajustes/obtener_correo.php";
        const data = await fetchData(url);
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que obtiene la imagen del usuario desde el servidor
 * @returns {Promise<Object>} - Una promesa que obtiene la imagen
 */
async function obtenerImg() {
    try {
        let url = `mvc/controllers/usuarios/obtener_img_usuario.php`;
        const data = await fetchData(url);
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que obtiene el nombre del usuario desde el servidor
 * @returns {Promise<Object>} - Una promesa que obtiene el nombre
 */
async function obtenerNombre() {
    try {
        let url = `mvc/controllers/usuarios/obtener_nombre_usuario.php`;
        const data = await fetchData(url);
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función asíncrona que sube una imagen del usuario al servidor
 * @param {File} imagen - El archivo de imagen a subir
 * @returns {Promise<Object>} - Una promesa que obtiene la respuesta de la solicitud
 */
async function subirImagen(imagen) {
    try {
        let url = `mvc/controllers/ajustes/subir_imagen.php`;
        const formData = new FormData();
        formData.append("imagen", imagen);
        const data = await fetchDataBody(url, formData);
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

// Evento que se dispara cuando el DOM ha sido completamente cargado
document.addEventListener("DOMContentLoaded", async (e) => {
    // Cambia el color del sidebar
    cambiarColorSidebar(sidebarMenu);

    // Se muestra un loader
    // Mostrar un loader mientras se cargan los datos
    let conLoader = document.createElement("div");
    let loader = document.createElement("span");
    conLoader.classList.add("con_loader");
    loader.classList.add("loader");
    $conPrincipal.appendChild(conLoader);
    $conPrincipal.appendChild(loader);

    // Obtiene datos del correo, imagen y nombre del usuario
    const respuestaCorreo = await obtenerCorreo();
    const respuestaImg = await obtenerImg();
    const respuestaNombre = await obtenerNombre();

    // Obtiene datos del almacenamiento local
    await obtenerDatosAlmacenamiento();

    // Actualiza el contenido de la página con los datos obtenidos
    if (respuestaCorreo.resultado === "Exitoso") {
        correo.textContent = respuestaCorreo.correo;
        correoActual.textContent = respuestaCorreo.correo;
    }

    if (respuestaImg.resultado === "Exitoso") {
        imagenAjustes.src = respuestaImg.rutaImagen;
    }

    if (respuestaNombre.resultado === "Exitoso") {
        nombre.textContent = respuestaNombre.nombreCompleto;
    }

    // Eliminar loader
    conLoader.remove();
    loader.remove();
});

// Evento que se dispara cuando se detecta un cambio en los elementos del DOM
document.addEventListener("change", async (e) => {
    if (e.target.matches(".input_cambiar_imagen")) {
        // Se obtiene la imagen que se quiere subir
        const imagen = e.target.files[0];
        // Hace la petición al controlador
        const respuesta = await subirImagen(imagen);
        // Se muestra la notificación en base a la respuesta
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);
        // Si la respuesta es exitosa, se actualiza la imagen en los elementos del DOM
        if (respuesta.respuesta === "exito") {
            imagenAjustes.src = respuesta.rutaImagen;
            imagenSidebar.src = respuesta.rutaImagen;
        }
    }
});

// Evento que se dispara cuando se hace clic en elementos del DOM
document.addEventListener("click", (e) => {
    if (e.target.matches(".ver_detalles_registro")) {
        // Si se selecciona "ver detalles", entonces se cambia la ruta Actual
        sessionStorage.setItem("rutaActual", "registros");
    }
});

// Evento que se dispara cuando hay una entrada en los campos de formulario
document.addEventListener("input", (e) => {
    verificarInputVacios(e);
});
