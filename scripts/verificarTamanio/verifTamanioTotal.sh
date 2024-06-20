#!/bin/bash

# SCRIPT PARA OBTENER EL TAMAÑO EN BYTES DE UNA CARPETA Y SUS ELEMENTOS

# Obtener la ruta de la carpeta del usuario
carpeta="$1"

# Obtener el tamaño en bytes de la carpeta y sus subelementos usando el comando 'du'

# '-sb' muestra el tamaño total en bytes y '-f1' extrae la primera columna que contiene el tamaño en bytes
tamanio=$(du -sb "$carpeta" | cut -f1)

# Imprimir el tamaño en bytes de la carpeta y sus subelementos
echo $tamanio
