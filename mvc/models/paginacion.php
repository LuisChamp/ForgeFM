<?php 
// Definición de la clase Paginacion
class Paginacion {
    // Propiedades privadas que almacenan la página actual y el total de páginas
    private $paginaActual;
    private $totalPaginas;
    // Número máximo de páginas a mostrar en la paginación
    private $maxPaginas = 7;

    // Constructor de la clase, recibe la página actual y el total de páginas
    public function __construct($paginaActual, $totalPaginas) {
        $this->paginaActual = $paginaActual;
        $this->totalPaginas = $totalPaginas;
    }

    // Método para construir la paginación
    public function construirPaginacion() {
        // Calculamos el valor medio del número máximo de páginas
        $medio = floor($this->maxPaginas / 2);
        // Inicializamos las variables que almacenarán el inicio y el fin de la página a mostrar
        $inicioPagina = 1;
        $finPagina = $this->totalPaginas;
        // Variable que almacenará el HTML de la paginación
        $paginacionHTML = '';

        // Verificamos si el total de páginas es mayor que el número máximo de páginas a mostrar
        if ($this->totalPaginas > $this->maxPaginas) {
            // Si la página actual está en la primera mitad de las páginas
            if ($this->paginaActual <= $medio) {
                // Establecemos el final de la página como el número máximo de páginas a mostrar
                $finPagina = $this->maxPaginas;
            } 
            // Si la página actual está en la segunda mitad de las páginas
            else if ($this->paginaActual + $medio > $this->totalPaginas) {
                // Ajustamos el inicio de la página para que la última página se muestre como la última página en el conjunto de la paginación
                $inicioPagina = $this->totalPaginas - $this->maxPaginas + 1;
            } 
            // Si la página actual está en el medio
            else {
                // Calculamos el inicio y el final de la página en base a la posición de la página actual
                $inicioPagina = $this->paginaActual - $medio;
                $finPagina = $this->paginaActual + $medio;
            }
        }

        // Agregamos los botones de navegación a la paginación
        if ($this->paginaActual > 1) {
            $paginacionHTML .= '<button data-pagina="1" class="paginacion-extremo">&lt;&lt;</button>';
            $paginacionHTML .= '<button data-pagina="' . ($this->paginaActual - 1) . '" class="paginacion-extremo">&lt;</button>';
        }

        // Agregamos los botones de números de página a la paginación
        for ($i = $inicioPagina; $i <= $finPagina; $i++) {
            // Si es la página actual, agregamos la clase 'active' para resaltarlo
            if($i == $this->paginaActual){
                $clase = "active";
            } else {
                $clase = "";
            }
            // Agregamos el botón de página con su número correspondiente y la clase obtenida
            $paginacionHTML .= '<button data-pagina="' . $i . '" class="btn-paginacion '.$clase.'">' . $i . '</button> ';
        }

        // Agregamos los botones de navegación a la paginación
        if ($this->paginaActual < $this->totalPaginas) {
            $paginacionHTML .= '<button data-pagina="' . ($this->paginaActual + 1) . '" class="paginacion-extremo">&gt;</button> ';
            $paginacionHTML .= '<button data-pagina="' . $this->totalPaginas . '" class="paginacion-extremo">&gt;&gt;</button>';
        }

        // Retornamos el HTML construido para la paginación
        return $paginacionHTML;
    }
}

?>
