@extends(layouts("app"))

@section('title_page','perfíl del usuario!')


@section('content')
  <div class="row justify-content-center">
    <div class="col-xl-7 col-lg-7 col-md-6 col-12">
        <div class="card">
            <div class="card-header bg-primary">
               <h4 class="text-white">Mi perfíl</h4>
            </div>
      
            <div class="card-body">
               <div class="row">
                 <div class="col-12 text-center">
                    @php
                        $Foto = $this->user()[0]->foto != null ? 'fotos/'.$this->user()[0]->foto:'assets/img/anonimo.png';
                    @endphp
                    <img src="{{URL_BASE.$Foto}}" alt=""  style="width: 120px;height: 120px;border-radius: 50%">
                 </div>
                  <div class="col-xl-6 col-lg-6 col-12">
                      <div class="form-group">
                          <label for=""><b>Nombre del usuario</b></label>
                          <input type="text" readonly class="form-control" value="{{$this->user()[0]->name}}">
                      </div>
                  </div>
      
                  <div class="col-xl-6 col-lg-6 col-12">
                      <div class="form-group">
                          <label for=""><b>Correo electrónico</b></label>
                          <input type="text" readonly class="form-control" value="{{$this->user()[0]->email}}">
                      </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                        <label for=""><b>Estado</b></label>
                        <input type="text" readonly class="form-control" value="{{$this->user()[0]->estado === 'h' ? 'HABILITADO':'INHABILITADO'}}">
                    </div>
                </div>
                  
               </div>
            </div>

            <div class="card-footer text-center">
                <a href="{{route("profile/editar")}}" class="btn btn-warning">Editar mi perfíl <i class="fas fa-edit"></i></a>
            </div>
         </div>
    </div>
  </div>
@endsection