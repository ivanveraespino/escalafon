<!-- resources/views/components/boton_salir.blade.php -->
<button 
    type="button" 
    class="btn btn-{{ $type ?? 'danger' }}" 
    data-bs-dismiss="modal" 
    aria-label="Close" 
    style="{{ $style ?? 'margin-right: 10px;' }}"
>
    <strong>{{ 'Cerrar' }}</strong> 
    <i class="fas fa-sign-out-alt fa-sm fa-fw"></i>
</button>
