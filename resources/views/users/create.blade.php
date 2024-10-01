@extends(layouts("app"))

@section('title_page','Crear usuarios')


@section('content')
   <div class="row">
     <div class="col-12">
        <div class="card">
           <div class="card-header">
              <h4>Crear usuarios</h4>
           </div>
           <form action="{{route("user/store")}}" method="post">
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
                       <input type="text" name="name" value="@php echo $this->getSession("name");$this->destroySession("name") @endphp" id="name" class="form-control">
                    </div>
                 </div>

                 <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                  <div class="from-group">
                     <label for="email" class="form-label"><b>Email <span class="text-danger">*</span></b></label>
                     <input type="text" name="email" id="email" class="form-control" value="@php echo $this->getSession("email");$this->destroySession("email") @endphp"  placeholder="ejemplo@curso.com">
                  </div>
               </div>

               <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                <div class="from-group">
                   <label for="password" class="form-label"><b>Password <span class="text-danger">*</span></b></label>
                   <input type="text" name="password" id="password" class="form-control" placeholder="*******************">
                </div>
              </div>

              <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                <div class="from-group">
                   <label for="estado" class="form-label"><b>Estado <span class="text-danger">*</span></b></label>
                   <select name="estado" id="estado" class="form-control">
                     <option value="h">Habilitado</option>
                     <option value="i">Inhabilitado</option>
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
                @if (isset($Roles) && count($Roles) > 0)
                  @foreach ($Roles as $index=>$role)
                        <tr>
                          <td>
                            <label for="role_{{$role->id_rol}}" style="cursor: pointer">
                              {{$role->nombre_rol}}
                            </label>
                          </td>
                          <td>
                            <input type="checkbox" style="width: 40px;height: 40px;" id="role_{{$role->id_rol}}"
                            name="role[]" value="{{$role->id_rol}}">  
                          </td>  
                        </tr>    
                  @endforeach
                   @else 
                     <td colspan="2">
                       <span class="text-danger">No hay roles para mostrar.....</span>
                     </td>
                  @endif
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