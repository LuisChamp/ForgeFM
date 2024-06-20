import { obtenerRegistros } from "../../funcionalidades/paginacion/obtenerRegistros.js";
import { construirPaginacion } from "../../funcionalidades/paginacion/paginacion.js";
import { obtenerDatosAlmacenamiento } from "../../funcionalidades/almacenamiento/datosAlmacenamiento.js";
import { cambiarColorSidebar } from "../../funcionalidades/sidebar.js";

// Selección de elementos del DOM
const table = document.querySelector(".responsive-table");
const conPaginacion = document.querySelector(".paginacion");
const sidebarMenu = document.querySelectorAll(".menu ul li");

/**
 * Evento que se dispara cuando el contenido del DOM se ha cargado completamente
 * Configura la interfaz inicial y carga los datos necesarios
 */
document.addEventListener("DOMContentLoaded", async () => {
    // Cambia el color de la barra lateral
    cambiarColorSidebar(sidebarMenu);

    // Se indica el número de objetos que van a aparecer en la página
    const numObjetos = 9;
    // Se indica la página actual
    let paginaActual = 1;

    // Recupera la página actual desde el sessionStorage si está disponible
    if (sessionStorage.getItem("paginaActual")) {
        paginaActual = sessionStorage.getItem("paginaActual");
    }

    // Obtener y mostrar los registros de la página actual
    const respuestaRegistros = await obtenerRegistros(numObjetos, paginaActual);

    sessionStorage.setItem("totalPaginas", respuestaRegistros.totalPaginas);

    // Se agregan los registros a la tabla
    table.innerHTML = respuestaRegistros.estructura;

    // Construir y mostrar la paginación
    const respuestaPaginacion = await construirPaginacion(
        paginaActual,
        sessionStorage.getItem("totalPaginas")
    );
    conPaginacion.innerHTML = respuestaPaginacion;

    // Obtener otros datos de almacenamiento
    await obtenerDatosAlmacenamiento();
});

/**
 * Evento que maneja al hacer clic en los botones de paginación
 * @param {Event} e - El evento de clic
 */
document.addEventListener("click", async (e) => {
    // Verifica si se ha hecho clic en un botón de paginación
    if (
        e.target.matches(".btn-paginacion") ||
        e.target.matches(".paginacion-extremo")
    ) {
        const pagina = e.target.dataset.pagina;
        const numObjetos = 9;

        // Actualiza la página actual en el sessionStorage
        sessionStorage.setItem("paginaActual", pagina);

        // Obtener y mostrar los registros de la página seleccionada
        const respuestaRegistros = await obtenerRegistros(numObjetos, pagina);
        sessionStorage.setItem("totalPaginas", respuestaRegistros.totalPaginas);
        table.innerHTML = respuestaRegistros.estructura;

        // Construir y mostrar la nueva paginación
        const respuestaPaginacion = await construirPaginacion(
            pagina,
            sessionStorage.getItem("totalPaginas")
        );
        conPaginacion.innerHTML = respuestaPaginacion;
    }
});
