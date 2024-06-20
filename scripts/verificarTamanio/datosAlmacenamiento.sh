#!/bin/bash

# SCRIPT PARA OBTENER DATOS DE ALMACENAMIENTO

# Verificar si se proporciona una ruta como argumento
if [ -z "$1" ]; then
  echo "No se proporciona la carpeta del usuario"
  exit 1
fi

ruta=$1

# Verificar si la ruta proporcionada existe
if [ ! -d "$ruta" ]; then
  echo "La carpeta del usuario no existe"
  exit 1
fi

# Función para calcular la cantidad y tamaño de archivos según el tipo
calcular_archivos() {
  # Obtener el arreglo de extensiones como parámetro
  extensiones=("${!2}")  
  nombre_tipo=$3

  # Contar la cantidad de archivos y calcular el tamaño total
  num_archivos=$(find "$ruta" -type f \( "${extensiones[@]}" \) | wc -l)
  if [ "$num_archivos" -gt 0 ]; then
    tamanio_total=$(find "$ruta" -type f \( "${extensiones[@]}" \) -exec du -ch {} + | grep 'total$' | awk '{print $1}')
  else
    tamanio_total="0"
  fi

  # Imprimir el resultado en formato JSON
  echo "\"$nombre_tipo\": {\"cantidad\": $num_archivos, \"tamanio\": \"$tamanio_total\"}"
}

# Preparar extensiones para diferentes tipos de archivos
doc_extensions=(-iname '*.doc' -o -iname '*.docx' -o -iname '*.pdf' -o -iname '*.txt')
img_extensions=(-iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' -o -iname '*.gif' -o -iname '*.bmp')
vid_extensions=(-iname '*.mp4' -o -iname '*.avi' -o -iname '*.mov' -o -iname '*.mkv')
varios_extensions=(-not \( -iname '*.doc' -o -iname '*.docx' -o -iname '*.pdf' -o -iname '*.txt' -o -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' -o -iname '*.gif' -o -iname '*.bmp' -o -iname '*.mp4' -o -iname '*.avi' -o -iname '*.mov' -o -iname '*.mkv' \))

# Generar salida JSON con información sobre los tipos de archivos y el tamaño total de la carpeta
{
  echo "{"  # Inicio del objeto JSON
  # Generar información para cada tipo de archivo y agregarla al objeto JSON
  echo "$(calcular_archivos "documentos" doc_extensions[@] "documentos"),"
  echo "$(calcular_archivos "imagenes" img_extensions[@] "imagenes"),"
  echo "$(calcular_archivos "videos" vid_extensions[@] "videos"),"
  echo "$(calcular_archivos "varios" varios_extensions[@] "varios")"
  # Calcular y agregar el tamaño total de la carpeta al objeto JSON
  tamanio_carpeta=$(du -sh "$ruta" | awk '{print $1}')
  echo ",\"tamanio_total_carpeta\": \"$tamanio_carpeta\""
  echo "}"  # Fin del objeto JSON
} | jq .  # Utilizar jq para formatear la salida JSON
