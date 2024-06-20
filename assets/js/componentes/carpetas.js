import { fetchDataBody } from './../funcionalidades/fetchData.js';

/**
 * Función asincrónica para obtener la lista de carpetas
 * @param {string} idRuta - La ruta de la carpeta de la que se desean obtener las carpetas
 */
export async function obtenerCarpetas(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para obtener la lista de carpetas en la ruta especificada
        const data = await fetchDataBody(`mvc/controllers/carpetas/inicio/obtener_carpetas.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para obtener la lista de carpetas compartidas
 * @param {string} idRuta - La ruta de la carpeta de la que se desean obtener las carpetas compartidas
 */
export async function obtenerCarpetasCompartidas(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para obtener la lista de carpetas compartidas en la ruta especificada
        const data = await fetchDataBody(`mvc/controllers/carpetas/compartidos/obtener_carpetas_compartidas.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para obtener la lista de carpetas en la papelera
 * @param {string} idRuta - La ruta de la carpeta de la que se desean obtener las carpetas en la papelera
 */
export async function obtenerCarpetasPapelera(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para obtener la lista de carpetas en la ruta especificada
        const data = await fetchDataBody(`mvc/controllers/carpetas/papelera/obtener_carpetas_papelera.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para construir la ruta de la aplicación en "Inicio"
 * @param {string} idRuta - La ruta de la carpeta de la que se desea construir la ruta de la aplicación en "Inicio"
 */
export async function construirRutaApp(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para construir la ruta de la aplicación
        const data = await fetchDataBody(`mvc/controllers/carpetas/inicio/construir_ruta_app.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para construir la ruta de la aplicacion en "Compartidos"
 * @param {string} idRuta - La ruta de la carpeta de la que se desea construir la ruta de la aplicación en "Compartidos"
 */
export async function construirRutaCompartidos(idRuta) {
    try {
        let formData = new FormData();
        formData.append('idRuta', idRuta);
        // Realizar una solicitud al servidor para construir la ruta de la aplicación
        const data = await fetchDataBody(`mvc/controllers/carpetas/compartidos/construir_ruta_compartidos.php`, formData);
        // Devolver los datos obtenidos
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asincrónica para construir la ruta para mover carpetas desde el modalBox - Mover
 * @param {string} idRuta - La ruta de la carpeta de la que se desea construir la ruta para mover carpetas
 */
export async function construirRutaMover(idRuta) {
    try {
        let formData = new FormData();
        formData.append("idRuta", idRuta);
        // Realizar una solicitud al servidor para construir la ruta para el modalBox - Mover
        const data = await fetchDataBody(
            `mvc/controllers/carpetas/inicio/construir_ruta_mover.php`,
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
 * Función para mostrar la lista de carpetas en la interfaz de usuario
 * @param {string} carpetas - El contenido HTML que representa la lista de carpetas a mostrar
 * @param {HTMLElement} $contenedorCarpetas - El elemento contenedor donde se mostrarán las carpetas
 * @param {HTMLElement} $rutaApp - El elemento donde se muestra la ruta actual en la aplicación
 */
export async function mostrarCarpetas(carpetas, $contenedorCarpetas, $rutaApp) {
    // Asignar el contenido HTML de la lista de carpetas al contenedor especificado
    $contenedorCarpetas.innerHTML = carpetas;
    if ($rutaApp) {
        // Obtener la ruta de la aplicación almacenada en la sesión
        const rutasApp = sessionStorage.getItem("rutasApp");
        // Si existe la ruta de la aplicación, se asigna en el contenedor donde va la ruta
        if (rutasApp) {
            $rutaApp.innerHTML = rutasApp;
        }
    }
}

/**
 * Función para mostrar la lista de carpetas compartidas en la interfaz de usuario
 * @param {string} carpetas - El contenido HTML que representa la lista de carpetas a mostrar
 * @param {HTMLElement} $contenedorCarpetas - El elemento contenedor donde se mostrarán las carpetas
 * @param {HTMLElement} $rutaCompartidas - El elemento donde se muestra la ruta actual de carpetas compartidas
 */
export async function mostrarCarpetasCompartidas(carpetas, $contenedorCarpetas, $rutaCompartidas) {
    // Asignar el contenido HTML de la lista de carpetas compartidas al contenedor especificado
    $contenedorCarpetas.innerHTML = carpetas;
    if ($rutaCompartidas) {
        // Obtener la ruta de la aplicación almacenada en la sesión
        const rutasCompartidas = sessionStorage.getItem("rutasCompartidos");
        // Si existe la ruta de la aplicación, se asigna en el contenedor donde va la ruta en "Compartidos"
        if (rutasCompartidas) {
            $rutaCompartidas.innerHTML = rutasCompartidas;
        }
    }
}
