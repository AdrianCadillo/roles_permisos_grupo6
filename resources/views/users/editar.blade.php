@extends(layouts("app"))

@section('title_page','Editar usuario')


@section('content')
   <div class="row">
     <div class="col-12">
        <div class="card">
           <div class="card-header">
              <h4>Editar usuario</h4>
              
           </div>
           <form action="{{route("user/".$usuario[0]->id_usuario."/update")}}" method="post">
            <input type="hidden" name="token_" value="{{$this->Csrf()}}">
            <div class="card-body">
              @if ($this->existSession("errors"))
                 <div class="alert alert-danger">
                    <ul>
                      @foreach ($this->getSession("errors") as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                 </div>
                 @php $this->destroySession("errors") @endphp
              @endif
              <div class="row">
                 <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                    <div class="from-group">
                       <label for="name" class="form-label"><b>Nombre usuario <span class="text-danger">*</span></b></label>
                       <input type="text" name="name" value="{{$usuario[0]->name}}" id="name" class="form-control">
                    </div>
                 </div>

                 <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                  <div class="from-group">
                     <label for="email" class="form-label"><b>Email <span class="text-danger">*</span></b></label>
                     <input type="text" name="email" id="email" class="form-control" value="{{$usuario[0]->email}}"  placeholder="ejemplo@curso.com">
                  </div>
               </div>

              <div class="col-12">
                <div class="from-group">
                   <label for="estado" class="form-label"><b>Estado <span class="text-danger">*</span></b></label>
                   <select name="estado" id="estado" class="form-control">
                     <option value="h" @if($usuario[0]->estado=== 'h') selected @endif>Habilitado</option>
                     <option value="i" @if($usuario[0]->estado=== 'i') selected @endif>Inhabilitado</option>
                   </select>
                </div>
             </div>

              </div>
              <h5 class="mb-2">Seleccione los roles</h5>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Nombre rol</th>
                        <th>Seleccionar</th>
                      </tr>
                    </thead>
                    <tbody>
                     @foreach ($rolesNotDelUsuario as $rolenouser)
                      <tr>
                        <td>
                          <label for="role_{{$rolenouser->id_rol}}" style="cursor: pointer">
                            {{$rolenouser->nombre_rol}}
                          </label>
                        </td>
                        <td>
                         
                          <input type="checkbox" style="width: 40px;height: 40px;" id="role_{{$rolenouser->id_rol}}"
                          name="role[]" value="{{$rolenouser->id_rol}}"> 
                        </td>  
                      </tr> 
                     @endforeach

                     @foreach ($rolesDelUsuario as $roleuser)
                      <tr>
                        <td>
                          <label for="role_{{$roleuser->id_rol}}" style="cursor: pointer">
                            {{$roleuser->nombre_rol}}
                          </label>
                        </td>
                        <td>
                         
                          <input type="checkbox" style="width: 40px;height: 40px;" id="role_{{$roleuser->id_rol}}"
                          name="role[]" value="{{$roleuser->id_rol}}" checked> 
                        </td>  
                      </tr> 
                     @endforeach
                 
                    </tbody>
                  </table>
                </div>
              </div>
            
            </div>

            <div class="card-footer">
              <button class="btn btn-primary" id="save_user">Guardar</button>
            </div>
           </form>
        </div>
     </div>
   </div>
@endsection 