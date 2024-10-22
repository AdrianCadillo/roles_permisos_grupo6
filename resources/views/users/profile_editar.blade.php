@extends(layouts("app"))

@section('title_page','Editar perfil!')


@section('content')
  <div class="row justify-content-center">
    <div class="col-xl-7 col-lg-7 col-md-6 col-12">
        <div class="card">
            <div class="card-header bg-primary">
               <h4 class="text-white">Editar perfíl</h4>
            </div>
      
            <div class="card-body">
               <div class="row">
                 <div class="col-12">
                    <div class="form-group">
                        <label for=""><b>Seleccionar foto</b></label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                 </div>
                  <div class="col-xl-6 col-lg-6 col-12">
                      <div class="form-group">
                          <label for=""><b>Nombre del usuario</b></label>
                          <input type="text"  class="form-control" value="{{$this->user()[0]->name}}">
                      </div>
                  </div>
      
                  <div class="col-xl-6 col-lg-6 col-12">
                      <div class="form-group">
                          <label for=""><b>Correo electrónico</b></label>
                          <input type="text"  class="form-control" value="{{$this->user()[0]->email}}">
                      </div>
                  </div>
               </div>
            </div>

            <div class="card-footer text-center">
                <a href="" class="btn btn-success">Guardar cambios <i class="fas fa-save"></i></a>
            </div>
         </div>
    </div>

    <div class="col-xl-5 col-lg-5 col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h5>Cambiar contraseña</h5>
            </div>
            <div class="card-body">
                <div class="m-2">
                    <input type="text" class="form-control" placeholder="Contraseña actual">
                </div>

                <div class="m-2">
                    <input type="text" class="form-control" placeholder="Nueva contraseña">
                </div>
                <div class="m-2">
                    <input type="text" class="form-control" placeholder="Confirmar la contraseña">
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
  </div>
@endsection