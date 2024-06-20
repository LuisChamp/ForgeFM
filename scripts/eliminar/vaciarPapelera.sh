#!/bin/bash

# SCRIPT PARA VACIAR LA PAPELERA

# Obtenemos la cadena de rutas pasada como argumento
cadenas_rutas="$1"

# Verificamos si la cadena de rutas está vacía
if [ -z "$cadenas_rutas" ]; then
    echo "La cadena de rutas esta vacia"
    exit 1
fi

# Convertimos la cadena en un array
IFS=',' read -r -a rutas <<< "$cadenas_rutas"

# Iteramos sobre cada ruta y realizamos la eliminación
for ruta in "${rutas[@]}"; do
    # Realizamos la eliminación para cada ruta
    rm -rf "$ruta" > /dev/null 2>&1

    if [ $? -ne 0 ]; then
        echo "Error al eliminar elemento"
        exit 1
    fi
done

# Se ha vaciado la papelera en el servidor
echo "0"
