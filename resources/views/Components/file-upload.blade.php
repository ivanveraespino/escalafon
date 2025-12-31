<div class="form-group box1 flex-1" id="{{ $id ?? 'archivo-url' }}-upload" >
    <label for="{{ $id ?? 'archivo' }}" class="btn btn-outline-success m-0 d-flex flex-0 align-items-center justify-content-center gap-1">
        <i class="lni lni-upload-1 h-100 "></i>Subir {{ $label ?? 'Archivo' }}
    </label>

    <input 
        type="file" 
        accept="{{ $accept ?? 'application/pdf' }}" 
        class="archivo form-control d-none" 
        id="{{ $id ?? 'archivo' }}" 
        name="{{ $name ?? 'archivo' }}" 
    />

    <input id="{{ $id ?? 'archivo' }}-folios"  type="number" class="form-control" name="{{ $nameFolio ?? 'nro_folios' }}" style="display:none;">

    <div >
        <div id="{{ $id ?? 'archivo' }}-info" class="form-text col">{{ $info ?? 'Formato: PDF. Peso máximo: 2MB' }}</div>
        <div id="{{ $id ?? 'archivo' }}-labelFolios" class="form-text col"></div>
    </div>
</div>


    <button id="{{ $id ?? 'archivo-url' }}-ver"  type="button" class="btn btn-primary ver-pdf col" style="display:none;  height: 58px;" data-pdf="">
        <i class="fas fa-file-pdf"></i> Ver {{ $label ?? 'Archivo' }}
    </button>
    <div class="m-0 box3" style="display:none; " id="{{ $id ?? 'archivo-url' }}-label"> 
        <!-- <p class="m-0 ml-2" style="color:#6c757d; font-size: 12px;">Archivo {{ $label ?? 'Archivo' }}</p> -->
        <p class="m-0" style="align-self: center;">No existe {{ $label ?? 'Archivo' }}</p>
    </div>

<style>
    .box1{
        margin-bottom: 0px;
    }
    .box3{
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 58px;
        margin-bottom: 19px;
        border: 1px solid gray;
        border-radius: 8px;
        
    }
</style>

<script>
$(document).ready(function () {
        const input = document.getElementById('{{ $id ?? 'archivo' }}');
        const info = document.getElementById('{{ $id ?? 'archivo' }}-info');
        const labelFolios = document.getElementById('{{ $id ?? 'archivo' }}-labelFolios');
        const nroFoliosInput = document.getElementById('{{ $id ?? 'archivo' }}-folios');
        const form = input.closest('form'); // Encuentra el formulario que contiene el input

        input.addEventListener('change', async function () {
            const fileName = this.files[0]?.name || '{{ $info ?? 'Formato: PDF. Peso máximo: 2MB' }}';
            info.textContent = fileName;

            const file = this.files[0];
            if (file) {
                const arrayBuffer = await file.arrayBuffer();
                const pdfDoc = await PDFLib.PDFDocument.load(arrayBuffer);
                const numPages = pdfDoc.getPageCount();
                nroFoliosInput.value = numPages;
                labelFolios.textContent = `Nro Folios: ${numPages}`;
            }
        });

        form.addEventListener('reset', function () {
            input.value = '';
            info.textContent = '{{ $info ?? "Formato: PDF. Peso máximo: 2MB" }}'; 
            labelFolios.textContent = ''; 
            nroFoliosInput.value = ''; 
        });
    });
</script>

