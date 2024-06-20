#!/bin/bash

# SCRIPT PARA CAMBIAR DE NOMBRE A UN ARCHIVO

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 2 ]; then
    echo "No se han pasado los parametros necesarios"
    exit 1
fi

# Obtener la ruta del archivo y el nuevo nombre del archivo
ruta_archivo="$1"
nuevo_nombre="$2"

# Verificar si el archivo existe
if [ ! -f "${ruta_archivo}" ]; then
    echo "Archivo no existe"
    exit 1
fi

# Obtener el directorio y el nombre del archivo del argumento de ruta
directorio=$(dirname "${ruta_archivo}")

# Construir la ruta del nuevo archivo
nueva_ruta="${directorio}/${nuevo_nombre}"

# Verificar si ya existe un archivo con el nuevo nombre
if [ -e "${nueva_ruta}" ]; then
    echo "Ya existe un archivo con ese nombre"
    exit 1
fi

# Cambiar el nombre del archivo
mv "${ruta_archivo}" "${nueva_ruta}" > /dev/null 2>&1

# Comprobar si el cambio de nombre fue exitoso
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al cambiar nombre de archivo"
    exit 1
fi


