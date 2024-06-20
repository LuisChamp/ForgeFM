/**
 * Función asíncrona para obtener la información del usuario actual
 * @returns {Promise<Object>} - La información del usuario actual
 */
export async function obtenerUsuario() {
    try {
        // Realizar una solicitud al servidor para obtener la información del usuario
        const response = await fetch("mvc/controllers/usuarios/obtener_usuario.php");
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            // Si la respuesta no es exitosa, lanzar un error
            throw new Error('Error en la solicitud');
        }
        // Convertir la respuesta a formato JSON
        const data = await response.json();
        // Retornar la información del usuario
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}

/**
 * Función asíncrona para obtener las rutas del usuario
 * @returns {Promise<Object>} - Las rutas del usuario
 */
export async function obtenerRutasUsuario() {
    try {
        // Realizar una solicitud al servidor para obtener las rutas del usuario
        const response = await fetch("mvc/controllers/usuarios/obtener_rutas_usuario.php");
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            // Si la respuesta no es exitosa, lanzar un error
            throw new Error('Error en la solicitud');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error(error);
    }
}
