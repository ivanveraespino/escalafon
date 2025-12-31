<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchInput extends Component
{
    public $route;

    /**
     * Crea una nueva instancia del componente.
     *
     * @param string $route Ruta para la búsqueda AJAX.
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('components.search-busqueda');
    }
}
