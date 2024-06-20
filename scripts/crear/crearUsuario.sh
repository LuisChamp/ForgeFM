#!/bin/bash

# SCRIPT PARA CREAR CARPETAS DE UN USUARIO NUEVO

# Carpeta raiz
carpeta_raiz="/gestor/usuarios"
# Obtener la ruta completa de la carpeta de usuario a partir del primer argumento
carpeta_usuario="$carpeta_raiz/$1"
# Definir las rutas de las carpetas 'carpetas' y 'papelera' dentro de la carpeta del usuario
carpeta_carpetas="$carpeta_usuario/carpetas"
carpeta_papelera="$carpeta_usuario/papelera"

# Función para crear la carpeta con permisos y propietario adecuados
crear_carpeta() {
    local carpeta=$1
    # Crear la carpeta con permisos 775 y propietario www-data:www-data
    mkdir -m 775 -p "$carpeta" && chown www-data:www-data "$carpeta" > /dev/null 2>&1
}

# Función para verificar si una carpeta existe y crearla si no
verificar_o_crear_carpeta() {
    local carpeta=$1
    if [ ! -d "$carpeta" ]; then
        crear_carpeta "$carpeta"
        if [ $? -ne 0 ]; then
            echo "error al crear carpeta $carpeta"
            exit 1
        fi
    fi
}

# Verificar y crear la carpeta raíz, de usuario, 'carpetas' y 'papelera' si no existen
verificar_o_crear_carpeta "$carpeta_raiz"
verificar_o_crear_carpeta "$carpeta_usuario"
verificar_o_crear_carpeta "$carpeta_carpetas"
verificar_o_crear_carpeta "$carpeta_papelera"

# Si todo ha ido bien, imprimir "0"
echo "0"
