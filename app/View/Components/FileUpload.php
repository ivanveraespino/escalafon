<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FileUpload extends Component
{
    public $id;
    public $name;
    public $label;
    public $accept;
    public $info;
    public $modal;
    public $value;
    public $nameFolio;

    /**
     * Crear una nueva instancia del componente.
     */
    public function __construct(
        $id = 'archivo',
        $name = 'archivo',
        $label = 'Subir Archivo',
        $accept = 'application/pdf',
        $info = 'Formato: PDF. Peso máximo: 2MB',
        $modal = false,
        $value = null,
        $nameFolio = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->accept = $accept;
        $this->info = $info;
        $this->modal = $modal;
        $this->value = $value;
        $this->nameFolio = $nameFolio;
    }

    /**
     * Generar la vista del componente.
     */
    public function render()
    {
        //if(!$this->modal){
            return view('components.file-upload');
        //}else{
          //  return view('components.file-upload-modal');
        //}
        
    }
}