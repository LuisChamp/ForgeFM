#!/bin/bash

# SCRIPT PARA ELIMINAR UN USUARIO

# Verificar que se proporcionaron los argumentos correctos
if [ $# -ne 1 ]; then
    echo "No se ha proporcionado el parametro necesario"
    exit 1
fi

# Obtener la ruta de la carpeta del usuario (carpeta raiz)
ruta_carpeta="/gestor/usuarios/$1"

# Verificar si la carpeta existe
if [ ! -d "${ruta_carpeta}" ]; then
    echo "Carpeta no existe"
    exit 1
fi

# Eliminar la carpeta recursivamente
rm -rf "${ruta_carpeta}" > /dev/null 2>&1

# Comprobar si la operación fue exitosa
if [ $? -eq 0 ]; then
    echo "0"
else
    echo "Error al eliminar carpeta de usuario"
    exit 1
fi

