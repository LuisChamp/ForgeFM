<div class="modal">
  <!-- Modal para subir archivos -->
  <article class="modal-container modal-subir-archivo">
    <!-- Encabezado del modal -->
    <header class="modal-container-header">
      <!-- Título del modal con icono -->
      <h1 class="modal-container-title">
        <!-- Icono para subir archivo -->
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M228,144v64a12,12,0,0,1-12,12H40a12,12,0,0,1-12-12V144a12,12,0,0,1,24,0v52H204V144a12,12,0,0,1,24,0ZM96.49,80.49,116,61v83a12,12,0,0,0,24,0V61l19.51,19.52a12,12,0,1,0,17-17l-40-40a12,12,0,0,0-17,0l-40,40a12,12,0,1,0,17,17Z">
          </path>
        </svg>
        Subir archivos
      </h1>
      <!-- Botón para cerrar el modal -->
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <!-- Cuerpo del modal -->
    <section class="modal-container-body rtf">
      <!-- Contenido para subir archivos -->
      <div class="container-subir-archivo">
        <!-- Sección de encabezado -->
        <div class="header-section">
          <p>Sube los archivos que deseas tener en el gestor.</p>
        </div>
        <!-- Sección de arrastrar y soltar archivos -->
        <div class="drop-section">
          <!-- Columna para arrastrar y soltar archivos -->
          <div class="col">
            <!-- Icono de nube para arrastrar y soltar -->
            <div class="cloud-icon">
              <img src="assets/imgs/cloud.png" alt="cloud">
            </div>
            <!-- Texto para arrastrar y soltar archivos -->
            <span>Arrastra y suelta tus archivos aquí</span>
            <!-- Texto alternativo -->
            <span>O</span>
            <!-- Botón para seleccionar archivos -->
            <button class="file-selector">Buscar archivos</button>
            <!-- Entrada de archivos oculta -->
            <input type="file" class="file-selector-input" multiple>
          </div>
          <!-- Columna para soltar archivos -->
          <div class="col">
            <div class="drop-here">Suelta aquí</div>
          </div>
        </div>
        <!-- Sección de lista de archivos subidos -->
        <div class="list-section">
          <!-- Título de la lista -->
          <div class="list-title">Archivos subidos</div>
          <!-- Contenedor de la lista -->
          <div class="list"></div>
        </div>
      </div>
    </section>
    <!-- Pie de página del modal -->
    <footer class="modal-container-footer">
      <!-- Botón para cancelar -->
      <button class="button-modal-box button-cancelar">Cerrar</button>
    </footer>
  </article>
  <!-- Modal para crear carpeta -->
  <article class="modal-container modal-crear-carpeta">
    <!-- Encabezado del modal -->
    <header class="modal-container-header">
      <!-- Título del modal con icono -->
      <h1 class="modal-container-title">
        <!-- Icono para crear carpeta -->
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M216,68H133.39l-26-29.29a20,20,0,0,0-15-6.71H40A20,20,0,0,0,20,52V200.62A19.41,19.41,0,0,0,39.38,220H216.89A19.13,19.13,0,0,0,236,200.89V88A20,20,0,0,0,216,68ZM90.61,56l10.67,12H44V56ZM212,196H44V92H212Zm-72-76v12h12a12,12,0,0,1,0,24H140v12a12,12,0,0,1-24,0V156H104a12,12,0,0,1,0-24h12V120a12,12,0,0,1,24,0Z">
          </path>
        </svg>
        Nueva Carpeta
      </h1>
      <!-- Botón para cerrar el modal -->
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <!-- Cuerpo del modal -->
    <section class="modal-container-body rtf">
      <!-- Sección de formulario para crear carpeta -->
      <section class="form">
        <!-- Etiqueta y campo de entrada para el nombre de la carpeta -->
        <label for="">Nombre</label>
        <input type="text" name="nombre_carpeta" id="nombre_carpeta" class="input_nombre_carpeta">
        <!-- Campo oculto para la ruta de la carpeta -->
        <input type="hidden" class="input_hidden" name="ruta_carpeta" value="">
      </section>
    </section>
    <!-- Pie de página del modal -->
    <footer class="modal-container-footer">
      <!-- Botón para cancelar la creación de la carpeta -->
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <!-- Botón para crear la carpeta (inicialmente deshabilitado) -->
      <button class="button-modal-box button-crear" disabled>Crear</button>
    </footer>
  </article>
  <!-- Modal para cambiar nombre -->
  <article class="modal-container modal-cambiar-nombre">
    <!-- Encabezado del modal -->
    <header class="modal-container-header">
      <!-- Título del modal con icono -->
      <h1 class="modal-container-title">
        <!-- Icono para cambiar nombre -->
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M232.49,55.51l-32-32a12,12,0,0,0-17,0l-96,96A12,12,0,0,0,84,128v32a12,12,0,0,0,12,12h32a12,12,0,0,0,8.49-3.51l96-96A12,12,0,0,0,232.49,55.51ZM192,49l15,15L196,75,181,60Zm-69,99H108V133l56-56,15,15Zm105-7.43V208a20,20,0,0,1-20,20H48a20,20,0,0,1-20-20V48A20,20,0,0,1,48,28h67.43a12,12,0,0,1,0,24H52V204H204V140.57a12,12,0,0,1,24,0Z">
          </path>
        </svg>
        Cambiar Nombre
      </h1>
      <!-- Botón para cerrar el modal -->
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <!-- Cuerpo del modal -->
    <section class="modal-container-body rtf">
      <!-- Sección de formulario para cambiar nombre -->
      <section class="form">
        <!-- Etiqueta y campo de entrada para el nuevo nombre -->
        <label for="">Nuevo nombre</label>
        <input type="text" name="" class="input_cambiar_nombre">
      </section>
    </section>
    <!-- Pie de página del modal -->
    <footer class="modal-container-footer">
      <!-- Botón para cancelar el cambio de nombre -->
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <!-- Botón para aceptar el cambio de nombre (inicialmente deshabilitado) -->
      <button class="button-modal-box button-cambiar-nombre" disabled>Aceptar</button>
    </footer>
  </article>
  <!-- Modal para cambiar nombre de la papelera -->
  <article class="modal-container modal-cambiar-nombre-papelera">
    <!-- Encabezado del modal -->
    <header class="modal-container-header">
      <!-- Título del modal con icono -->
      <h1 class="modal-container-title">
        <!-- Icono para cambiar nombre -->
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M232.49,55.51l-32-32a12,12,0,0,0-17,0l-96,96A12,12,0,0,0,84,128v32a12,12,0,0,0,12,12h32a12,12,0,0,0,8.49-3.51l96-96A12,12,0,0,0,232.49,55.51ZM192,49l15,15L196,75,181,60Zm-69,99H108V133l56-56,15,15Zm105-7.43V208a20,20,0,0,1-20,20H48a20,20,0,0,1-20-20V48A20,20,0,0,1,48,28h67.43a12,12,0,0,1,0,24H52V204H204V140.57a12,12,0,0,1,24,0Z">
          </path>
        </svg>
        Cambiar Nombre
      </h1>
      <!-- Botón para cerrar el modal -->
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <!-- Cuerpo del modal -->
    <section class="modal-container-body rtf">
      <!-- Sección de formulario para cambiar nombre de la papelera -->
      <section class="form">
        <!-- Etiqueta y campo de entrada para el nuevo nombre -->
        <label for="">Nuevo nombre</label>
        <input type="text" name="" class="input_cambiar_nombre_papelera">
      </section>
    </section>
    <!-- Pie de página del modal -->
    <footer class="modal-container-footer">
      <!-- Botón para cancelar el cambio de nombre -->
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <!-- Botón para aceptar el cambio de nombre (inicialmente deshabilitado) -->
      <button class="button-modal-box button-cambiar-nombre-papelera" disabled>Aceptar</button>
    </footer>
  </article>
  <!-- Modal para compartir -->
  <article class="modal-container modal-compartir">
    <!-- Encabezado del modal -->
    <header class="modal-container-header">
      <!-- Título del modal con icono -->
      <h1 class="modal-container-title">
        <!-- Icono para compartir -->
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M232.49,112.49l-48,48a12,12,0,0,1-17-17L195,116H165a84,84,0,0,0-81.36,63,12,12,0,1,1-23.24-6A107.94,107.94,0,0,1,165,92H195L167.51,64.48a12,12,0,0,1,17-17l48,48A12,12,0,0,1,232.49,112.49ZM192,204H44V88a12,12,0,0,0-24,0V216a12,12,0,0,0,12,12H192a12,12,0,0,0,0-24Z">
          </path>
        </svg>
        Compartir <span class="compartir_nombre_elemento"></span>
      </h1>
      <!-- Botón para cerrar el modal -->
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <!-- Cuerpo del modal -->
    <section class="modal-container-body rtf">
      <!-- Sección de formulario para compartir -->
      <div class="form_compartir">
        <input type="text" name="" class="input_compartir" placeholder="Añade el email del usuario">
        <button class="button-modal-box btn_agregar_lector" disabled>Compartir</button>
      </div>
      <!-- Lista de emails compartidos -->
      <ul class="lista-emails"></ul>
      <!-- Sección de personas con acceso -->
      <section>
        <!-- Título de personas con acceso -->
        <h2 class="subtitulo-compartido">Personas con acceso</h2>
        <!-- Propietario -->
        <div class="compartido-propietario">
          <div class="compartido-propietario-izq">
            <img src="assets/imgs/users/default_usuario.jpg" alt="" class="icon-usuario compartido-img-propietario">
            <!-- Datos del propietario -->
            <div class="compartido-propietario-izq-datos">
              <h3 class="compartido-nombre-propietario"></h3>
              <p class="compartido-email-propietario"></p>
            </div>
          </div>
          <!-- Indicador de propietario -->
          <p class="compartido-propietario-der">Propietario</p>
        </div>
        <!-- Lista de lectores compartidos -->
        <div class="lectores">
          <!-- Lector compartido -->
          <div class="compartido-lector">
            <div class="compartido-lector-izq">
              <!-- Icono de usuario -->
              <img src="assets/imgs/users/default_usuario.jpg" alt="" class="icon-usuario compartido-img-lector">
              <!-- Datos del lector -->
              <div class="compartido-lector-izq-datos">
                <h3 class="compartido-nombre-lector"></h3>
                <p class="compartido-email-lector"></p>
              </div>
            </div>
            <!-- Botón para quitar acceso del lector -->
            <div class="compartido-lector-der">
              <button class="btn-quitar-acceso">Quitar acceso</button>
            </div>
          </div>
        </div>
      </section>
    </section>
    <!-- Pie de página del modal -->
    <footer class="modal-container-footer">
      <!-- Botón para cancelar el compartir -->
      <button class="button-modal-box button-cancelar">Cancelar</button>
    </footer>
  </article>
  <!-- Modal para mover un elemento -->
  <article class="modal-container modal-mover">
    <header class="modal-container-header">
      <!-- Título del modal -->
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
          <path
            d="M220,112v96a20,20,0,0,1-20,20H56a20,20,0,0,1-20-20V112A20,20,0,0,1,56,92H76a12,12,0,0,1,0,24H60v88H196V116H180a12,12,0,0,1,0-24h20A20,20,0,0,1,220,112ZM96.49,72.49,116,53v83a12,12,0,0,0,24,0V53l19.51,19.52a12,12,0,1,0,17-17l-40-40a12,12,0,0,0-17,0l-40,40a12,12,0,1,0,17,17Z">
          </path>
        </svg>
        Mover <span class="mover_nombre_elemento"></span>
      </h1>
      <!-- Botón de cierre del modal -->
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z"></path>
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z">
          </path>
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <!-- Ruta actual del elemento -->
      <div class="modal-mover-ruta">
        <p>Ruta actual:</p>
        <!-- Unidad de almacenamiento -->
        <span>Unidad</span>
        <!-- Ícono de carpeta -->
        <i>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256">
            <path
              d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z">
            </path>
          </svg>
        </i>
        <!-- Nombre de la carpeta 1 -->
        <span>Carpeta 1</span>
        <!-- Ícono de carpeta -->
        <i>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256">
            <path
              d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z">
            </path>
          </svg>
        </i>
        <!-- Nombre de la carpeta 2 -->
        <span>Carpeta 2</span>
        <!-- Ícono de carpeta -->
        <i>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256">
            <path
              d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z">
            </path>
          </svg>
        </i>
        <!-- Nombre de la carpeta 3 -->
        <span>Carpeta 3</span>
      </div>
      <!-- Listado de carpetas disponibles para mover -->
      <div class="modal-carpetas-ruta-mover">
        <!-- Carpeta 1 disponible para mover -->
        <div class="modal-carpeta-mover">
          <!-- Sección izquierda de la carpeta -->
          <div class="modal-carpeta-mover-izq">
            <!-- Ícono de carpeta -->
            <i class="modal-mover-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                <path
                  d="M216,64H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,80V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V80A16,16,0,0,0,216,64ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Z">
                </path>
              </svg>
            </i>
            <!-- Nombre de la carpeta 1 -->
            <p class="modal-nombre-carpeta">Carpeta 1</p>
          </div>
          <!-- Ícono para ingresar a la carpeta -->
          <i class="modal-mover-icon  modal-mover-icon-entrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
              <path
                d="M221.66,133.66l-80,80A8,8,0,0,1,128,208V147.31L61.66,213.66A8,8,0,0,1,48,208V48a8,8,0,0,1,13.66-5.66L128,108.69V48a8,8,0,0,1,13.66-5.66l80,80A8,8,0,0,1,221.66,133.66Z">
              </path>
            </svg>
          </i>
        </div>
        <!-- Carpeta 2 disponible para mover -->
        <div class="modal-carpeta-mover">
          <!-- Sección izquierda de la carpeta -->
          <div class="modal-carpeta-mover-izq">
            <!-- Ícono de carpeta -->
            <i class="modal-mover-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                <path
                  d="M216,64H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,80V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V80A16,16,0,0,0,216,64ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Z">
                </path>
              </svg>
            </i>
            <!-- Nombre de la carpeta 1 -->
            <p class="modal-nombre-carpeta">Carpeta 1</p>
          </div>
          <!-- Ícono para ingresar a la carpeta -->
          <i class="modal-mover-icon  modal-mover-icon-entrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
              <path
                d="M221.66,133.66l-80,80A8,8,0,0,1,128,208V147.31L61.66,213.66A8,8,0,0,1,48,208V48a8,8,0,0,1,13.66-5.66L128,108.69V48a8,8,0,0,1,13.66-5.66l80,80A8,8,0,0,1,221.66,133.66Z">
              </path>
            </svg>
          </i>
        </div>
      </div>
    </section>
    <!-- Footer del modal -->
    <footer class="modal-container-footer">
      <!-- Botón de cancelar -->
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <!-- Botón de mover -->
      <button class="button-modal-box button-mover">Mover</button>
    </footer>
  </article>
  <!--Mover multiple-->
  <article class="modal-container modal-mover-multiple">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
          <path
            d="M220,112v96a20,20,0,0,1-20,20H56a20,20,0,0,1-20-20V112A20,20,0,0,1,56,92H76a12,12,0,0,1,0,24H60v88H196V116H180a12,12,0,0,1,0-24h20A20,20,0,0,1,220,112ZM96.49,72.49,116,53v83a12,12,0,0,0,24,0V53l19.51,19.52a12,12,0,1,0,17-17l-40-40a12,12,0,0,0-17,0l-40,40a12,12,0,1,0,17,17Z">
          </path>
        </svg>
        </svg>
        Mover <span class="modal_mover_nombre_item_multiple">Name</span>
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <div class="modal-mover-ruta-multiple">
        <p>Ruta actual:</p>
        <span>Unidad</span>
        <i>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256">
            <path
              d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z">
            </path>
          </svg>
        </i>
        <span>Carpeta 1</span>
        <i>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256">
            <path
              d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z">
            </path>
          </svg>
        </i>
        <span>Carpeta 2</span>
        <i>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#000000" viewBox="0 0 256 256">
            <path
              d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z">
            </path>
          </svg>
        </i>
        <span>Carpeta 3</span>
      </div>
      <div class="modal-carpetas-ruta-mover-multiple">
        <div class="modal-carpeta-mover">
          <div class="modal-carpeta-mover-izq">
            <i class="modal-mover-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                <path
                  d="M216,64H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,80V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V80A16,16,0,0,0,216,64ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Z">
                </path>
              </svg>
            </i>
            <p class="modal-nombre-carpeta">Carpeta 1</p>
          </div>
          <i class="modal-mover-icon  modal-mover-icon-entrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
              <path
                d="M221.66,133.66l-80,80A8,8,0,0,1,128,208V147.31L61.66,213.66A8,8,0,0,1,48,208V48a8,8,0,0,1,13.66-5.66L128,108.69V48a8,8,0,0,1,13.66-5.66l80,80A8,8,0,0,1,221.66,133.66Z">
              </path>
            </svg>
          </i>
        </div>
        <div class="modal-carpeta-mover">
          <div class="modal-carpeta-mover-izq">
            <i class="modal-mover-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
                <path
                  d="M216,64H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,80V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V80A16,16,0,0,0,216,64ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Z">
                </path>
              </svg>
            </i>
            <p class="modal-nombre-carpeta">Carpeta 1</p>
          </div>
          <i class="modal-mover-icon modal-mover-icon-entrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256">
              <path
                d="M221.66,133.66l-80,80A8,8,0,0,1,128,208V147.31L61.66,213.66A8,8,0,0,1,48,208V48a8,8,0,0,1,13.66-5.66L128,108.69V48a8,8,0,0,1,13.66-5.66l80,80A8,8,0,0,1,221.66,133.66Z">
              </path>
            </svg>
          </i>
        </div>
      </div>
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <button class="button-modal-box button-mover-multiple">Mover</button>
    </footer>
  </article>
  <!--Informacion Carpeta-->
  <article class="modal-container modal-informacion-carpeta">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M108,84a16,16,0,1,1,16,16A16,16,0,0,1,108,84Zm128,44A108,108,0,1,1,128,20,108.12,108.12,0,0,1,236,128Zm-24,0a84,84,0,1,0-84,84A84.09,84.09,0,0,0,212,128Zm-72,36.68V132a20,20,0,0,0-20-20,12,12,0,0,0-4,23.32V168a20,20,0,0,0,20,20,12,12,0,0,0,4-23.32Z">
          </path>
        </svg>
        </svg>
        Información <span class="info_nombre_carpeta"></span>
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <div class="tipo">
        <p class="title_info_carpeta">Tipo:</p>
        <p>Carpeta</p>
      </div>
      <div class="nombre_completo_carpeta">
        <p class="title_info_carpeta">Nombre completo:</p>
        <p class="info_nombre_completo_carpeta">&nbsp;</p>
      </div>
      <div class="propietario">
        <p class="title_info_carpeta">Propietario:</p>
        <p class="info_propietario_carpeta">&nbsp;</p>
      </div>
      <div class="creacion">
        <p class="title_info_carpeta">Fecha de creación:</p>
        <p class="info_fecha_creacion_carpeta">&nbsp;</p>
      </div>
      <div class="cantidad">
        <p class="title_info_carpeta">Cantidad:</p>
        <div class="archivos">
          <p class="info_cantidad_archivos">&nbsp;</p>
          <span>/</span>
          <p class="info_cantidad_carpetas">&nbsp;</p>
        </div>
      </div>
      <div class="tamanio">
        <p class="title_info_carpeta">Tamaño:</p>
        <p class="info_tamanio_carpeta">&nbsp;</p>
      </div>
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cerrar</button>
    </footer>
  </article>
  <!--Informacion Archivo-->
  <article class="modal-container modal-informacion-archivo">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M108,84a16,16,0,1,1,16,16A16,16,0,0,1,108,84Zm128,44A108,108,0,1,1,128,20,108.12,108.12,0,0,1,236,128Zm-24,0a84,84,0,1,0-84,84A84.09,84.09,0,0,0,212,128Zm-72,36.68V132a20,20,0,0,0-20-20,12,12,0,0,0-4,23.32V168a20,20,0,0,0,20,20,12,12,0,0,0,4-23.32Z">
          </path>
        </svg>
        </svg>
        Información <span class="info_nombre_archivo"></span>
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <div class="tipo">
        <p class="title_info_archivo">Tipo:</p>
        <p>Archivo</p>
      </div>
      <div class="nombre_completo_archivo">
        <p class="title_info_archivo">Nombre completo:</p>
        <p class="info_nombre_completo_archivo">&nbsp;</p>
      </div>
      <div class="propietario">
        <p class="title_info_archivo">Propietario:</p>
        <p class="info_propietario_archivo">&nbsp;</p>
      </div>
      <div class="creacion">
        <p class="title_info_archivo">Fecha de creación:</p>
        <p class="info_fecha_creacion_archivo">&nbsp;</p>
      </div>
      <div class="tamanio">
        <p class="title_info_archivo">Tamaño:</p>
        <p class="info_tamanio_archivo">&nbsp;</p>
      </div>
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cerrar</button>
    </footer>
  </article>
  <!--Modificar correo electronico-->
  <article class="modal-container modal-cambiar-correo">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M228,48V152a20,20,0,0,1-20,20H112.92a12,12,0,0,1-17.41,16.49l-20-20a12,12,0,0,1,0-17l20-20A12,12,0,0,1,112.92,148H204V52H100a12,12,0,0,1-24,0V48A20,20,0,0,1,96,28H208A20,20,0,0,1,228,48ZM168,192a12,12,0,0,0-12,12H52V108h91.08a12,12,0,0,0,17.41,16.49l20-20a12,12,0,0,0,0-17l-20-20A12,12,0,0,0,143.08,84H48a20,20,0,0,0-20,20V208a20,20,0,0,0,20,20H160a20,20,0,0,0,20-20v-4A12,12,0,0,0,168,192Z">
          </path>
        </svg>
        Cambiar correo
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <label for="">Correo actual</label>
      <p class="modal_correo_actual">&nbsp;</p>
      <label for="">Nuevo correo</label>
      <input type="text" name="nuevo_correo" id="nuevo_correo" class="input_nuevo_correo">
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <button class="button-modal-box button_cambiar_correo" disabled>Cambiar</button>
    </footer>
  </article>
  <!--Cambiar contraseña-->
  <article class="modal-container modal-cambiar-pass">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M228,48V152a20,20,0,0,1-20,20H112.92a12,12,0,0,1-17.41,16.49l-20-20a12,12,0,0,1,0-17l20-20A12,12,0,0,1,112.92,148H204V52H100a12,12,0,0,1-24,0V48A20,20,0,0,1,96,28H208A20,20,0,0,1,228,48ZM168,192a12,12,0,0,0-12,12H52V108h91.08a12,12,0,0,0,17.41,16.49l20-20a12,12,0,0,0,0-17l-20-20A12,12,0,0,0,143.08,84H48a20,20,0,0,0-20,20V208a20,20,0,0,0,20,20H160a20,20,0,0,0,20-20v-4A12,12,0,0,0,168,192Z">
          </path>
        </svg>
        Cambiar contraseña
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <section class="form">
        <label for="">Contraseña actual</label>
        <input type="password" name="pass_actual" class="pass_actual">
        <label for="">Contraseña nueva</label>
        <input type="password" name="pass_nueva" class="pass_nueva">
        <label for="">Confirma contraseña nueva</label>
        <input type="password" name="pass_conf_nueva" class="pass_conf_nueva">
      </section>
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <button class="button-modal-box button_cambiar_pass" disabled>Cambiar</button>
    </footer>
  </article>
  <!--Eliminar cuenta-->
  <article class="modal-container modal-eliminar-cuenta">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0D0D64" viewBox="0 0 256 256">
          <path
            d="M216,36H68.53a20.09,20.09,0,0,0-17.15,9.71L5.71,121.83a12,12,0,0,0,0,12.34l45.67,76.12A20.09,20.09,0,0,0,68.53,220H216a20,20,0,0,0,20-20V56A20,20,0,0,0,216,36Zm-4,160H70.8L30,128,70.8,60H212ZM103.51,143.51,119,128l-15.52-15.51a12,12,0,0,1,17-17L136,111l15.51-15.52a12,12,0,0,1,17,17L153,128l15.52,15.51a12,12,0,0,1-17,17L136,145l-15.51,15.52a12,12,0,0,1-17-17Z">
          </path>
        </svg>
        Eliminar cuenta
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <p>¿Estas seguro que deseas eliminar tu cuenta y todo lo relacionado a ella?</p>
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <button class="button-modal-box button_eliminar_cuenta">Confirmar</button>
    </footer>
  </article>
  <!--Vaciar papelera-->
  <article class="modal-container modal-vaciar-papelera">
    <header class="modal-container-header">
      <h1 class="modal-container-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
          <path
            d="M216,36H68.53a20.09,20.09,0,0,0-17.15,9.71L5.71,121.83a12,12,0,0,0,0,12.34l45.67,76.12A20.09,20.09,0,0,0,68.53,220H216a20,20,0,0,0,20-20V56A20,20,0,0,0,216,36Zm-4,160H70.8L30,128,70.8,60H212ZM103.51,143.51,119,128l-15.52-15.51a12,12,0,0,1,17-17L136,111l15.51-15.52a12,12,0,0,1,17,17L153,128l15.52,15.51a12,12,0,0,1-17,17L136,145l-15.51,15.52a12,12,0,0,1-17-17Z">
          </path>
        </svg>
        Vaciar papelera
      </h1>
      <button class="icon-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="none" d="M0 0h24v24H0z" />
          <path fill="currentColor"
            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
        </svg>
      </button>
    </header>
    <section class="modal-container-body rtf">
      <p>¿Estás seguro de que deseas vaciar la papelera? Esta acción eliminará permanentemente todos los elementos y no
        se podrán restaurar.</p>
    </section>
    <footer class="modal-container-footer">
      <button class="button-modal-box button-cancelar">Cancelar</button>
      <button class="button-modal-box button_vaciar_papelera">Confirmar</button>
    </footer>
  </article>
</div>