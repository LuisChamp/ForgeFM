#!/bin/bash

# SCRIPT PARA MOVER UNA CARPETA

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 2 ]; then
    echo "No se han pasado los parametros necesarios"
    exit 1
fi

# Obtener la ruta de la carpeta de origen y destino
ruta_origen="$1"
ruta_destino="$2"

# Verificar si la carpeta de origen existe
if [ ! -d "${ruta_origen}" ]; then
    echo "Carpeta origen no existe"
    exit 1
fi

directorio_padre=$(dirname "${ruta_destino}")

# Verificar si la ruta de destino es un directorio válido
if [ ! -d "${directorio_padre}" ]; then
    echo "Carpeta destino no existe"
    exit 1
fi

# Mover la carpeta y todo su contenido a la nueva ubicación
mv "${ruta_origen}" "${ruta_destino}" > /dev/null 2>&1

# Comprobar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Mover carpeta fallida"
    exit 1
fi

