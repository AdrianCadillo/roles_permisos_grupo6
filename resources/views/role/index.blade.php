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

            <div class="modal-footer">
                <button class="btn btn-primary" id="save_role">Guardar <i class="fas fa-save"></i></button>
            </div>
        </div>
     </div>
   </div>

   {{--MODAL PARA EDITAR LOS ROLES---}}
   <div class="modal fade" id="modal_editar_rol">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
       <div class="modal-content">
           <div class="modal-header bg-info">
               <h4>Editar rol</h4>
           </div>

           <div class="modal-body">
               <div class="form-group">
                   <label for="nombre_rol"><b>Nombre rol (*)</b></label>
                   <input type="text" id="nombre_rol_editar" class="form-control" placeholder="NOMBRE ROL....">
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
                        <tbody id="listapermisosedicion"></tbody>
                    </table>
               </div>
           </div>

           <div class="modal-footer">
               <button class="btn btn-primary" id="update_role">Guardar <i class="fas fa-save"></i></button>
           </div>
       </div>
    </div>
  </div>
@endsection

@section('js')
    <script>
        var listaUsers;
        var ROLID;
        $(document).ready(function(){
            let NombreRole = $('#nombre_rol');
            let NombreRoleEditar = $('#nombre_rol_editar')
            mostrarRoles();

            $('#new_role').click(function(){
                $('#modal_new_rol').modal("show");

                showPermisos('listapermisos');
            });

            $('#save_role').click(function(){
                 saveRole(NombreRole);
            });

            $('#update_role').click(function(){
                updateRole(NombreRoleEditar,ROLID);
            });
        });

        /*MOSTRAR A TODOS LOS ROLES CON SUS PERMISOS*/
        function mostrarRoles()
        {
            listaUsers = $('#roles').DataTable({
                retrieve:true,
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
                        return `<button class="btn btn-warning btn-sm" id='editar'><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" id='delete'><i class="fas fa-trash-alt"></i></button>
                               `;
                    }}

                ]
            }).ajax.reload();

            EditarRole(listaUsers,'#roles tbody')
            ConfirmarEliminadoRole(listaUsers,'#roles tbody');
        }

        /// editar los roles
        function EditarRole(Tabla,Tbody){
           $(Tbody).on('click','#editar',function(){
             /// obtener la fila seleccionado
             let FilaRoleSelect = $(this).parents('tr');

             let Data = Tabla.row(FilaRoleSelect).data();

             $('#nombre_rol_editar').val(Data.nombre_rol);
             ROLID = Data.id_rol;
             showPermisos('listapermisosedicion','editar',ROLID);
             $('#modal_editar_rol').modal("show");
              
           });
        }

         /// editar los roles
         function ConfirmarEliminadoRole(Tabla,Tbody){
           $(Tbody).on('click','#delete',function(){
             /// obtener la fila seleccionado
             let FilaRoleSelect = $(this).parents('tr');

             let Data = Tabla.row(FilaRoleSelect).data();

             ROLID = Data.id_rol;
           
          Swal.fire({
            title: "Estas seguro de eliminar al rol "+Data.nombre_rol+"?",
            text: "Al aceptar, se quitará de la lista al rol y se enviará a la papelera!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
            }).then((result) => {
            if (result.isConfirmed) {
               ProcesoEliminadoRole(ROLID);
            }
            });
           });
        }

        function ProcesoEliminadoRole(id)
        {
            let formDeleteRole = new FormData();
                formDeleteRole.append("token_","{{$this->Csrf()}}");
            axios({
                url:"/role/"+id+"/delete",
                method:"POST",
                data:formDeleteRole
            }).then(function(response){
                if(response.data.response === 'ok'){
                    Swal.fire(
                        {
                            title:"Mensaje del sistema!",
                            text:"Rol eliminado correctamente!",
                            icon:"success"
                        }
                    ).then(function(){
                        mostrarRoles();
                    });
                }
            });
        }

        /// mostrar a todos los permisos
        function showPermisos(Tabla,accion='crear',id=null)
        {
            let tr='';let item = 0;
            $.ajax({
                url:"/permisos-existentes",
                method:"GET",
                dataType:"json",
                success:function(response){
                    if(accion === 'crear'){
                        response.permisos.forEach(permiso => {
                        item++;
                        tr+=`<tr>
                         <td>`+item+`</td>
                         <td><label style="cursor:pointer" for='per_`+permiso.id_permiso+`'>`+permiso.nombre_permiso+`</label></td>
                         <td>
                          <input type='checkbox' style="width: 40px;height: 40px;" id='per_`+permiso.id_permiso+`'
                          value=`+permiso.id_permiso+`>
                          </td>
                         </tr>`;
                    });
                    }else{
                    let permisosrol = showPermisosRol(id);  
                    let permisosnoasignedRol = showPermisosNoAsignedRol(id);   
                    response.permisos.forEach(permiso => {
                        item++;
                      permisosrol.forEach(permisorol => {
                        if(permiso.id_permiso === permisorol.id_permiso)
                         {
                            tr+=`<tr>
                         <td>`+item+`</td>
                         <td><label style="cursor:pointer" for='per_`+permiso.id_permiso+`'>`+permiso.nombre_permiso+`</label></td>
                         <td>
                          <input type='checkbox' style="width: 40px;height: 40px;" id='per_`+permiso.id_permiso+`'
                          value=`+permiso.id_permiso+` checked>
                          </td>
                         </tr>`;
                         }
                      });
                      
                      permisosnoasignedRol.forEach(permisonoasignedrol => {
                        if(permiso.id_permiso === permisonoasignedrol.id_permiso)
                         {
                            tr+=`<tr>
                         <td>`+item+`</td>
                         <td><label style="cursor:pointer" for='per_`+permiso.id_permiso+`'>`+permiso.nombre_permiso+`</label></td>
                         <td>
                          <input type='checkbox' style="width: 40px;height: 40px;" id='per_`+permiso.id_permiso+`'
                          value=`+permiso.id_permiso+`>
                          </td>
                         </tr>`;
                         }
                      });
                    });  
                   }
                    $('#'+Tabla).html(tr);
                }
            });
        }

        /*Valide la cantidad de permisos seleccionados*/
        function CantidadPermissionSelect()
        {
            let cantidad = 0;

            $('#listapermisos tr').each(function(){
              /// recuperamos todas las filas
              let fila = $(this).closest("tr");

              let PermissionCheck = fila.find('input[type=checkbox]').is(":checked");

              if(PermissionCheck){
                cantidad++;
              }
            });

            return cantidad;
        }

        /// método para guardar roles
        function saveRole(rolname)
        {
          let FormRole = new FormData();
          FormRole.append("token_","{{$this->Csrf()}}");
          FormRole.append("namerol",rolname.val());
          axios({
            method: 'post',
            url: '/role/save',
            data:FormRole
          }).then(function(response){
             if(CantidadPermissionSelect() > 0){
                asignRolePermissions('listapermisos',$('#nombre_rol').val());
             }else{
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"Rol creado correctamente!",
                        icon:"success"
                    }
                ).then(function(){
                    $('#nombre_rol').val("");
                    mostrarRoles();
                });
             }
          });
        }

        /// actualizar los roles
        function updateRole(rolname,id)
        {
          let FormRole = new FormData();
          FormRole.append("token_","{{$this->Csrf()}}");
          FormRole.append("namerol",rolname.val());
          axios({
            method: 'post',
            url: '/role/permissions/update/'+id,
            data:FormRole
          }).then(function(response){
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"Rol modificado correctamente!",
                        icon:"success"
                    }
                ).then(function(){
                    asignRolePermissions('listapermisosedicion',$('#nombre_rol_editar').val(),'update');
                    $('#nombre_rol').val("");
                    mostrarRoles();
                });
             
          });
        }

        /**Método para asignacion de permisos del sistema al rol creado**/
        function asignRolePermissions(tabla,roldata,accion='save')
        {
            let message = '';
            $('#'+tabla+' tr').each(function(){
              /// recuperamos todas las filas
              let fila = $(this).closest("tr");

              let PermissionCheck = fila.find('input[type=checkbox]').is(":checked");

              if(PermissionCheck){
                let Permission = fila.find('input[type=checkbox]').val();

                message = asignarPermisos(Permission,roldata);
              }
            }); 

            if(accion === 'save')
            {
                if(message === 'ok'){
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"Rol creado correctamente!",
                        icon:"success"
                    }
                ).then(function(){
                    $('#nombre_rol').val("");
                    $('#listapermisos input[type=checkbox]').prop("checked",false);
                    mostrarRoles();
                });
            }else{
                Swal.fire(
                    {
                        title:"Mensaje del sistema!",
                        text:"Error al crear rol!",
                        icon:"error"
                    }
                );
            }
        }
        }

        /// proceso de ajax para asignar permisos a roles
        function asignarPermisos(permiso_data,roldata){
            let mensaje = '';
            $.ajax({
                url:"/role/asignar/permisos",
                method:"POST",
                async:false,
                data:{
                    token_:"{{$this->Csrf()}}",
                    namerol:roldata,
                    permiso:permiso_data
                },
                dataType:"json",
                success:function(response){
                    mensaje = response.response;
                }
            });

            return mensaje;
        }

        /// mostrar los permisos de un rol
        function showPermisosRol(roliddata){
        let permisos = [];
          $.ajax({
            method: 'get',
            url: '/role/permissions/'+roliddata,
            async:false,
            dataType:"json",
            success:function(response){
                permisos = response.permisosrol;
            }
        });

        return permisos;
        }

        /// mostrar los permisos de un rol
        function showPermisosNoAsignedRol(roliddata){
        let permisosNoAsignados = [];
          $.ajax({
            method: 'get',
            url: '/role/permisos-no-asignados/'+roliddata,
            async:false,
            dataType:"json",
            success:function(response){
                permisosNoAsignados = response.response;
            }
        });

        return permisosNoAsignados;
        }
    </script>
@endsection