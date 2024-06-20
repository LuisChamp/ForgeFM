#!/bin/bash

# SCRIPT PARA CREAR UNA NUEVA CARPETA

# Crear la carpeta utilizando el primer argumento pasado al script como nombre de la carpeta
mkdir "$1" > /dev/null 2>&1

# Verificar si el comando mkdir se ejecutó correctamente
if [ $? -eq 0 ]; then
    # Si la carpeta se creó exitosamente, imprimir "0" indicando éxito
    echo "0"
else
    # Si hubo algún error al crear la carpeta, imprimir "1" indicando fallo
    echo "1"
fi
