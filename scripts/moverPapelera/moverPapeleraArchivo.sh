#!/bin/bash

# SCRIPT PARA MOVER UN ARCHIVO A LA PAPELERA

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 4 ]; then
    echo "No se han pasado los parametros necesarios"
    exit 1
fi

# Obtener la ruta del archivo, su id, nombre del propietario y el nombre del archivo
ruta_archivo="$1"
nombre_usuario="$2"
id_archivo="$3"
nombre_archivo="$4"

ruta_papelera="/gestor/usuarios/${nombre_usuario}/papelera"
nuevo_nombre="${id_archivo}_${nombre_archivo}"

# Verificar si el archivo existe
if [ ! -f "${ruta_archivo}" ]; then
    echo "Archivo no existe"
    exit 1
fi

# Verificar si la ruta de papelera es un directorio válido
if [ ! -d "${ruta_papelera}" ]; then
    echo "Papelera no existe"
    exit 1
fi

# Mover el archivo a la papelera
mv "${ruta_archivo}" "${ruta_papelera}/${nuevo_nombre}" > /dev/null 2>&1

# Comprobar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al mover archivo a papelera"
    exit 1
fi

