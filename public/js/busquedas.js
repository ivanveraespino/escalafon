let debounceTimeout;
function debounceSearch(callback, delay = 300) {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(callback, delay);
}

//FILTROS Y SELECCIONAR PERSONAL
function setupFilters(dataTable, chosenSelector, dtSearchInputSelector) {
    $(dtSearchInputSelector).on('keyup', function () {
        $(chosenSelector).val('').trigger('chosen:updated');
    });

    $(chosenSelector).on('change', function () {
        var selectedText = $(this).find('option:selected').val();
        dataTable.search('').draw(); 
        dataTable.ajax.reload();    
    });
}


//SELECT2

function initializeSelect2(selectIds, options) {
    const { url, placeholder = 'Seleccione una opción', minInputLength = 1, loadAllIfEmpty = false, preselectedData = [] } = options;
    // Iterar sobre los IDs y aplicar la configuración
    selectIds.forEach((selectId) => {
        const preselected = preselectedData.find(p => p.selectId === selectId);
        $(`#${selectId}`).select2({
            theme: 'bootstrap-5', // Tema compatible con Bootstrap 5
            ajax: {
                url: url, // Usar el mismo URL para todas las instancias
                dataType: 'json',
                data: function (params) {
                    if (loadAllIfEmpty && !params.term) {
                        return {}; // Sin término de búsqueda, cargar todos los registros
                    }
                    return {
                        buscar: params.term // Término de búsqueda
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nombre // Ajustar según el formato del API
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: placeholder,
            minimumInputLength: minInputLength,
        });

        // Insertar preselección si existe en preselectedData
        if (preselected && preselected.text && preselected.id) {
            const option = new Option(preselected.text, preselected.id, true, true);
            $(`#${selectId}`).append(option).trigger('change');
        }
    });
}

function initializeChosen(selectId, route, preselectedId = null) {
    let select = $(selectId);

    // Inicializar el select con Chosen
    select.chosen({
        no_results_text: "No se encontraron resultados",
        placeholder_text_single: "Seleccione una opción...",
        search_contains: true,
        width: "100%"
    });

    $.ajax({
        url: route,
        type: 'GET',
        data: { limit: 10 },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            select.append('<option value="">Seleccione una opción...</option>'); // Restaurar opción predeterminada
            data.forEach(item => {
                let option = $('<option>')
                    .val(item.id)
                    .text(item.nombre);

                // Si el ID coincide con el preseleccionado, marcar como seleccionado
                if (preselectedId && item.id == preselectedId) {
                    option.attr('selected', true);
                }

                select.append(option);
            });

            select.trigger("chosen:updated"); // Actualizar Chosen con las nuevas opciones

            // Verificar si el valor preseleccionado está entre los primeros 10 elementos
            if (preselectedId && !data.some(item => item.id == preselectedId)) {
                // Si el ID no está en los primeros 10, realizar una búsqueda
                select.trigger('chosen:search', preselectedId);
            }
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

    // Escuchar el evento de búsqueda en el campo Chosen
    select.siblings('.chosen-container').find('.chosen-search input').on('keyup', function () {
        let searchQuery = $(this).val();
        if (searchQuery.length >= 2) {
            debounceSearch(() => {
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: { search: searchQuery }, // Pasar el texto de búsqueda como parámetro
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        let chosenSearchInput = select.siblings('.chosen-container').find('.chosen-search input');
                        let currentSearchText = chosenSearchInput.val(); // Capturar el texto actual del campo de búsqueda

                        select.empty(); // Limpiar las opciones actuales
                        select.append('<option value="">Seleccione una opción...</option>'); // Restaurar opción predeterminada

                        // Añadir las opciones recibidas del servidor
                        data.forEach(item => {
                            let option = $('<option>')
                                .val(item.id)
                                .text(item.nombre);

                            // Si el ID coincide con el preseleccionado, marcar como seleccionado
                            if (preselectedId && item.id == preselectedId) {
                                option.attr('selected', true);
                            }

                            select.append(option);
                        });

                        select.trigger("chosen:updated"); // Actualizar Chosen con las nuevas opciones

                        // Restaurar el texto del campo de búsqueda
                        chosenSearchInput.val(currentSearchText);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al cargar los datos.'
                        });
                    }
                });
            }, 300);
        }
    });
}



async function  initializeChosen2 (selectIds, route, preselectedId = null)  {
    // Convertir selectIds en un array si no lo es
    if (!Array.isArray(selectIds)) {
        selectIds = [selectIds];
    }

    await $.ajax({
        url: route,
        type: 'GET',
        data: { limit: 10 },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            selectIds.forEach(selectId => {
                let select = $(selectId);
                select.chosen({
                    no_results_text: "No se encontraron resultados",
                    placeholder_text_single: "Seleccione una opción...",
                    search_contains: true,
                    width: "100%"
                });

                select.append('<option value="">Seleccione una opción...</option>'); // Restaurar opción predeterminada
                data.forEach(item => {
                    let option = $('<option>')
                        .val(item.id)
                        .text(item.nombre);
    
                    // Si el ID coincide con el preseleccionado, marcar como seleccionado
                    if (preselectedId && item.id == preselectedId) {
                        option.attr('selected', true);
                    }
    
                    select.append(option);
                });
    
                select.trigger("chosen:updated"); // Actualizar Chosen con las nuevas opciones
    
                // Verificar si el valor preseleccionado está entre los primeros 10 elementos
                if (preselectedId && !data.some(item => item.id == preselectedId)) {
                    // Si el ID no está en los primeros 10, realizar una búsqueda
                    select.trigger('chosen:search', preselectedId);
                }

                select.siblings('.chosen-container').find('.chosen-search input').on('keyup', function () {
                    let searchQuery = $(this).val();
                    if (searchQuery.length >= 2) {
                        debounceSearch(() => {
                            $.ajax({
                                url: route,
                                type: 'GET',
                                data: { search: searchQuery }, // Pasar el texto de búsqueda como parámetro
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (data) {
                                    let chosenSearchInput = select.siblings('.chosen-container').find('.chosen-search input');
                                    let currentSearchText = chosenSearchInput.val(); // Capturar el texto actual del campo de búsqueda
            
                                    select.empty(); // Limpiar las opciones actuales
                                    select.append('<option value="">Seleccione una opción...</option>'); // Restaurar opción predeterminada
            
                                    // Añadir las opciones recibidas del servidor
                                    data.forEach(item => {
                                        let option = $('<option>')
                                            .val(item.id)
                                            .text(item.nombre);
            
                                        // Si el ID coincide con el preseleccionado, marcar como seleccionado
                                        if (preselectedId && item.id == preselectedId) {
                                            option.attr('selected', true);
                                        }
            
                                        select.append(option);
                                    });
            
                                    select.trigger("chosen:updated"); // Actualizar Chosen con las nuevas opciones
            
                                    // Restaurar el texto del campo de búsqueda
                                    chosenSearchInput.val(currentSearchText);
                                },
                                error: function (xhr) {
                                    console.error(xhr.responseText);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Hubo un problema al cargar los datos.'
                                    });
                                }
                            });
                        }, 300);
                    }
                });
            });
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

    
}