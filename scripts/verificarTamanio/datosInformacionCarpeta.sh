#!/bin/bash

# SCRIPT PARA OBTENER INFORMACIÓN DE UNA CARPETA

# Verificar si se proporcionó una ruta como argumento
if [ $# -ne 1 ]; then
    echo "No se ha pasado el parámetro necesario"
    exit 1
fi

# Obtener la ruta de la carpeta proporcionada como argumento
carpeta="$1"

# Verificar si la carpeta existe
if [ ! -d "$carpeta" ]; then
  echo "Carpeta no existe"
  exit 1
fi

# Calcular la cantidad de archivos y subcarpetas en la carpeta
cantidad_archivos=$(find "$carpeta" -type f | wc -l)
cantidad_subcarpetas=$(find "$carpeta" -mindepth 1 -type d | wc -l)

# Calcular el tamaño de la carpeta del usuario en bytes
tamanio_total_bytes=$(du -sb "$carpeta" | awk '{print $1}')

# Función para convertir bytes a una unidad adecuada
convertir_tamanio() {
    # Definir el tamaño y la unidad predeterminados
    local tamanio=$1
    local unidad="B"

    # Comprobar si el tamaño es mayor o igual a 1 GB
    if [ $tamanio -ge 1073741824 ]; then
        # Si es así, convertir el tamaño a gigabytes y actualizar la unidad
        tamanio=$(echo "scale=2; $tamanio / 1073741824" | bc)
        unidad="GB"
    # Si el tamaño es menor que 1 GB, pero mayor o igual a 1 MB
    elif [ $tamanio -ge 1048576 ]; then
        # Convertir el tamaño a megabytes y actualizar la unidad
        tamanio=$(echo "scale=2; $tamanio / 1048576" | bc)
        unidad="MB"
    # Si el tamaño es menor que 1 MB, pero mayor o igual a 1 KB
    elif [ $tamanio -ge 1024 ]; then
        # Convertir el tamaño a kilobytes y actualizar la unidad
        tamanio=$(echo "scale=2; $tamanio / 1024" | bc)
        unidad="KB"
    fi

    echo "$tamanio $unidad"
}

# Convertir el tamaño total de bytes a la unidad adecuada
tamanio_total=$(convertir_tamanio $tamanio_total_bytes)

# Generar el JSON
{
  echo "{"
  echo "  \"cantidad_archivos\": $cantidad_archivos,"
  echo "  \"cantidad_subcarpetas\": $cantidad_subcarpetas,"
  echo "  \"tamanio_total\": \"$tamanio_total\""
  echo "}"
} | jq .
