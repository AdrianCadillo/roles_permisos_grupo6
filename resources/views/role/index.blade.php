@extends(layouts("app"))

@section('title_page','roles')


@section('content')
   <div class="row">
      <div class="col-12 table-responsive">
         <div class="card">
             <div class="card-header bg-primary">
                <h5 class="text-white">Lista de roles</h5>
             </div>

             <div class="card-body">
                <button  class="btn btn-primary mb-2" id="new_role">Agregar uno nuevo <i class="fas fa-plus"></i></button>
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
               
                <table class="table table-bordered nowrap" id="roles" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nombre rol</th>
                            <th>Permisos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                  </table>
             </div>
         </div>
      </div>
   </div>

   {{--- VENTANA MODALA PARA AGREGAR NUEVOS ROLES CON SUS PERMISOS---}}
   <div class="modal fade" id="modal_new_rol">
     <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Crear rol</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="nombre_rol"><b>Nombre rol (*)</b></label>
                    <input type="text" id="nombre_rol" class="form-control" placeholder="NOMBRE ROL....">
                </div>

              <h4>Asignar permisos</h4>
                <div class="table-responsive">
                     <table class="table table-bordered">
                         <thead>
                            <tr>
                                <th>#</th>
                                <th>Permiso</th>
                                <th>Seleccionar</th>
                            </tr>
                         </thead>
                         <tbody id="listapermisos"></tbody>
                     </table>
                </div>
            </div>

            <div class="modal-footer"></div>
        </div>
     </div>
   </div>
@endsection

@section('js')
    <script>
        var listaUsers;
        $(document).ready(function(){
            mostrarRoles();

            $('#new_role').click(function(){
                $('#modal_new_rol').modal("show");

                showPermisos();
            });
        });

        /*MOSTRAR A TODOS LOS ROLES CON SUS PERMISOS*/
        function mostrarRoles()
        {
            listaUsers = $('#roles').DataTable({
                ajax:{
                    url:"/role/permisos",
                    method:"GET",
                    dataSrc:"roles"
                },
                columns:[
                    {"data":"nombre_rol"},
                    {"data":null,render:function(roledata){
                        let span='';

                        if(roledata.permissions.length > 0){
                        for(permiso of roledata.permissions)
                          {
                          span+='<span class="badge bg-primary mx-1">'+permiso.nombre_permiso+'</span>';
                          }
                        }else{
                            span='<span class="text-danger">No tiene permisos asignados</span>';
                        }

                         return span;
                    }},
                    {"data":null,render:function(){
                        return `<button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                               `;
                    }}

                ]
            })
        }

        /// mostrar a todos los permisos
        function showPermisos()
        {
            let tr='';let item = 0;
            $.ajax({
                url:"/permisos-existentes",
                method:"GET",
                dataType:"json",
                success:function(response){
                    response.permisos.forEach(permiso => {
                        item++;
                        tr+=`<tr>
                         <td>`+item+`</td>
                         <td><label style="cursor:pointer" for='per_`+permiso.id_permiso+`'>`+permiso.nombre_permiso+`</label></td>
                         <td>
                          <input type='checkbox' style="width: 40px;height: 40px;" id='per_`+permiso.id_permiso+`'>
                          </td>
                         </tr>`;
                    });
                    $('#listapermisos').html(tr);
                }
            });
        }
    </script>
@endsection