<section class="con_almacenamiento">
  <!-- Sección principal de almacenamiento -->
  <section class="almacenamiento">
    <!-- Título de la sección de almacenamiento -->
    <p class="title_almacenamiento">ALMACENAMIENTO</p>

    <!-- Sub-sección que muestra el uso del almacenamiento -->
    <section class="almacenamiento_uso">
      <section class="almacenamiento_uso_top">
        <!-- Icono de disco duro y texto -->
        <p class="con_hard_drive">
          <i class="icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              fill="#000000"
              viewBox="0 0 256 256"
            >
              <path
                d="M224,60H32A20,20,0,0,0,12,80v96a20,20,0,0,0,20,20H224a20,20,0,0,0,20-20V80A20,20,0,0,0,224,60Zm-4,112H36V84H220Zm-56-44a16,16,0,1,1,16,16A16,16,0,0,1,164,128Z"
              ></path>
            </svg>
          </i>
          <span>DISCO</span>
        </p>
        <!-- Espacio para mostrar el total del almacenamiento -->
        <p class="almacenamiento_total"></p>
      </section>

      <!-- Barra de progreso que muestra el uso del almacenamiento -->
      <div class="con_barra_progreso">
        <p>Usado de <span class="almacenamiento_tamanio_usado">&nbsp;</span></p>
        <div class="barra_progreso_almacenamiento">
          <div class="barra_progreso_almacenamiento_color"></div>
        </div>
      </div>
    </section>

    <!-- Sección de detalles del almacenamiento -->
    <section class="detalles_almacenamiento">
      <p class="title_almacenamiento_detalles">DETALLES ALMACENAMIENTO</p>

      <!-- Sub-sección para documentos -->
      <div class="con_documentos">
        <div class="con_documentos_superior">
          <div class="con_documentos_superior_izquierda">
            <i class="icon icon_almacenamiento">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                fill="#000000"
                viewBox="0 0 256 256"
              >
                <path
                  d="M216.49,79.52l-56-56A12,12,0,0,0,152,20H56A20,20,0,0,0,36,40V216a20,20,0,0,0,20,20H200a20,20,0,0,0,20-20V88A12,12,0,0,0,216.49,79.52ZM160,57l23,23H160ZM60,212V44h76V92a12,12,0,0,0,12,12h48V212Zm112-80a12,12,0,0,1-12,12H96a12,12,0,0,1,0-24h64A12,12,0,0,1,172,132Zm0,40a12,12,0,0,1-12,12H96a12,12,0,0,1,0-24h64A12,12,0,0,1,172,172Z"
                ></path>
              </svg>
            </i>
            <!-- Información sobre documentos -->
            <div class="con_documentos_text">
              <p class="text_tipo_archivo">DOCUMENTOS</p>
              <p class="text_num_archivos">
                <span class="almacenamiento_cantidad_documentos">&nbsp;</span>
                Archivos
              </p>
            </div>
          </div>
          <!-- Espacio adicional -->
          <p class="con_documentos_superior_derecha">&nbsp;</p>
        </div>
        <!-- Barra de progreso para documentos -->
        <div class="con_documentos_inferior">
          <div class="barra_progreso_archivo">
            <div class="barra_progreso_archivo_documentos"></div>
          </div>
        </div>
      </div>

      <!-- Sub-sección para imágenes -->
      <div class="con_imagenes">
        <div class="con_imagenes_superior">
          <div class="con_imagenes_superior_izquierda">
            <i class="icon icon_almacenamiento">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                fill="#000000"
                viewBox="0 0 256 256"
              >
                <path
                  d="M144,96a16,16,0,1,1,16,16A16,16,0,0,1,144,96Zm92-40V200a20,20,0,0,1-20,20H40a20,20,0,0,1-20-20V56A20,20,0,0,1,40,36H216A20,20,0,0,1,236,56ZM44,60v79.72l33.86-33.86a20,20,0,0,1,28.28,0L147.31,147l17.18-17.17a20,20,0,0,1,28.28,0L212,149.09V60Zm0,136H162.34L92,125.66l-48,48Zm168,0V183l-33.37-33.37L164.28,164l32,32Z"
                ></path>
              </svg>
            </i>
            <!-- Información sobre imágenes -->
            <div class="con_imagenes_text">
              <p class="text_tipo_archivo">IMAGENES</p>
              <p class="text_num_archivos">
                <span class="almacenamiento_cantidad_imagenes">&nbsp;</span>
                Archivos
              </p>
            </div>
          </div>
          <!-- Espacio adicional -->
          <p class="con_imagenes_superior_derecha">&nbsp;</p>
        </div>
        <!-- Barra de progreso para imágenes -->
        <div class="con_imagenes_inferior">
          <div class="barra_progreso_archivo">
            <div class="barra_progreso_archivo_imagenes"></div>
          </div>
        </div>
      </div>

      <!-- Sub-sección para videos -->
      <div class="con_videos">
        <div class="con_videos_superior">
          <div class="con_videos_superior_izquierda">
            <i class="icon icon_almacenamiento">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                fill="#000000"
                viewBox="0 0 256 256"
              >
                <path
                  d="M216,36H40A20,20,0,0,0,20,56V160a20,20,0,0,0,20,20H216a20,20,0,0,0,20-20V56A20,20,0,0,0,216,36Zm-4,120H44V60H212Zm24,52a12,12,0,0,1-12,12H32a12,12,0,0,1,0-24H224A12,12,0,0,1,236,208ZM104,128V88a12,12,0,0,1,18.36-10.18l32,20a12,12,0,0,1,0,20.36l-32,20A12,12,0,0,1,104,128Z"
                ></path>
              </svg>
            </i>
            <!-- Información sobre videos -->
            <div class="con_videos_text">
              <p class="text_tipo_archivo">VIDEOS</p>
              <p class="text_num_archivos">
                <span class="almacenamiento_cantidad_videos">&nbsp;</span>
                Archivos
              </p>
            </div>
          </div>
          <!-- Espacio adicional -->
          <p class="con_videos_superior_derecha">&nbsp;</p>
        </div>
        <!-- Barra de progreso para videos -->
        <div class="con_videos_inferior">
          <div class="barra_progreso_archivo">
            <div class="barra_progreso_archivo_videos"></div>
          </div>
        </div>
      </div>

      <!-- Sub-sección para otros archivos -->
      <div class="con_otros">
        <div class="con_otros_superior">
          <div class="con_otros_superior_izquierda">
            <i class="icon icon_almacenamiento">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                fill="#000000"
                viewBox="0 0 256 256"
              >
                <path
                  d="M216.49,79.52l-56-56A12,12,0,0,0,152,20H56A20,20,0,0,0,36,40V216a20,20,0,0,0,20,20H200a20,20,0,0,0,20-20V88A12,12,0,0,0,216.49,79.52ZM160,57l23,23H160ZM60,212V44h76V92a12,12,0,0,0,12,12h48V212Zm112-80a12,12,0,0,1-12,12H96a12,12,0,0,1,0-24h64A12,12,0,0,1,172,132Zm0,40a12,12,0,0,1-12,12H96a12,12,0,0,1,0-24h64A12,12,0,0,1,172,172Z"
                ></path>
              </svg>
            </i>
            <!-- Información sobre otros archivos -->
            <div class="con_otros_text">
              <p class="text_tipo_archivo">OTROS</p>
              <p class="text_num_archivos">
                <span class="almacenamiento_cantidad_otros">&nbsp;</span>
                Archivos
              </p>
            </div>
          </div>
          <!-- Espacio adicional -->
          <p class="con_otros_superior_derecha">&nbsp;</p>
        </div>
        <!-- Barra de progreso para otros archivos -->
        <div class="con_otros_inferior">
          <div class="barra_progreso_archivo">
            <div class="barra_progreso_archivo_otros"></div>
          </div>
        </div>
      </div>
    </section>
  </section>
</section>
