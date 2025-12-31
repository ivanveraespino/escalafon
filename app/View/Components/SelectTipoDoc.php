<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectTipoDoc extends Component
{
    public $id;           
    public $name;        
    public $tdoc;         // Lista de opciones
    public $categoria;    // Categoría a filtrar
    public $label;        // Label dinámico
    public $divClass; 

    /**
     * Create a new component instance.
     *
     * @param string $id
     * @param string $name
     * @param array $tdoc
     * @param string $categoria
     * @param string $label
     * @param string|null $divClass
     */
    public function __construct($id, $name, $tdoc, $categoria, $label, $divClass = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tdoc = $tdoc;
        $this->categoria = $categoria;
        $this->label = $label;
        $this->divClass = $divClass;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select-tipo-doc');
    }
}
