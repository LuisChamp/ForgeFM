import { fetchDataBody } from "../fetchData.js";

/**
 * Función asincrónica para obtener los datos de almacenamiento
 * @returns {Promise<Object>} - Los datos de almacenamiento que se han obtenido
 */
export async function obtenerDatosAlmacenamientoPeticion() {
    try {
        // Obtener el idRuta de la carpeta raíz desde sessionStorage
        const idRuta = sessionStorage.getItem("carpeta");

        // Verificar si idRuta existe
        if (idRuta) {
            let url = "mvc/controllers/almacenamiento/datos_almacenamiento.php";
            let formData = new FormData();

            // Agregar idRuta al objeto formData
            formData.append("idRuta", idRuta);
            
            // Realizar la solicitud al servidor
            const data = await fetchDataBody(url, formData);

            // Devolver los datos obtenidos
            return data;
        }

    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        throw error;
    }
}

/**
 * Función para convertir un tamaño en diferentes unidades a bytes
 * @param {string} tamanio - El tamaño a convertir
 * @returns {number} - El tamaño en bytes
 */
function convertirATamanioEnBytes(tamanio) {
    const unidades = {
        B: 1,
        KB: 1024,
        K: 1024,
        MB: 1024 * 1024,
        M: 1024 * 1024,
        GB: 1024 * 1024 * 1024,
        G: 1024 * 1024 * 1024,
        TB: 1024 * 1024 * 1024 * 1024,
        T: 1024 * 1024 * 1024 * 1024
    };

    // Expresión regular para extraer el valor y la unidad del tamaño
    const regex = /([\d.]+)\s*(B|K|KB|M|MB|G|GB|T|TB)/i;
    const match = tamanio.match(regex);

    // Extraer el valor numérico y la unidad del tamaño
    const valor = parseFloat(match[1]);
    const unidad = match[2].toUpperCase();

    // Convertir el valor a bytes utilizando la unidad correspondiente
    return valor * (unidades[unidad] || 1);
}

/**
 * Función para calcular el porcentaje de almacenamiento usado
 * @param {string} tamanioTotal - El tamaño total de almacenamiento 
 * @param {string} tamanioUsado - El tamaño usado de almacenamiento
 * @returns {string} - El porcentaje de almacenamiento usado, redondeado a dos decimales
 */
function calcularPorcentajeUsado(tamanioTotal, tamanioUsado) {
    // Convertir el tamaño total y el tamaño usado a bytes
    const totalEnBytes = convertirATamanioEnBytes(tamanioTotal);
    const usadoEnBytes = convertirATamanioEnBytes(tamanioUsado);

    // Calcular el porcentaje usado
    const porcentajeUsado = (usadoEnBytes / totalEnBytes) * 100;
    // Redondear a dos decimales
    return porcentajeUsado.toFixed(2); 
}

/**
 * Función principal para obtener y actualizar los datos de almacenamiento.
 */
export async function obtenerDatosAlmacenamiento() {
    try {
        // Obtener los datos de almacenamiento desde el servidor
        const respuesta = await obtenerDatosAlmacenamientoPeticion();

        // Parsear los datos obtenidos
        const data = JSON.parse(respuesta.datosAlmacenamiento);

        // Seleccionar elementos del DOM
        const almacenamientoTotal = document.querySelector(".almacenamiento_total");
        const tamanioTotal = document.querySelector(".almacenamiento_tamanio_usado");
        const cantidadDocumentos = document.querySelector(".almacenamiento_cantidad_documentos");
        const cantidadImagenes = document.querySelector(".almacenamiento_cantidad_imagenes");
        const cantidadVideos = document.querySelector(".almacenamiento_cantidad_videos");
        const cantidadOtros = document.querySelector(".almacenamiento_cantidad_otros");
        const tamanioDocumentos = document.querySelector(".con_documentos_superior_derecha");
        const tamanioImagenes = document.querySelector(".con_imagenes_superior_derecha");
        const tamanioVideos = document.querySelector(".con_videos_superior_derecha");
        const tamanioOtros = document.querySelector(".con_otros_superior_derecha");
        const porcentajeTamanio = document.querySelector(".barra_progreso_almacenamiento_color");

        // Actualizar el contenido de los elementos del DOM con los datos obtenidos
        almacenamientoTotal.textContent = `${respuesta.almacenamientoTotal}GB`;
        tamanioTotal.textContent = data.tamanio_total_carpeta;
        cantidadDocumentos.textContent = data.documentos.cantidad;
        cantidadImagenes.textContent = data.imagenes.cantidad;
        cantidadVideos.textContent = data.videos.cantidad;
        cantidadOtros.textContent = data.varios.cantidad;
        tamanioDocumentos.textContent = data.documentos.tamanio;
        tamanioImagenes.textContent = data.imagenes.tamanio;
        tamanioVideos.textContent = data.videos.tamanio;
        tamanioOtros.textContent = data.varios.tamanio;

        // Calcular y actualizar el porcentaje de almacenamiento usado
        const porcentaje = calcularPorcentajeUsado(almacenamientoTotal.textContent, tamanioTotal.textContent);
        porcentajeTamanio.style.width = `${porcentaje}%`;

    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}
