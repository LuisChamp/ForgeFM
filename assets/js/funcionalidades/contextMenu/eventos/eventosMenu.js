/**
 * Función que muestra el menú contextual en la posición del clic del usuario
 * @param {Event} e - El evento del clic del ratón
 * @param {HTMLElement} contextMenu - El elemento del menú contextual
 */
export function mostrarMenuContextual(e, contextMenu) {
    // Previene el comportamiento predeterminado del clic derecho
    e.preventDefault();

    // Si el menú contextual está activo, lo desactiva
    if (contextMenu.classList.contains("active")) {
        contextMenu.classList.remove("active");
    }

    // Busca el elemento más cercano con la clase "carpeta" o "archivo"
    let carpeta = e.target.closest(".carpeta");
    let archivo = e.target.closest(".archivo");

    // Si se encuentra una carpeta o archivo, continúa
    if (carpeta || archivo) {
        e.preventDefault();

        // Obtiene el contenedor principal del menú
        const $menu = document.querySelector(".con_principal");
        const menu = contextMenu.querySelector(".menu");

        // Asigna los datos del elemento al menú contextual
        if (carpeta) {
            menu.dataset.id = carpeta.dataset.id;
            menu.dataset.id_papelera = carpeta.dataset.id_papelera;
            menu.dataset.tipo = carpeta.dataset.tipo;
        } else {
            menu.dataset.id = archivo.dataset.id;
            menu.dataset.tipo = archivo.dataset.tipo;
            menu.dataset.id_papelera = archivo.dataset.id_papelera;
        }

        // Coordenadas del clic del ratón
        const clickX = e.clientX;
        const clickY = e.clientY;
        const pageY = e.pageY;

        // Dimensiones del menú
        let menuWidth = parseInt(menu.dataset.width);
        let menuHeight = parseInt(menu.dataset.height);

        // Dimensiones del contenedor de las carpetas y los archivos
        const containerWidth = $menu.offsetWidth;
        const containerHeight = $menu.offsetHeight;

        // Calcula las coordenadas para asegurarse de que el menú aparezca dentro del contenedor
        let x, y;
        if (clickX + menuWidth > containerWidth) {
            x = clickX - menuWidth + "px"; // Aparece a la izquierda
        } else {
            x = clickX + "px";
        }

        if (pageY + menuHeight > containerHeight) {
            y = clickY - menuHeight + "px"; // Ajusta la posición vertical hacia arriba
        } else {
            y = clickY + "px";
        }

        // Establece las coordenadas del menú
        menu.style.left = x;
        menu.style.top = y;

        // Activa el menú contextual
        contextMenu.classList.add("active");
    }
}

/**
 * Función que limpia los valores de los inputs de formularios y desactiva botones relevantes
 */
export function limpiarInputs() {
    const inputs = document.querySelectorAll(".form input");

    const btnCrearCarpeta = document.querySelector(".button-crear");
    const btnCambiarNombre = document.querySelector(".button-cambiar-nombre");
    const btnCambiarNombrePapelera = document.querySelector(".button-cambiar-nombre-papelera");
    const btnCompartir = document.querySelector(".btn_agregar_lector");

    // Limpia el valor de todos los inputs
    inputs.forEach((input) => {
        input.value = "";
    });

    // Desactiva los botones relevantes
    btnCrearCarpeta.disabled = true;
    btnCambiarNombre.disabled = true;
    btnCambiarNombrePapelera.disabled = true;
    btnCompartir.disabled = true;

    // Limpia el valor del input específico para compartir
    document.querySelector(".form_compartir input").value = "";
}

/**
 * Función que limpia la lista de archivos a subir y oculta la sección de la lista
 */
export function limpiarSubirArchivos() {
    const dropZone = document.querySelector(".container-subir-archivo .list");
    const listSection = document.querySelector(".list-section");

    // Limpia el contenido de la zona de archivos a subir
    dropZone.innerHTML = "";

    // Oculta la sección de la lista
    listSection.style.display = "none";
}
