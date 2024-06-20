const $conPrincipal = document.querySelector(".con_principal");

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
 * Función asíncrona para manejar el evento de doble clic en la lista de carpetas
 * @param {Event} e - El evento de doble clic
 * @param {HTMLElement} $contenedorCarpetas - El contenedor de las carpetas
 * @param {HTMLElement} $contenedorArchivos - El contenedor de los archivos
 * @param {HTMLElement} $rutaCompartidos - El elemento que muestra la ruta actual de la aplicación en compartidos
 * @param {Function} obtenerCarpetasCompartidas - Función para obtener la lista de carpetas
 * @param {Function} obtenerArchivosCompartidos - Función para obtener la lista de archivos
 * @param {Function} construirRutaCompartidos - Función para construir ruta de aplicación en Compartidos
 * @param {Function} mostrarCarpetasCompartidas - Función para mostrar la lista de carpetas en la interfaz
 * @param {Function} mostrarArchivos - Función para mostrar la lista de archivos en la interfaz
 */
export async function manejarDobleClic(e, $contenedorCarpetas, $contenedorArchivos, $rutaCompartidos, obtenerCarpetasCompartidas, obtenerArchivosCompartidos, construirRutaCompartidos, mostrarCarpetasCompartidas, mostrarArchivos) {
    // Si se elige una carpeta
    let carpeta = e.target.closest(".carpeta");
    if (carpeta) {
        //  Mostrar loader
        mostrarLoader($conPrincipal);

        // Se obtiene su id
        let idRutaCompleta = carpeta.dataset.id;

        // Se asigna su id como última ruta en compartidos
        sessionStorage.setItem("idUltimaRutaCompartidos", idRutaCompleta);

        // Se elimina el contenido de las carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Se obtienen las carpetas y archivos de la carpeta elegida
        const carpetas = await obtenerCarpetasCompartidas(idRutaCompleta);
        const archivos = await obtenerArchivosCompartidos(idRutaCompleta);

        // Se muestran las carpetas y archivos en la interfaz
        mostrarCarpetasCompartidas(carpetas, $contenedorCarpetas, $rutaCompartidos);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Se construye la ruta de la aplicación en Compartidos
        const rutaCarpeta = await construirRutaCompartidos(idRutaCompleta);
        $rutaCompartidos.innerHTML = await rutaCarpeta;

        // Se asigna la ruta de la aplicación en la rutaCompartidos de la sesión
        sessionStorage.setItem("rutasCompartidos", rutaCarpeta);

        // Se elimina el loader
        eliminarLoader();
    }
}

/**
 * Función asíncrona para manejar el evento de clic en la lista de carpetas y archivos
 * @param {Event} e - El evento de clic
 * @param {HTMLElement} $rutaCompartidos - El elemento que muestra la ruta actual de la aplicación en compartidos
 * @param {HTMLElement} $contenedorCarpetas - El contenedor de las carpetas
 * @param {HTMLElement} $contenedorArchivos - El contenedor de los archivos
 * @param {Function} obtenerCarpetasCompartidas - Función para obtener la lista de carpetas
 * @param {Function} obtenerArchivosCompartidos - Función para obtener la lista de archivos
 * @param {Function} construirRutaCompartidos - Función para construir ruta de aplicación en Compartidos
 * @param {Function} mostrarCarpetasCompartidas - Función para mostrar la lista de carpetas en la interfaz
 * @param {Function} mostrarArchivos - Función para mostrar la lista de archivos en la interfaz
 */
export async function manejarClic(e, $rutaCompartidos, $contenedorCarpetas, $contenedorArchivos, obtenerCarpetasCompartidas, obtenerArchivosCompartidos, construirRutaCompartidos, mostrarCarpetasCompartidas, mostrarArchivos) {
    // Si se hace clic en alguna carpeta de la barra de navegación superior
    if (e.target.matches(".elemento_ruta_app")) {

        // Mostrar loader
        mostrarLoader($conPrincipal);

        // Obtener la ruta completa de la carpeta seleccionada
        let idRutaCompletaCarpeta = e.target.dataset.id;

        // Almacenar la última ruta en la sesión
        sessionStorage.setItem("idUltimaRutaCompartidos", idRutaCompletaCarpeta);

        // Obtener la ruta actual mostrada en la aplicación
        const rutaCarpeta = await construirRutaCompartidos(idRutaCompletaCarpeta);
        $rutaCompartidos.innerHTML = await rutaCarpeta;

        // Almacenar la ruta mostrada en la aplicación en la sesión
        sessionStorage.setItem("rutasCompartidos", rutaCarpeta);

        // Limpiar los contenedores de carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Obtener la lista de carpetas y archivos de la nueva ruta seleccionada
        const carpetas = await obtenerCarpetasCompartidas(idRutaCompletaCarpeta);
        const archivos = await obtenerArchivosCompartidos(idRutaCompletaCarpeta);

        // Mostrar las carpetas y archivos obtenidos en la interfaz
        mostrarCarpetasCompartidas(carpetas, $contenedorCarpetas, $rutaCompartidos);
        mostrarArchivos(archivos, $contenedorArchivos);

        eliminarLoader();
    }

    // Si se elige "Compartidos" en la barra de navegación superior
    if (e.target.matches(".ruta_compartidos")) {
        e.preventDefault();

        // Mostrar loader
        mostrarLoader($conPrincipal);

        // Obtener la ruta de la unidad de usuario
        let idRutaUnidadUsuario = e.target.dataset.id;

        // Almacenar la última ruta en la sesión
        sessionStorage.setItem("idUltimaRutaCompartidos", idRutaUnidadUsuario);

        // Construir la ruta de la aplicación
        const rutaCarpeta = await construirRutaCompartidos(idRutaUnidadUsuario);
        $rutaCompartidos.innerHTML = await rutaCarpeta;

        // Almacenar la ruta mostrada en la aplicación en la sesión
        sessionStorage.setItem("rutasCompartidos", rutaCarpeta);
        // Limpiar los contenedores de carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Obtener la lista de carpetas y archivos de la unidad de usuario
        const carpetas = await obtenerCarpetasCompartidas(idRutaUnidadUsuario);
        const archivos = await obtenerArchivosCompartidos(idRutaUnidadUsuario);

        // Mostrar las carpetas y archivos obtenidos en la interfaz
        mostrarCarpetasCompartidas(carpetas, $contenedorCarpetas, $rutaCompartidos);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Se elimina el loader
        eliminarLoader();
    }

    // Si se elige "cerrar sesión"
    if (e.target.matches(".btn_cerrar_sesion")) {
        e.preventDefault();
        // Se limpia la sesión
        sessionStorage.clear();
        // Se redirecciona al usuario al archivo donde se cierra la sesión
        window.location.href =
            "mvc/controllers/usuarios/credenciales/cerrar_sesion.php?sesion=true";
    }
}


export async function manejarClicMovil(e, $contenedorCarpetas, $contenedorArchivos, $rutaCompartidos, obtenerCarpetasCompartidas, obtenerArchivosCompartidos, construirRutaCompartidos, mostrarCarpetasCompartidas, mostrarArchivos) {
    // Si se elige una carpeta
    let carpeta = e.target.closest(".carpeta");
    if (carpeta) {
        //  Mostrar loader
        mostrarLoader($conPrincipal);

        // Se obtiene su id
        let idRutaCompleta = carpeta.dataset.id;

        // Se asigna su id como última ruta en compartidos
        sessionStorage.setItem("idUltimaRutaCompartidos", idRutaCompleta);

        // Se elimina el contenido de las carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Se obtienen las carpetas y archivos de la carpeta elegida
        const carpetas = await obtenerCarpetasCompartidas(idRutaCompleta);
        const archivos = await obtenerArchivosCompartidos(idRutaCompleta);

        // Se muestran las carpetas y archivos en la interfaz
        mostrarCarpetasCompartidas(carpetas, $contenedorCarpetas, $rutaCompartidos);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Se construye la ruta de la aplicación en Compartidos
        const rutaCarpeta = await construirRutaCompartidos(idRutaCompleta);
        $rutaCompartidos.innerHTML = await rutaCarpeta;

        // Se asigna la ruta de la aplicación en la rutaCompartidos de la sesión
        sessionStorage.setItem("rutasCompartidos", rutaCarpeta);

        // Se elimina el loader
        eliminarLoader();
    }
}