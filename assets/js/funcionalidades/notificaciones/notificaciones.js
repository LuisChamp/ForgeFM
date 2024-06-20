/**
 * Función que muestra una notificación tipo toast en la interfaz de usuario
 * @param {string} tipo - El tipo de notificación
 * @param {string} toastText - La estructura de la notificación
 */
export function mostrarNotificacion(tipo, toastText) {
    // Obtener el contenedor de las notificaciones toast
    const toastBox = document.getElementById("toastBox");

    // Crear un nuevo elemento div para la notificación
    let toast = document.createElement("div");

    // Añadir clases al elemento div para aplicar estilos según el tipo de notificación
    toast.classList.add("toast");
    toast.classList.add(tipo);

    // Establecer la estructura de la notificación
    toast.innerHTML = toastText;

    // Agregar la notificación al contenedor
    toastBox.appendChild(toast);

    // Eliminar la notificación después de 4 segundos
    setTimeout(() => {
        toast.remove();
    }, 4000);
}
