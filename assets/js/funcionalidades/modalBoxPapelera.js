import { fetchDataBody } from "./fetchData.js";

/**
 * Función que muestra un loader
 * @param {HTMLElement} contenedor - El elemento donde se va a mostrar el loader
 */
export function mostrarLoader(contenedor) {
    let conLoader = document.createElement("div");
    let loader = document.createElement("span");
    conLoader.classList.add("con_loader");
    loader.classList.add("loader");
    contenedor.appendChild(conLoader);
    contenedor.appendChild(loader);
}

/**
 * Función que elimina un loader
 */
export function eliminarLoader() {
    let conLoader = document.querySelector(".con_loader");
    let loader = document.querySelector(".loader");

    conLoader.remove();
    loader.remove();
}

/**
 * Función que muestra un modal específico
 * @param {HTMLElement} modal - El elemento modal que se va a mostrar
 * @param {HTMLElement} con_modal - El contenedor del modal que se va a mostrar
 */
export function mostrarModal(modal, con_modal) {
    modal.classList.add("active");
    con_modal.classList.add("active");
}

/**
 * Función que oculta todos los modales
 * @param {NodeList} $modales - Lista de elementos modales
 * @param {HTMLElement} con_modal - El contenedor de los modales
 */
export function ocultarModales($modales, con_modal) {
    Object.values($modales).forEach((modal) => {
        modal.classList.remove("active");
    });
    con_modal.classList.remove("active");
}

/**
 * Función que oculta un modal específico
 * @param {HTMLElement} $modal - El elemento modal que se va a ocultar
 * @param {HTMLElement} $conModal - El contenedor del modal que se va a ocultar
 */
export function ocultarModal($modal, $conModal) {
    $modal.classList.remove("active");
    $conModal.classList.remove("active");
}

/**
 * Función asíncrona que obtiene los datos necesarios para mostrar el modal de mover
 * @param {string} idRuta - El ID de la ruta
 * @returns {Promise<Object>} - Datos necesarios para mostrar el modal de mover
 */
export async function obtenerDatosModalMover(idRuta) {
    try {
        let formData = new FormData();
        formData.append("idRuta", idRuta);
        // Realizar una solicitud al controlador
        const data = await fetchDataBody(
            `mvc/controllers/modalBox/modal_mover.php`,
            formData
        );
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asíncrona que muestra el modal de mover con los datos proporcionados
 * @param {string} carpetas - Estructura de carpetas a mostrar en el modal
 * @param {HTMLElement} $conModal - El contenedor del modal de mover
 * @param {HTMLElement} $conRutaMover - El contenedor de la ruta de mover
 * @param {string} rutaMover - La ruta a mover
 */
export async function mostrarModalMover(carpetas, $conModal, $conRutaMover, rutaMover) {
    // Asigna las carpetas con su estructura en el modal
    $conModal.innerHTML = carpetas;
    // Asigna la ruta en su contenedor
    $conRutaMover.innerHTML = rutaMover;
}

/**
 * Función asíncrona que obtiene los datos necesarios para mostrar el modal de compartir
 * @param {string} idRuta - El ID de la ruta
 * @param {string} tipo - El tipo de elemento (carpeta o archivo)
 * @returns {Promise<Object>} - Datos necesarios para mostrar el modal de compartir
 */
export async function obtenerDatosModalCompartir(idRuta, tipo) {
    try {
        let url;
        let formData = new FormData();
        formData.append("idRuta", idRuta);

        // URL del controlador según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/modalBox/modal_compartir_carpetas.php`;
        } else {
            url = `mvc/controllers/modalBox/modal_compartir_archivos.php`;
        }

        // Se realiza la petición
        const data = await fetchDataBody(url, formData);

        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asíncrona que muestra el modal de compartir con los datos proporcionados
 * @param {HTMLElement} $conNombreElemento - El contenedor del nombre del elemento a compartir
 * @param {HTMLElement} $conPropietarioNombre - El contenedor del nombre del propietario del elemento
 * @param {HTMLElement} $conPropietarioEmail - El contenedor del correo electrónico del propietario del elemento
 * @param {HTMLElement} $conPropietarioImg - El contenedor de la imagen del propietario del elemento
 * @param {HTMLElement} $conLectores - El contenedor de los lectores del elemento
 * @param {string} nombre - El nombre del elemento a compartir
 * @param {string} propietarioNombre - El nombre del propietario del elemento
 * @param {string} propietarioEmail - El correo electrónico del propietario del elemento
 * @param {string} propietarioImg - La URL de la imagen del propietario del elemento
 * @param {string} datosLectores - Los datos de los lectores del elemento
 */
export async function mostrarModalCompartir($conNombreElemento, $conPropietarioNombre, $conPropietarioEmail, $conPropietarioImg, $conLectores, nombre, propietarioNombre, propietarioEmail, propietarioImg, datosLectores) {
    const $modalCompartir = document.querySelector(".modal-compartir");

    // Se muestra un loader mientras se cargan los datos del modal
    mostrarLoader($modalCompartir);

    // Se asignan los datos en cada contenedor respectivamente
    $conNombreElemento.textContent = `"${nombre}"`;
    $conPropietarioNombre.textContent = propietarioNombre;
    $conPropietarioEmail.textContent = propietarioEmail;
    $conPropietarioImg.src = propietarioImg;
    $conLectores.innerHTML = datosLectores;

    // Se elimina el loader
    eliminarLoader();
}

/**
 * Función asíncrona que obtiene los datos necesarios para mostrar la información de una carpeta o archivo
 * @param {string} idRuta - El ID de la ruta
 * @param {string} tipo - El tipo de elemento (carpeta o archivo)
 * @returns {Promise<Object>} - Datos necesarios para mostrar la información de una carpeta o archivo
 */
export async function obtenerDatosInformacion(idRuta, tipo) {
    try {
        let url;
        let formData = new FormData();
        formData.append("idRuta", idRuta);

        // URL del controlador según el tipo de elemento
        if (tipo === "carpeta") {
            url = `mvc/controllers/carpetas/inicio/acciones/informacion_carpeta.php`;
        } else {
            url = `mvc/controllers/archivos/inicio/acciones/informacion_archivo.php`;
        }

        // Se realiza la petición
        const data = await fetchDataBody(url, formData);

        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asíncrona que muestra el modal con la información de una carpeta
 * @param {string} idRuta - El ID de la ruta de la carpeta
 * @param {string} tipo - El tipo de elemento (carpeta o archivo)
 */
export async function mostrarModalInfoCarpeta(idRuta, tipo) {
    const $modalInformacion = document.querySelector(".modal-informacion-carpeta");

    // Se muestra un loader mientras se cargan los datos del modal
    mostrarLoader($modalInformacion);

    // Selección de elementos en el modal
    const infoNombreCarpeta = document.querySelector(".info_nombre_carpeta"),
        infoPropietarioCarpeta = document.querySelector(".info_propietario_carpeta"),
        infoFechaCreacion = document.querySelector(".info_fecha_creacion_carpeta"),
        infoCantidadArchivos = document.querySelector(".info_cantidad_archivos"),
        infoCantidadCarpetas = document.querySelector(".info_cantidad_carpetas"),
        infoTamanioCarpeta = document.querySelector(".info_tamanio_carpeta");

    // Obtener datos de la carpeta
    const respuesta = await obtenerDatosInformacion(idRuta, tipo);
    const datosCarpeta = respuesta.datosCarpeta;
    const infoCarpeta = JSON.parse(respuesta.infoCarpeta);

    // Asignar datos a los elementos del modal
    infoNombreCarpeta.textContent = `"${datosCarpeta.nombre_carpeta}"`;
    infoPropietarioCarpeta.textContent = `${datosCarpeta.nombre} ${datosCarpeta.apellido}`;
    infoFechaCreacion.textContent = datosCarpeta.fecha_creacion;

    // Asignar el total de archivos y carpetas en el modal de información carpeta
    if (infoCarpeta.cantidad_archivos == "1") {
        infoCantidadArchivos.textContent = `${infoCarpeta.cantidad_archivos} archivo`;
    } else {
        infoCantidadArchivos.textContent = `${infoCarpeta.cantidad_archivos} archivos`;
    }

    if (infoCarpeta.cantidad_subcarpetas == "1") {
        infoCantidadCarpetas.textContent = `${infoCarpeta.cantidad_subcarpetas} carpeta`;
    } else {
        infoCantidadCarpetas.textContent = `${infoCarpeta.cantidad_subcarpetas} carpetas`;
    }

    // Asignar el tamaño total de la carpeta
    infoTamanioCarpeta.textContent = infoCarpeta.tamanio_total;

    // Remover loader
    eliminarLoader();
}

/**
 * Función asíncrona que muestra el modal con la información de un archivo
 * @param {string} idRuta - El ID de la ruta del archivo
 * @param {string} tipo - El tipo de elemento (carpeta o archivo)
 */
export async function mostrarModalInfoArchivo(idRuta, tipo) {
    const $modalInformacion = document.querySelector(".modal-informacion-archivo");

    // Se muestra un loader mientras se cargan los datos del modal
    mostrarLoader($modalInformacion);

    // Selección de elementos en el modal
    const infoNombreArchivo = document.querySelector(".info_nombre_archivo"),
        infoPropietarioArchivo = document.querySelector(".info_propietario_archivo"),
        infoFechaCreacion = document.querySelector(".info_fecha_creacion_archivo"),
        infoTamanioArchivo = document.querySelector(".info_tamanio_archivo");

    // Obtener datos del archivo
    const respuesta = await obtenerDatosInformacion(idRuta, tipo);
    const datosArchivo = respuesta.datosArchivo;

    // Asignar datos a los elementos del modal
    infoNombreArchivo.textContent = `"${datosArchivo.nombre_archivo}"`;
    infoPropietarioArchivo.textContent = `${datosArchivo.nombre} ${datosArchivo.apellido}`;
    infoFechaCreacion.textContent = datosArchivo.fecha_creacion;
    infoTamanioArchivo.textContent = datosArchivo.tamanio;

    // Remover loader
    eliminarLoader();
}

