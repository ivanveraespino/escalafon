<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class LayoutDatoLegajo extends Component
{
    public $title;
    public $columns;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param array $columns
     */
    public function __construct($title, $columns)
    {
        $this->title = $title;
        $this->columns = $columns;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.layout-dato-legajo');
    }
}
