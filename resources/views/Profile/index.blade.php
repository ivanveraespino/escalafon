@php
use Carbon\Carbon;
$hoy = Carbon::now();
$definido=0;
@endphp
<div class="container">
	<div class="row">
		<div class="card shadow p-0">
			<div class="card-header">
				<h1 class="card-title text-munilc">
					Usuarios del sistema
				</h1>

			</div>
			<div class="card-body">
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevoUsuario" class="btn btn-mplc"><i class="fas fa-plus-circle"></i> Nuevo Usuario</button>
				<table class="table table-bordered table-striped display" id="user-table">
					<thead>
						<tr>
							<th>NOMBRE</th>
							<th>EMAIL</th>
							<th>CREADO</th>
							<th>EXPIRACIÓN</th>
                            <th>ACCIÓN</th>
							<!-- <th>MODIFICAR CONTRASEÑA</th>-->
						</tr>
					</thead>
                     <tbody>
                        @foreach ($users as $user)
                        @php
                            $definido=0;
                            $expira=$user->expiration;
                            if(isset($user->expiration))
                            {
                                $definido=1;
                                if($hoy<$user->expiration)
                                    $definido=2;
                            }
                            else
                                $definido=0;
                        @endphp
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d-m-Y') }}</td>
                                <td>{{{ $definido>0 ? substr($expira,0 ,10) : 'No definido' }}}</td>
                                <td>
                                    @if($definido==2)
                                    <button type="button" id="{{ $user->id }}" OnClick="ampliarUsuario({{ $user->id }})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    @endif
                                    @if($definido==1)
                                    <button type="button" id="{{ $user->id }}" OnClick="expirarUsuario({{ $user->id }})" class="btn btn-info"><i class="fas fa-repeat"></i></button>
                                    @endif
                                    @if($definido==0)
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
				</table>            
			</div>
		</div>
		
	</div>
	<!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
</div>
<div class="modal fade" id="nuevoUsuario" tabindex="-1" role="dialog" aria-labelledby="nuevoUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-munilc" id="exampleModalLabel">Elija Usuario</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <div class="mb-3">
            <select class="form-select" name="iduser" id="iduser" >
              @if (isset($admisibles))
                @foreach ($admisibles as $admisible)
                    <option value="{{ $admisible->id_personal }}" > {{ $admisible->nombre }}</option>
                @endforeach
              @else 
                  <option selected value="0">Sin datos</option>
              @endif
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-mplc" OnClick="validarOpcion()">Agregar Usuario</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>

<script>
    $(document).ready(function() {
        $('#user-table').DataTable();
        $("#user-table").addClass("compact nowrap w-100");
    });
    function validarOpcion(){
        
        var iduser = $("#iduser").val();
        $.ajax({
            url: 'profile/saveUser',
            data: {
                iduser:iduser
            },
            success:function(responsedata) {
                 $('#nuevoUsuario').modal('hide');
                loadContent('{{route('profile')}}');
            }
            
            
        })
       
    }
    function expirarUsuario(id){
        $.ajax({
            url: 'profile/expireUser',
            data: {
                id:id
            },
            success:function(responsedata) {
                loadContent('{{ route('profile') }}');
            }
        });
    }
    function ampliarUsuario(id){
        $.ajax({
            url: 'profile/expandUser',
            data: {
                id:id
            },
            success:function(responsedata) {
                loadContent('{{route('profile')}}');
            }
        });
    }
</script>
