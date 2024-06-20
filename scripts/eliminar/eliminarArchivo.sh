#!/bin/bash

# SCRIPT PARA ELIMINAR UN ARCHIVO

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 1 ]; then
    echo "No se ha proporcionado el parametro necesario"
    exit 1
fi

# Obtener la ruta del archivo
ruta_archivo="$1"

# Verificar si el archivo existe
if [ ! -f "${ruta_archivo}" ]; then
    echo "Archivo no existe"
    exit 1
fi

# Eliminar el archivo
rm "${ruta_archivo}" > /dev/null 2>&1

# Comprobar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al eliminar archivo"
    exit 1
fi

