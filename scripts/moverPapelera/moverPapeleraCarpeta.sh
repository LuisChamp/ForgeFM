#!/bin/bash

# SCRIPT PARA MOVER UNA CARPETA A LA PAPELERA

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 4 ]; then
    echo "No se proporcionaron los parametros necesarios"
    exit 1
fi

# Obtener la ruta de la carpeta, su id, nombre del propietario y el nombre de la carpeta
ruta_carpeta="$1"
nombre_usuario="$2"
id_carpeta="$3"
nombre_carpeta="$4"

ruta_papelera="/gestor/usuarios/${nombre_usuario}/papelera"
nuevo_nombre="${id_carpeta}_${nombre_carpeta}"

# Verificar si la carpeta existe
if [ ! -d "${ruta_carpeta}" ]; then
    echo "Carpeta no existe"
    exit 1
fi

# Verificar si la ruta de la papelera es un directorio válido
if [ ! -d "${ruta_papelera}" ]; then
    echo "Papelera no existe"
    exit 1
fi

# Mover la carpeta y su contenido a la papelera
mv "${ruta_carpeta}" "${ruta_papelera}/${nuevo_nombre}" > /dev/null 2>&1

# Comprobar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al mover carpeta a la papelera"
    exit 1
fi

