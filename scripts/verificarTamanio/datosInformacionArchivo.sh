#!/bin/bash

# SCRIPT PARA OBTENER INFORMACIÓN DE UN ARCHIVO

# Verificar si se proporcionó una ruta como argumento
if [ $# -ne 1 ]; then
    echo "No se ha pasado el parametro necesario"
    exit 1
fi

# Obtener la ruta del archivo proporcionada como argumento
archivo="$1"

# Verificar si el archivo existe
if [ ! -f "$archivo" ]; then
  echo "Archivo no existe"
  exit 1
fi

# Obtener tamaño del archivo en formato legible
tamanio_archivo=$(du -h "$archivo" | cut -f1)

# Generar el JSON
{
  echo "{"
  echo "  \"tamanio_archivo\": \"$tamano_archivo\""
  echo "}"
} | jq .

