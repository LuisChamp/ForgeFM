import { construirRutaApp, construirRutaCompartidos } from "./../componentes/carpetas.js";
import { fetchData } from "./fetchData.js";

const menuItems = document.querySelectorAll(".menu > ul > li");
const menuBtn = document.querySelector(".menu-btn");
// Elemento donde se muestra la ruta de la aplicación en Inicio
const $rutaApp = document.getElementById("ruta");
// Elemento donde se muestra la ruta de la aplicación en Compartidos
const $rutaCompartidos = document.querySelector(".ruta_app_compartidos");
// Botón para cerrar sesión
const cerrarSesion = document.querySelector(".btn_cerrar_sesion");
// Botón para mandar al manual de uso
const manualUso = document.querySelector(".btn_manual_uso");

// Itera sobre cada elemento li encontrado
menuItems.forEach(function (menuItem) {
    // Agrega un event listener "click" en cada item del menu
    menuItem.addEventListener("click", async function (e) {
        e.preventDefault();
        // Si se hace clic en cerrar sesión
        if (cerrarSesion.contains(e.target)) {
            // Se limpia la sesión
            sessionStorage.clear();
            // Redireccionar al usuario donde se cierra la sesión en el servidor
            window.location.href = "mvc/controllers/usuarios/credenciales/cerrar_sesion.php?sesion=true";
            return;
        }

        // Si se hace clic en el manual de uso
        if (manualUso.contains(e.target)) {
            // Se abre una nueva pestaña con el manual de uso
            window.open('https://drive.google.com/file/d/1XYbKm88FudwjO10uP_gAPWnLT_Xel6Op/view?usp=sharing', '_blank');
            return;
        }

        const menu = e.target.closest("li");
        // Se asigna la ruta actual a la página que se hace clic
        if (menu.dataset.pagina) {
            sessionStorage.setItem("rutaActual", menu.dataset.pagina.toLowerCase());
        }

        const idRutaCarpetas = sessionStorage.getItem("carpeta");
        // Se resetean la ruta en inicio, compartidos y la paginación
        sessionStorage.setItem("idUltimaRuta", idRutaCarpetas);
        sessionStorage.setItem("idUltimaRutaCompartidos", idRutaCarpetas);
        sessionStorage.setItem("paginaActual", 1);

        // Se construye la ruta de aplicación en Inicio y Compartidos
        const rutaApp = await construirRutaApp(idRutaCarpetas);
        const rutaAppCompartidos = await construirRutaCompartidos(idRutaCarpetas);
        if ($rutaApp) {
            $rutaApp.innerHTML = await rutaApp;
        }
        if ($rutaCompartidos) {
            $rutaCompartidos.innerHTML = await rutaAppCompartidos;
        }

        // Almacenar la ruta de aplicación de Inicio y Compartidos en la sesión
        sessionStorage.setItem("rutasApp", rutaApp);
        sessionStorage.setItem("rutasCompartidos", rutaAppCompartidos);

        // Si se hace clic en cualquier item que no sea el de cerrar sesión
        if (menuItem.children[0] !== cerrarSesion) {
            // Se redirige al usuario en base al href del item que se ha elegido
            window.location.href = menuItem.children[0].getAttribute("href");
        }

        // Se remueve la clase "active" de los hermanos del elemento actual
        document.querySelectorAll(".menu li").forEach((li) => {
            if (li !== menuItem) {
                li.classList.remove("active");
            }
        });

        // Alterna la clase "active" en el elemento actual
        menuItem.classList.toggle("active");
    });
});

// Agrega un event listener para el evento "click" al elemento seleccionado
menuBtn.addEventListener("click", () => {
    // Selecciona el elemento con la clase "sidebar" y alterna la clase "active"
    if (window.innerWidth >= 1200) {
        document.querySelector(".sidebar").classList.toggle("active");
    }
});

document.addEventListener("DOMContentLoaded", async (e) => {
    const img = document.querySelector(".sidebar .user-img img");
    const nombreUsuario = document.querySelector(".sidebar .user-details .name");

    if (window.innerWidth < 1200) {
        document.querySelector(".sidebar").classList.add("active");
    }

    if (!sessionStorage.getItem("rutaActual")) {
        sessionStorage.setItem("rutaActual", "inicio");
    }

    // Se realiza una solicitud al controlador para obtener la imagen de perfil y nombre del usuario
    const respuesta = await fetchData(
        `mvc/controllers/usuarios/obtener_datos_usuario.php`
    );

    // Se asigna tanto la url de la imagen y el nombre del usuario
    if (respuesta.resultado === "Exitoso") {
        img.src = respuesta.rutaImagen;
        nombreUsuario.textContent = respuesta.nombreUsuario;
    }
});

window.addEventListener("resize", () => {
    if (window.innerWidth < 1200) {
        document.querySelector(".sidebar").classList.add("active");
    }
});

/**
 * Función que cambia el color del menú lateral dependiendo de la página actual
 * @param {HTMLElement[]} sidebarMenu - Los elementos del menú lateral
 */
export function cambiarColorSidebar(sidebarMenu) {
    sidebarMenu.forEach((el) => {
        // Si coincide la página con la ruta actual, entonces se cambia de color
        if (el.dataset.pagina === sessionStorage.getItem("rutaActual")) {
            el.classList.add("active");
        } else { // Si no coincide, entonces se remueve el color
            el.classList.remove("active");
        }
    });
}
