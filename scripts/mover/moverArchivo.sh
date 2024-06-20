#!/bin/bash

# SCRIPT PARA MOVER UN ARCHIVO

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 2 ]; then
    echo "No se han pasado los parametros necesarios"
    exit 1
fi

# Obtener la ruta del archivo y la carpeta donde se va a mover
ruta_origen="$1"
ruta_destino="$2"

# Verificar si el archivo de origen existe
if [ ! -f "${ruta_origen}" ]; then
    echo "Archivo no existe"
    exit 1
fi

directorio_padre=$(dirname "${ruta_destino}")

# Verificar si la ruta de destino es un directorio válido
if [ ! -d "${directorio_padre}" ]; then
    echo "Directorio no existe"
    exit 1
fi

# Mover el archivo a la nueva ubicación
mv "${ruta_origen}" "${ruta_destino}" > /dev/null 2>&1

# Comprobar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al mover archivo en la carpeta"
    exit 1
fi

