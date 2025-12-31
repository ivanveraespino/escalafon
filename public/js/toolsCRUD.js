
//SHOW FORM
const toggleButton = document.getElementById("toggleFormButton");
const formContainer = document.getElementById("formContainer");
//VARIABLE DE VLP
var adelantado = 0;
let vpl_act_valor = ''; 

//PASAR VALOR PARA EDITAR
var id_tables;

document.addEventListener("DOMContentLoaded", function () {

    document.body.addEventListener("click", function (event) {
        if (event.target.id === "toggleFormButton") {

            if (formContainer.classList.contains("show")) {
                formContainer.classList.remove("show");
                setTimeout(() => {
                    formContainer.style.display = "none";
                }, 300);
            } else {
                formContainer.style.display = "block";
                setTimeout(() => {
                    formContainer.classList.add("show");
                }, 8);
            }
        }
    });
});



function onClickSaveAdd(pathAdd, pathData){
    var formtra = $('#formAdd')[0]; 
    var formData = new FormData(formtra);
    let id = $('#dnipcv')[0].value

    formData.append('adelantado', adelantado);
    var obs = formData.get('observaciones'); 
    // Condicional para verificar adelantado y obs
    if (adelantado === 1 && (obs === null || obs.trim() === '')) {
        formData.append('observaciones', 'ADELANTADO'); 
    }

    if (id){
        formData.append('personal_id', id); 
        $.ajax({
            url: `/${pathAdd}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#formAdd').modal('hide');
                formtra.reset();
                reset_all_vlp();
                dataTable.ajax.url(`/${pathData}`).load();
                setTimeout(() => {
                    formContainer.style.display = "none";
                }, 300);                
                Swal.fire({
                    icon: 'success',
                    title: 'Registro agregado!',
                    showConfirmButton: false, 
                    timer: 1000 
                });
            }
        });
    } else
    {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe seleccionar un trabajador',
            showConfirmButton: false, 
            timer: 1000 
        });
    }
}

function onClickSaveEdit(pathEdit, pathData, formname = null, id = null) {
    var form = formname ? $(`#${formname}`)[0] : $('#formEdit')[0];
    var formData = new FormData(form);
    formData.append('adelantado', adelantado);
    formData.append('_method', 'PATCH'); 
    if (id !== null) {
        id_tables = id;
    }
    $.ajax({
        url: `/${pathEdit}/${id_tables}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            dataTable.ajax.url(`/${pathData}`).load();
            Swal.fire({
                icon: 'success',
                title: '¡Campo actualizado!',
                text: 'El campo se ha actualizado correctamente.',
                showConfirmButton: false, 
                timer: 1000 
            });
            formContainer.style.display = "none";
            reset_all_vlp();
            $('#modalFormEdit').modal('hide');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al guardar.',
                showConfirmButton: false, 
                timer: 1000 
            });
        }
    });
}

function viewCRUD(id,path, title='') {
    $('#formEdit')[0].reset();
    $('#btnSubmit').hide();
    $('#modalTitle').text('Detalle '+title);

    return $.ajax({
        url: `/${path}/${id}`,
        type: 'GET',
        success: function(data) {
            $.each(data, function(key, value) {
                const keywords = ['fecha', 'desde', 'hasta']; 
                if (keywords.some(keyword => key.toLowerCase().includes(keyword))) {
                    value = value ? new Date(value).toISOString().split('T')[0] : '';
                }
                if (key.includes('archivo')) {
                    $('#Ed' + key+'-upload').hide();
                
                    if(value){
                        $('#Ed' + key+'-ver').attr('data-pdf', value);
                        $('#Ed' + key+'-ver').show();
                        $('#Ed' + key+'-label').hide();
                        
                    }
                    else{
                        $('#Ed' + key+'-ver').hide();
                        $('#Ed' + key+'-label').show();
                    }
                } else{
                    if($('#Ed' + key).hasClass('chosen')){
                        $('#Ed' + key).val(value);
                    }else{
                        $('#Ed' + key).val(value);
                        $('#Ed' + key).prop('disabled', true);
                    }
                }
            });
            $('.vlp-item').hide();
            $('#modalFormEdit').modal('show');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al cargar los datos .',
                showConfirmButton: false, 
                timer: 1000 
            });
        }
    });
}

function editCRUD(id,path, title='') {
    $('#formEdit')[0].reset();
    $('#btnSubmit').show();
    $('#modalTitle').text(`Editar ${title}`);
    //ASIGNAR OTROS VALORES ID VALIDACION
    return $.ajax({
        url: `/${path}/${id}`,
        type: 'GET',
        success: function(data) {
            $.each(data, function(key, value) {
                const keywords = ['fecha', 'desde', 'hasta']; 
                if (keywords.some(keyword => key.toLowerCase().includes(keyword))) {
                    value = value ? new Date(value).toISOString().split('T')[0] : '';
                }
                //PARA EDITAR un registro y enviar id de ese campo al guardar
                if (key === 'id') {
                    id_tables = value; 
                }
                //PARA EDITAR VLP
                vpl_act_valor = data.dias  ?? '';

                if (key === 'acuentavac') {
                    if (value === 'SI') {
                        $('.acuenta_vac_cont').show();

                    } else {
                        $('.acuenta_vac_cont').hide();
                    }
                }

                if (key.includes('archivo')) {
                    $('#Ed' + key+'-upload').show();
                    $('#Ed' + key+'-ver').hide();
                    $('#Ed' + key+'-label').hide();
                    $('#Ed' + key).prop('disabled', false);
                } else{
                    if($('#Ed' + key).hasClass('chosen')){
                        $('#Ed' + key).val(value);
                    }else{
                        $('#Ed' + key).val(value);
                        $('#Ed' + key).prop('disabled', false);
                    }

                }
            });
            $('.vlp-item').hide();
            $('#modalFormEdit').modal('show');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al cargar los datos .',
                showConfirmButton: false, 
                timer: 1000 
            });
        }
    });
}

function deleteCRUD(id, pathDel, pathData) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Borrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/${pathDel}/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    //reset_guardar();
                    if (response.success) {
                        dataTable.ajax.url(`/${pathData}`).load();

                        Swal.fire({
                            icon: 'success',
                            title: `Registro eliminado`,
                            text: response.success,
                            showConfirmButton: false, 
                            timer: 1000 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            showConfirmButton: false, 
                            timer: 1000 
                            });
                    }
                }
            });
        }
    });
}          

function onClickSaveAdd2(pathAdd, pathData) {
    var formtra = $('#formAdd')[0]; 
    console.log(formtra);
    var formData = new FormData();

    $('#formAdd .save').each(function () {
        if (this.type === 'file' && this.files.length > 0) {
            formData.append(this.name, this.files[0]); 
        } else if (this.name && !this.disabled) {
            formData.append(this.name, this.value);
        }
    });

    let id = $('#dnipcv')[0].value;
    if (id) {
        formData.append('personal_id', id);

        $.ajax({
            url: `/${pathAdd}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#formAdd').modal('hide');
                formtra.reset();
                dataTable.ajax.url(`/${pathData}`).load();

                Swal.fire({
                    icon: 'success',
                    title: 'Registro agregado!',
                    showConfirmButton: false, 
                    timer: 1000 
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe seleccionar un trabajador',
            showConfirmButton: false, 
            timer: 1000 
        });
    }
}