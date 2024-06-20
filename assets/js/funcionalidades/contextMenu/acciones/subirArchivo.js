import { fetchDataBody } from "../../fetchData.js";
import { mostrarNotificacion } from "../../notificaciones/notificaciones.js";

// Seleccionar elementos del DOM
const dropArea = document.querySelector(".drop-section");
const listSection = document.querySelector(".list-section");
const listContainer = document.querySelector(".list");
const fileSelector = document.querySelector(".file-selector");
const fileSelectorInput = document.querySelector(".file-selector-input");
const $contenedorArchivos = document.getElementById("archivos");

// Cuando se hace clic en el elemento, se simula un clic en el input de tipo file para abrir el explorador de archivos
fileSelector.onclick = () => fileSelectorInput.click();

// Cuando cambia el valor del input de tipo file (es decir, se seleccionan archivos),
// se recorre la lista de archivos seleccionados y se llama a la función subirArchivo para cada archivo
fileSelectorInput.onchange = () => {
    [...fileSelectorInput.files].forEach((file) => {
        subirArchivo(file);
    });
};

// Manejar eventos de arrastrar y soltar archivos en el área de soltar (drop area)

// Cuando un archivo es arrastrado sobre el área de soltar, se previene el comportamiento por defecto del navegador
// y se añade una clase CSS para aplicar efectos visuales al área de soltar
dropArea.ondragover = (e) => {
    e.preventDefault();
    dropArea.classList.add("drag-over-effect");
};

// Cuando el archivo arrastrado sale del área de soltar, se elimina la clase CSS del efecto visual
dropArea.ondragleave = () => {
    dropArea.classList.remove("drag-over-effect");
};

// Cuando se suelta un archivo en el área de soltar, se previene el comportamiento por defecto,
// se elimina la clase CSS del efecto visual y se manejan los archivos soltados
dropArea.ondrop = (e) => {
    e.preventDefault();
    dropArea.classList.remove("drag-over-effect");
    // Se recorre cada elemento para verificar si es un archivo, luego se obtiene el archivo y se sube
    if (e.dataTransfer.items) {
        [...e.dataTransfer.items].forEach((item) => {
            // Verifica si el elemento es un archivo
            if (item.kind === "file") {
                // Obtiene el archivo
                const file = item.getAsFile();
                // Llama a la función para subir el archivo
                subirArchivo(file);
            }
        });
    } else {
        // Si DataTransfer.items no está disponible, se recorre DataTransfer.files y se sube cada archivo
        [...e.dataTransfer.files].forEach((file) => {
            // Llama a la función para subir el archivo
            subirArchivo(file);
        });
    }
};

/**
 * Función asíncrona que sube un archivo al servidor
 * @param {File} archivo - El archivo a subir
 */
async function subirArchivo(archivo) {
    try {
        // Obtener la ruta de la carpeta actual
        const idRutaCarpeta = document.querySelector(".input_hidden").value;

        // Mostrar la sección de lista de archivos
        listSection.style.display = "block";

        // Crear un elemento de lista para el archivo
        const elementoLista = crearElementoListaArchivo(archivo);
        listContainer.prepend(elementoLista);

        // Mostrar un mensaje de "Subiendo..."
        const iconoRespuesta = elementoLista.querySelector(".col_respuesta");
        iconoRespuesta.innerHTML = "Subiendo...";

        // Subir el archivo al servidor
        const respuesta = await subirArchivoPeticion(archivo, idRutaCarpeta);

        // Mostrar la notificación de la respuesta del servidor
        mostrarNotificacion(respuesta.respuesta, respuesta.notificacion);

        iconoRespuesta.style.paddingTop = '12px';
        iconoRespuesta.style.marginRight = '0px';

        // Si la carga es exitosa, mostrar el ícono de éxito y agregar la estructura del archivo al contenedor de archivos
        if (respuesta.respuesta === "exito") {
            iconoRespuesta.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#15d52c" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm45.66,85.66-56,56a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L112,148.69l50.34-50.35a8,8,0,0,1,11.32,11.32Z"></path></svg>`;
            $contenedorArchivos.innerHTML += respuesta.estructura;
        }
        // Si la carga falla, mostrar el ícono de error
        else {
            iconoRespuesta.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#ff1f1f" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm37.66,130.34a8,8,0,0,1-11.32,11.32L128,139.31l-26.34,26.35a8,8,0,0,1-11.32-11.32L116.69,128,90.34,101.66a8,8,0,0,1,11.32-11.32L128,116.69l26.34-26.35a8,8,0,0,1,11.32,11.32L139.31,128Z"></path></svg>`;
        }
    } catch (error) {
        console.error(error);
    }
}

/**
 * Función asíncrona que realiza una solicitud al servidor para subir un archivo
 * @param {File} archivo - El archivo a subir
 * @param {string} idRuta - El ID de la ruta de la carpeta de destino
 * @returns {Promise<Object>} - La respuesta del servidor
 */
async function subirArchivoPeticion(archivo, idRuta) {
    try {
        let formData = new FormData();
        // Agregar datos que se van a enviar
        formData.append("archivo", archivo);
        formData.append("idRuta", idRuta);

        // Se hace la solicitud al servidor
        const data = await fetchDataBody("mvc/controllers/archivos/inicio/subir_archivo.php", formData);

        // Se obtiene la respuesta
        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

/**
 * Función que formatea el tamaño de un archivo para mostrarlo
 * @param {number} bytes - Tamaño del archivo en bytes
 * @returns {string} - El tamaño formateado del archivo
 */
function formatoTamanioArchivo(bytes) {
    const tamMB = bytes / (1024 * 1024);
    if (tamMB < 1) {
        return (bytes / 1024).toFixed(2) + " KB";  // Convertir a KB si el tamaño es menos de 1 MB
    } else {
        return tamMB.toFixed(2) + " MB";  // Mostrar en MB si el tamaño es de 1 MB o más
    }
}

/**
 * Función que crea un elemento de lista para un archivo
 * @param {File} archivo - El archivo para el cual se creará el elemento de lista
 * @returns {HTMLLIElement} - El elemento de lista creado
 */
function crearElementoListaArchivo(archivo) {
    const elementoLista = document.createElement("li");
    elementoLista.className = "en-progreso";
    elementoLista.innerHTML = `
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#2f72e4" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM152,88V44l44,44Z"></path></svg>
        </div>
        <div class="col">
            <div class="file-name">
                <div class="name">${archivo.name}</div>
            </div>
            <div class="file-size">${formatoTamanioArchivo(archivo.size)}</div>
        </div>
        <div class="col col_respuesta">
        </div>
    `;
    return elementoLista;
}




