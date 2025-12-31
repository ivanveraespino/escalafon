<!-- resources/views/datosficha/arch_adicionales.blade.php -->
<style>
    .required {
        color: red;
    }
    .modal-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        text-align: center;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

</style>

  <div class="form-row gap-3">

        <div class="form-floating">                      
            <select id="Etipo_archivo" name="tipo_archivo" class="form-select" >
                <option value=""></option>
                @foreach($tarch as $t)
                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                @endforeach
            </select>  
            <label class="required">Tipo de Archivo<span class="required">*</span></label> 
        </div>

        <div class="form-floating">             
            <select id="Ecategoria_archivo" name="categoria_archivo" class="form-select" >
                <option value=""></option>
                @foreach($cat as $t)
                <option value="{{ $t->id }}" data-categoria="{{ $t->nombre }}">{{ $t->nombre }}</option>
                @endforeach
            </select>   
            <label class="required">Categoria de Archivo<span class="required">*</span></label>            
        </div>

        <x-file-upload id="Earchivo" name="archivo" label="Copia DNI"/>
        
    </div>
    <button type="submit" id="btnfam" class="btn btn-success ml-auto mr-0"  style="margin-right: 10px;">Guardar</button>
