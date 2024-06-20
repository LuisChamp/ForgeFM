#!/bin/bash

# SCRIPT PARA CAMBIAR DE NOMBRE A UN ARCHIVO EN PAPELERA

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 3 ]; then
    echo "No se han proporcionado los parametros necesarios"
    exit 1
fi

# Obtener la ruta del archivo y el nuevo nombre del archivo
ruta_archivo="$1"
nuevo_nombre="$2"
id_papelera="$3"

# Verificar si el archivo existe
if [ ! -f "${ruta_archivo}" ]; then
    echo "Archivo no existe"
    exit 1
fi

# Obtener el directorio padre y el nombre de la carpeta del argumento de ruta
directorio_padre=$(dirname "${ruta_archivo}")

# Construir la ruta del nuevo archivo
nueva_ruta="${directorio_padre}/${id_papelera}_${nuevo_nombre}"

# Verificar si ya existe un archivo con el nuevo nombre
if [ -e "${nueva_ruta}" ]; then
    echo "Archivo ya existe"
    exit 1
fi

# Cambiar el nombre del archivo
mv "${ruta_archivo}" "${nueva_ruta}" > /dev/null 2>&1

# Comprobar si el cambio de nombre fue exitoso
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al cambiar nombre archivo en papelera"
    exit 1
fi

