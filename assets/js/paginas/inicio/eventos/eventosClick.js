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
 * Función para manejar el modo edición en Inicio
 * @param {Event} e - El evento de clic
 */
export function modoEdicion(e) {
    // Si se hace clic en el toggle
    if (e.target.matches(".toggle") || e.target.matches(".toggle_button")) {
        // Se seleccionan elementos del DOM
        let toggle = document.querySelector(".toggle");
        let texto = document.querySelector(".toggle_text");
        let modoEdicion = document.querySelector(".modo_edicion");
        let carpetas = document.querySelectorAll(".carpeta");
        let archivos = document.querySelectorAll(".archivo");
        let totalSeleccionados = document.querySelector(".total_seleccionados");

        toggle.classList.toggle("active");

        // Si tiene la clase "active", se modifica el contenido del modo edición y de ser así aparece la interfaz de modo edición
        if (toggle.classList.contains("active")) {
            texto.textContent = "Modo Edición: ON";
            modoEdicion.classList.add("active");
        } else {
            // De no ser así, se modifica el contenido del modo edición y se oculta la interfaz de modo edición
            texto.textContent = "Modo Edición: OFF";
            modoEdicion.classList.remove("active");
            // Dejan de estar seleccionadas las carpetas y los archivos
            carpetas.forEach((carpeta) => {
                carpeta.classList.remove("active");
            });
            archivos.forEach((archivo) => {
                archivo.classList.remove("active");
            });
            // El total de seleccionados pasa a ser 0
            totalSeleccionados.textContent = 0;
        }
    }
}

/**
 * Función para ocultar el modo edición
 */
export function modoOcultarEdicion() {
    // Se seleccionan elementos del DOM
    let toggle = document.querySelector(".toggle");
    let texto = document.querySelector(".toggle_text");
    let modoEdicion = document.querySelector(".modo_edicion");
    let carpetas = document.querySelectorAll(".carpeta");
    let archivos = document.querySelectorAll(".archivo");
    let totalSeleccionados = document.querySelector(".total_seleccionados");

    // Se elimina la clase "active" en el toggle
    toggle.classList.remove("active");
    texto.textContent = "Modo Edición: OFF";
    // Se oculta la interfaz de modo edición
    modoEdicion.classList.remove("active");
    // Dejan de estar seleccionadas las carpetas y los archivos
    carpetas.forEach((carpeta) => {
        carpeta.classList.remove("active");
    });
    archivos.forEach((archivo) => {
        archivo.classList.remove("active");
    });
    totalSeleccionados.textContent = 0;
}

/**
 * Función para actualizar el contador de seleccionados
 */
function actualizarContador(totalSeleccionados) {
    let cont = 0; // Reinicia el contador para una nueva cuenta
    // Se cuentan cuantas carpetas y archivos estan seleccionados
    document.querySelectorAll(".carpeta.active, .archivo.active").forEach(item => {
        cont++;
    });
    // Se asigna el resultado al total de seleccionados
    totalSeleccionados.textContent = cont;
}

/**
 * Función asíncrona para manejar el evento de doble clic en la lista de carpetas
 * @param {Event} e - El evento de doble clic
 * @param {HTMLElement} $contenedorCarpetas - El contenedor de las carpetas
 * @param {HTMLElement} $contenedorArchivos - El contenedor de los archivos
 * @param {HTMLElement} $rutaApp - El elemento que muestra la ruta actual de la aplicación
 * @param {NodeList} $inputHidden - Lista de elementos input hidden
 * @param {Function} obtenerCarpetas - Función para obtener la lista de carpetas
 * @param {Function} obtenerArchivos - Función para obtener la lista de archivos
 * @param {Function} construirRutaApp - Función que construye la ruta de la aplicación
 * @param {Function} mostrarCarpetas - Función para mostrar la lista de carpetas en la interfaz
 * @param {Function} mostrarArchivos - Función para mostrar la lista de archivos en la interfaz
 */
export async function manejarDobleClic(e, $contenedorCarpetas, $contenedorArchivos, $rutaApp, $inputHidden, obtenerCarpetas, obtenerArchivos, construirRutaApp, mostrarCarpetas, mostrarArchivos) {
    // Si se hace doble clic sobre una carpeta
    let carpeta = e.target.closest(".carpeta");
    if (carpeta) {
        // Mostrar loader
        mostrarLoader($conPrincipal);

        // Ocultar modo edicion
        modoOcultarEdicion();

        // Se obtiene el id ruta de la carpeta donde se hace doble clic
        let idRutaCompleta = carpeta.dataset.id;

        // Se asigna su id como última ruta
        sessionStorage.setItem("idUltimaRuta", idRutaCompleta);

        // Se limpian los contenedores de carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Se obtienen y muestran las carpetas y archivos de la carpeta que se ha seleccionado
        const carpetas = await obtenerCarpetas(idRutaCompleta);
        const archivos = await obtenerArchivos(idRutaCompleta);
        mostrarCarpetas(carpetas, $contenedorCarpetas, $rutaApp);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Se construye la ruta de la aplicación
        const rutaCarpeta = await construirRutaApp(idRutaCompleta);
        $rutaApp.innerHTML = await rutaCarpeta;

        // Se asigna la ruta de aplicación en la sesión
        sessionStorage.setItem("rutasApp", rutaCarpeta);

        // Se asigna en los input hidden el id de la ruta de la carpeta
        $inputHidden.forEach((input) => {
            input.value = idRutaCompleta;
        });

        // Se elimina el loader
        eliminarLoader();
    }
}

/**
 * Función asíncrona para manejar el evento de clic en la lista de carpetas y archivos
 * @param {Event} e - El evento de clic
 * @param {HTMLElement} $rutaApp - El elemento que muestra la ruta actual de la aplicación
 * @param {HTMLElement} $contenedorCarpetas - El contenedor de las carpetas
 * @param {HTMLElement} $contenedorArchivos - El contenedor de los archivos
 * @param {NodeList} $inputHidden - Lista de elementos input hidden
 * @param {Function} obtenerCarpetas - Función para obtener la lista de carpetas
 * @param {Function} obtenerArchivos - Función para obtener la lista de archivos
 * @param {Function} construirRutaApp - Función que construye la ruta de la aplicación
 * @param {Function} mostrarCarpetas - Función para mostrar la lista de carpetas en la interfaz
 * @param {Function} mostrarArchivos - Función para mostrar la lista de archivos en la interfaz
 */
export async function manejarClic(e, $rutaApp, $contenedorCarpetas, $contenedorArchivos, $inputHidden, obtenerCarpetas, obtenerArchivos, construirRutaApp, mostrarCarpetas, mostrarArchivos) {

    let carpeta = e.target.closest(".carpeta");
    let archivo = e.target.closest(".archivo");
    let toggle = document.querySelector(".toggle");
    let totalSeleccionados = document.querySelector(".total_seleccionados");

    // Si se hace clic sobre un archivo o carpeta y el modo edición esta activado
    if ((carpeta || archivo) && toggle.classList.contains("active")) {
        // Se actualiza la clase "active" en el elemento
        e.target.closest(".carpeta, .archivo").classList.toggle("active");
        // Se actualiza el contador
        actualizarContador(totalSeleccionados);
    }

    // Si se hace clic en alguna carpeta de la barra de navegación superior
    if (e.target.matches(".elemento_ruta_app")) {
        // Mostrar loader
        mostrarLoader($conPrincipal);

        // Ocultar modo edicion
        modoOcultarEdicion();

        // Obtener el id de ruta de la carpeta seleccionada
        let idRutaCompletaCarpeta = e.target.dataset.id;

        // Almacenar la última ruta en la sesión
        sessionStorage.setItem("idUltimaRuta", idRutaCompletaCarpeta);

        // Obtener la ruta actual mostrada en la aplicación
        const rutaCarpeta = await construirRutaApp(idRutaCompletaCarpeta);
        $rutaApp.innerHTML = await rutaCarpeta;

        // Almacenar la ruta mostrada en la aplicación en la sesión
        sessionStorage.setItem("rutasApp", rutaCarpeta);

        // Limpiar los contenedores de carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Obtener la lista de carpetas y archivos de la nueva ruta seleccionada
        const carpetas = await obtenerCarpetas(idRutaCompletaCarpeta);
        const archivos = await obtenerArchivos(idRutaCompletaCarpeta);

        // Mostrar las carpetas y archivos obtenidos en la interfaz
        mostrarCarpetas(carpetas, $contenedorCarpetas, $rutaApp);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Establecer la ruta seleccionada en los elementos input hidden
        $inputHidden.forEach((input) => {
            input.value = idRutaCompletaCarpeta;
        });

        eliminarLoader();
    }

    // Si se elige "Unidad" en la barra de navegación superior
    if (e.target.matches(".ruta_unidad")) {
        e.preventDefault();

        // Mostrar loader
        mostrarLoader($conPrincipal);

        // Ocultar modo edicion
        modoOcultarEdicion();

        // Obtener la ruta de la unidad de usuario
        let idRutaUnidadUsuario = e.target.dataset.id;

        // Almacenar la última ruta en la sesión
        sessionStorage.setItem("idUltimaRuta", idRutaUnidadUsuario);

        // Construir la ruta de la aplicación
        const rutaCarpeta = await construirRutaApp(idRutaUnidadUsuario);
        $rutaApp.innerHTML = await rutaCarpeta;

        // Almacenar la ruta mostrada en la aplicación en la sesión
        sessionStorage.setItem("rutasApp", rutaCarpeta);
        // Limpiar los contenedores de carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Obtener la lista de carpetas y archivos de la unidad de usuario
        const carpetas = await obtenerCarpetas(idRutaUnidadUsuario);
        const archivos = await obtenerArchivos(idRutaUnidadUsuario);

        // Mostrar las carpetas y archivos obtenidos en la interfaz
        mostrarCarpetas(carpetas, $contenedorCarpetas, $rutaApp);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Establecer la ruta seleccionada en los elementos input hidden
        $inputHidden.forEach((input) => {
            input.value = idRutaUnidadUsuario;
        });

        // Se elimina el loader
        eliminarLoader();
    }
}


/**
 * Función asíncrona para manejar el evento de clic en el movil
 * @param {Event} e - El evento de doble clic
 * @param {HTMLElement} $contenedorCarpetas - El contenedor de las carpetas
 * @param {HTMLElement} $contenedorArchivos - El contenedor de los archivos
 * @param {HTMLElement} $rutaApp - El elemento que muestra la ruta actual de la aplicación
 * @param {NodeList} $inputHidden - Lista de elementos input hidden
 * @param {Function} obtenerCarpetas - Función para obtener la lista de carpetas
 * @param {Function} obtenerArchivos - Función para obtener la lista de archivos
 * @param {Function} construirRutaApp - Función que construye la ruta de la aplicación
 * @param {Function} mostrarCarpetas - Función para mostrar la lista de carpetas en la interfaz
 * @param {Function} mostrarArchivos - Función para mostrar la lista de archivos en la interfaz
 */
export async function manejarClicMovil(e, $contenedorCarpetas, $contenedorArchivos, $rutaApp, $inputHidden, obtenerCarpetas, obtenerArchivos, construirRutaApp, mostrarCarpetas, mostrarArchivos) {
    // Si se hace doble clic sobre una carpeta
    let carpeta = e.target.closest(".carpeta");
    if (carpeta) {
        // Mostrar loader
        mostrarLoader($conPrincipal);

        // Ocultar modo edicion
        modoOcultarEdicion();

        // Se obtiene el id ruta de la carpeta donde se hace doble clic
        let idRutaCompleta = carpeta.dataset.id;

        // Se asigna su id como última ruta
        sessionStorage.setItem("idUltimaRuta", idRutaCompleta);

        // Se limpian los contenedores de carpetas y archivos
        $contenedorCarpetas.replaceChildren();
        $contenedorArchivos.replaceChildren();

        // Se obtienen y muestran las carpetas y archivos de la carpeta que se ha seleccionado
        const carpetas = await obtenerCarpetas(idRutaCompleta);
        const archivos = await obtenerArchivos(idRutaCompleta);
        mostrarCarpetas(carpetas, $contenedorCarpetas, $rutaApp);
        mostrarArchivos(archivos, $contenedorArchivos);

        // Se construye la ruta de la aplicación
        const rutaCarpeta = await construirRutaApp(idRutaCompleta);
        $rutaApp.innerHTML = await rutaCarpeta;

        // Se asigna la ruta de aplicación en la sesión
        sessionStorage.setItem("rutasApp", rutaCarpeta);

        // Se asigna en los input hidden el id de la ruta de la carpeta
        $inputHidden.forEach((input) => {
            input.value = idRutaCompleta;
        });

        // Se elimina el loader
        eliminarLoader();
    }
}

