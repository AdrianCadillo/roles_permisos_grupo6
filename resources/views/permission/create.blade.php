@extends(layouts("app"))

@section('title_page','crear permiso')

@section('content')
 <div class="card">
     <div class="card-header bg-info">
        <h3>Crear Permiso</h3>
     </div>

     <div class="card-body table-responsive">
         @if ($this->existSession("error"))
             <div class="alert alert-danger">
                {{$this->getSession("error")}}
             </div>
          @php $this->destroySession("error") @endphp   
         @endif

         @if ($this->existSession("error_token"))
             <div class="alert alert-danger">
                {{$this->getSession("error_token")}}
             </div>
          @php $this->destroySession("error_token") @endphp   
         @endif

         @if ($this->existSession("errors"))
             <div class="alert alert-danger">
                 <ul>
                    @foreach ($this->getSession("errors") as $error)
                       <li> {{$error}}</li>
                    @endforeach
                 </ul>
             </div>
             @php $this->destroySession("errors") @endphp   
         @endif
         <form action="{{route("permiso/save")}}" method="post" id="form_save_permiso">
            <input type="hidden" name="token_" value="{{$this->Csrf()}}">
            <div class="row">
                <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                    <div class="form-group">
                        <label for="name_permiso" class="form-label"><b>Nombre permiso <span class="text-danger">*</span></b></label>
                        <input type="text" name="name_permiso" id="name_permiso" class="form-control" autofocus>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                    <div class="form-group">
                        <label for="alias_permiso" class="form-label"><b>Alias permiso <span class="text-danger">*</span></b></label>
                        <input type="text" name="alias_permiso" id="alias_permiso" class="form-control">
                    </div>
                </div>
            </div>
         </form>
     </div>
     <div class="card-footer">
        <button class="btn btn-success" onclick="document.getElementById('form_save_permiso').submit()">Guardar <i class="fas fa-save"></i></button>
     </div>
 </div>
@endsection