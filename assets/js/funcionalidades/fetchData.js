/**
 * Función asíncrona que realiza una solicitud
 * @param {string} url - La URL a la que se realizará la solicitud
 * @param {FormData} formData - Los datos a enviar en el cuerpo de la solicitud
 * @returns {Promise<any>} - La respuesta de la solicitud en formato JSON
 */
export async function fetchDataBody(url, formData) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Hubo un error al realizar la solicitud:', error);
        throw error;
    }
}

/**
 * Función asíncrona que realiza una solicitud sin enviar datos en el cuerpo
 * @param {string} url - La URL a la que se realizará la solicitud
 * @returns {Promise<any>} - La respuesta de la solicitud en formato JSON
 */
export async function fetchData(url) {
    try {
        const response = await fetch(url, {
            method: 'POST'
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Hubo un error al realizar la solicitud:', error);
        throw error;
    }
}
