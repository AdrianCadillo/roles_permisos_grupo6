@extends('../resources/views/layouts/app.blade.php')

@section('title_page','usuarios')


@section('content')
   <div class="row">
      <div class="col-12 table-responsive">
         <div class="card">
             <div class="card-header bg-primary">
                <h5 class="text-white">Lista de usuarios</h5>
             </div>

             <div class="card-body">
                <a href="{{route("/user/create")}}" class="btn btn-primary mb-2">Agregar uno nuevo <i class="fas fa-plus"></i></a>
               @if ($this->existSession("success"))
                <div class="alert alert-success">
                   {{$this->getSession("success")}}
                </div>
                @php $this->destroySession("success") @endphp
                @endif
 
                
                @if ($this->existSession("error"))
                <div class="alert alert-danger">
                    Error al registrar usuario!
                </div>
                @php $this->destroySession("error") @endphp
                @endif
                
                @if ($this->existSession("error_token"))
                <div class="alert alert-danger">
                    Error en el token Csrf!
                </div>
                @php $this->destroySession("error_token") @endphp
                @endif
                
                @if ($this->existSession("existe"))
                <div class="alert alert-warning">
                    {{$this->getSession("existe")}}
                </div>
                @php $this->destroySession("existe") @endphp
                @endif
               
                <table class="table table-bordered" id="usuarios" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre usuario</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Roles</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($usuarios) && count($usuarios) > 0)
                           @foreach ($usuarios as $index=>$user)
                               <tr>
                                  <td>{{$index+1}}</td>
                                  <td>{{$user->name}}</td>
                                  <td>{{$user->email}}</td>
                                  <td>
                                    @if ($user->estado === 'h')
                                        <span class="badge bg-success">Habilitado</span>
                                        @else 
                                        <span class="badge bg-danger">Ihabilitado</span>
                                    @endif
                                  </td>
                                  <td>
                                    @foreach ($this->UserRoles($user->id_usuario) as $role)
                                        <span class="badge bg-primary">{{$role->nombre_rol}}</span>
                                    @endforeach
                                  </td>
                                  <td>
                                    <div class="row">
                                        <div class="col-auto">
                                            <a href="{{route("user/".$user->id_usuario."/editar")}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        </div>
                                        <div class="col-auto">
                                           <button class="btn btn-danger btn-sm" onclick="ConfirmaEliminado(`{{$user->id_usuario}}`,`{{$user->name}}`)"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                  </td>
                               </tr>
                           @endforeach 
                         @else 
                           
                        @endif
                    </tbody>
                  </table>
             </div>
         </div>
      </div>
   </div>
@endsection


@section('js')
    <script>
        var ListaUsuarios;
        $(document).ready(function(){
           mostrarUsuarios();
           
        });

        function mostrarUsuarios(){
            ListaUsuarios = $('#usuarios').DataTable({})
        }

        /*CONFIRMAR ELIMINADO DEL USUARIO*/
        function ConfirmaEliminado(id,nombre){
      Swal.fire({
            title: "Estas seguro de eliminar al usuario "+nombre+"?",
            text: "Al eliminar se borrará al usuario de manera automática, y no podrás recuperarlo!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
            }).then((result) => {
            if (result.isConfirmed) {
             deleteUser(id);
            }
            });
        }

        /*MÉTODO PARA ELIMINA*/
        function deleteUser(id){
            $.ajax({
                url:"user/"+id+"/delete",
                method:"POST",
                data:{
                    token_:"{{$this->Csrf()}}"
                },
                dataType:"json",
                success:function(response){
                    if(response.response){
                       Swal.fire(
                        {
                            title:"Mensaje del sistema!",
                            text:"Usuario eliminado correctamente!",
                            icon:"success"
                        }
                       ).then(function(){
                          location.href = '/users';
                       });
                    }
                }
            })
        }
    </script>
@endsection