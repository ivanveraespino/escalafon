<div>
    <div class="form-floating">
        <select id="{{ $id ?? 'archivo' }}" name="{{ $name ?? 'archivo' }}" class="form-select">
            
        </select>                      
        <label >{{ $label ?? 'archivo' }}</label>
    </div>
</div>

@push('scripts')
<script>
      $.ajax({
        url: $route,
        type: 'GET',
        data: { limit: 10 }, 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            select.append('<option value="">Seleccione una opción...</option>'); // Restaurar opción predeterminada
            data.forEach(item => {
                let option = $('<option>').val(item.id).text(item.nombre);
                select.append(option);
            });

            select.trigger("chosen:updated"); // Actualizar Chosen con las nuevas opciones
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al cargar los datos iniciales.'
            });
        }
    });
</script>

@endpush