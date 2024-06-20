#!/bin/bash

# SCRIPT PARA ELIMINAR ELEMENTOS QUE ESTAN MÁS DE 30 DÍAS EN LA PAPELERA DE MANERA AUTOMÁTICA

# Definimos el número de días para el intervalo
DIAS_INTERVALO=30

# Obtenemos las rutas de carpetas a eliminar
RUTAS_CARPETAS=$(mysql -u gestoradmin -pA1b2c3d4. -D bd_forgeFM -N -B -e "SELECT ruta_carpeta_papelera FROM carpetas WHERE ruta_carpeta_papelera LIKE '/%/%/%/%/%' AND ruta_carpeta_papelera NOT LIKE '/%/%/%/%/%/%' AND fecha_papelera < DATE_SUB(CURDATE(),INTERVAL $DIAS_INTERVALO DAY);")

# Obtenemos las rutas de archivos a eliminar
RUTAS_ARCHIVOS=$(mysql -u gestoradmin -pA1b2c3d4. -D bd_forgeFM -N -B -e "SELECT ruta_archivo_papelera FROM archivos WHERE ruta_archivo_papelera LIKE '/%/%/%/%/%' AND ruta_archivo_papelera NOT LIKE '/%/%/%/%/%/%' AND fecha_papelera < DATE_SUB(CURDATE(),INTERVAL $DIAS_INTERVALO DAY);")

# Iteramos sobre cada ruta de carpeta y la eliminamos del sistema
while IFS= read -r RUTA_CARPETA; do
    rm -rf "$RUTA_CARPETA"
done <<< "$RUTAS_CARPETAS"

# Iteramos sobre cada ruta de archivo y la eliminamos del sistema
while IFS= read -r RUTA_ARCHIVO; do
    rm -rf "$RUTA_ARCHIVO"
done <<< "$RUTAS_ARCHIVOS"

# Eliminamos las carpetas indicadas de la base de datos
mysql -u gestoradmin -pA1b2c3d4. -D bd_forgeFM -e "DELETE FROM carpetas WHERE ruta_carpeta_papelera LIKE '/%/%/%/%/%' AND ruta_carpeta_papelera NOT LIKE '/%/%/%/%/%/%' AND fecha_papelera < DATE_SUB(CURDATE(),INTERVAL $DIAS_INTERVALO DAY);"

# Eliminamos los archivos indicados de la base de datos
mysql -u gestoradmin -pA1b2c3d4. -D bd_forgeFM -e "DELETE FROM archivos WHERE ruta_archivo_papelera LIKE '/%/%/%/%/%' AND ruta_archivo_papelera NOT LIKE '/%/%/%/%/%/%' AND fecha_papelera < DATE_SUB(CURDATE(),INTERVAL $DIAS_INTERVALO DAY);"

