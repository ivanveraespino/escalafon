<form id="guardarv"  method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12" id="per">
            <label for="dni">Personal</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar empleado" id="personal_id" name="personal_id">
                <div class="input-group-append">
                    <div class="btn btn-primary" ><i class="fas fa-search"></i></div>
                </div>
            </div>
            <div class="dropdown-menu" id="suggestions"></div>

        </div>
    </div>
    <div id="message3"></div>
    <div class="form-group">
        <div class="row">
            <div class="col-3 mb-3">
                <label for="regimen">Tipo</label>
                <select id="Eidtd" name="idtd" class="form-control">
                    <option value="" selected>Seleccione--</option>
                    @foreach($tdoc as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-7 mb-3">
                <label for="entidad">Nro Documento</label>
                <input type="text" class="form-control" id="Enrodoc" name="nrodoc" placeholder="Ejm. 232-2023">
            </div>
            <div class="col-2 mb-3">                        
                <label for="archivo">Archivo</label>
                <input type="file" class="form-control" id="archivo" name="archivo" >
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            <label for="periodo">Periodo</label>
            <input type="text" class="form-control" id="Eperiodo" name="periodo" >
        </div>
        <div class="col-6 mb-3">
            <label for="periodo">Dias</label>
            <input type="number" class="form-control" id="Edias" name="dias" >
        </div>
        <div class="col-6 mb-3">
            <label for="periodo">Desde</label>
            <input type="date" class="form-control" id="Edesde" name="desde" >
        </div>
        <div class="col-6 mb-3">
            <label for="periodo">Hasta</label>
            <input type="date" class="form-control" id="Ehasta" name="hasta" >
        </div>
    </div>

    <div class="form-group">
            <label for="entidad">Estado</label>
            <select id="Eestado" name="estado" class="form-control">
                    <option value="P" selected>PROGRAMADO</option>
                    <option value="R">REPROGRAMADO</option>
            </select>                    
    </div>

    <div id="camadicionales"class="row">
        <div class="row">
            <div class="col-10 mb-3">                        
                <label for="archivo">Archivo</label>
                <input type="file" class="form-control" id="archivo" name="archivo" >
            </div>
            <div class="col-2 pt-4">
            <button id="addMore" type="button" class="btn btn-primary">+</button>
        </div>
        </div>
        <div class="col-5 mb-3">
            <label for="periodo">Desde</label>
            <input type="date" class="form-control" id="EdesdeR" name="desder[]" >
        </div>
        <div class="col-5 mb-3">
            <label for="periodo">Hasta</label>
            <input type="date" class="form-control" id="Ehasta" name="hastar[]" >
        </div>
        <div class="col-2 mb-3">
            <label for="periodo">Dias</label>
            <input type="number" class="form-control" id="Edias" name="diasr[]" > 
        </div>

    </div>
    <button id="btns" type="button" class="btn btn-success btn-block btn-lg">Guardar</button>
    <button id="btne" type="button" class="btn btn-success btn-block btn-lg">Guardar</button>
</form>