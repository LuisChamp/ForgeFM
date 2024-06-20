#!/bin/bash

# SCRIPT PARA CAMBIAR DE NOMBRE A UNA CARPETA

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 2 ]; then
    echo "No se proporcionan los 2 argumentos"
    exit 1
fi

# Obtener la ruta de la carpeta y el nuevo nombre de la carpeta
ruta_carpeta="$1"
nuevo_nombre="$2"

# Verificar si la carpeta existe
if [ ! -d "${ruta_carpeta}" ]; then
    echo "Carpeta no existe"
    exit 1
fi

# Obtener el directorio padre y el nombre de la carpeta del argumento de ruta
directorio_padre=$(dirname "${ruta_carpeta}")

# Construir la ruta de la nueva carpeta
nueva_ruta="${directorio_padre}/${nuevo_nombre}"

# Verificar si ya existe una carpeta con el nuevo nombre
if [ -e "${nueva_ruta}" ]; then
    echo "Carpeta con nuevo nombre ya existe"
    exit 1
fi

# Cambiar el nombre de la carpeta
mv "${ruta_carpeta}" "${nueva_ruta}" > /dev/null 2>&1

# Comprobar si el cambio de nombre fue exitoso
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al cambiar nombre de carpeta"
    exit 1
fi

