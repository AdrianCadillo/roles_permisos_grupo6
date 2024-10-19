@extends(layouts("app"))

@section('title_page','Permisos')


@section('content')
 <div class="card">
     <div class="card-header bg-info">
        <h3>Permisos existentes</h3>
     </div>

     <div class="card-body table-responsive">
        @if ($this->existSession("success"))
             <div class="alert alert-success">
                {{$this->getSession("success")}}
             </div>
          @php $this->destroySession("success") @endphp   
        @endif
        @if ($this->can("permiso.index"))
        <a href="{{route("permiso/create")}}" class="btn btn-primary mb-2">Agregar uno nuevo <i class="fas fa-plus"></i></a>
        @endif
        <table class="table table-bordered nowrap responsive" id="TablaPermisos" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre permiso</th>
                    <th>Alias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if (count($permisos) > 0)
                    @foreach ($permisos as $index=>$permiso)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{strtoupper($permiso->nombre_permiso)}}</td>
                            <td>{{$permiso->alias_permiso}}</td>
                            <td>
                              <div class="row">
                                 @if ($this->can("permiso.editar"))
                                 <div class="col-auto">
                                  <a href="" class="btn btn-warning btn-sm btn-rounded"><i class="fas fa-edit"></i></a>    
                                  </div> 
                                 @endif
                                 
                                 @if ($this->can("permiso.delete"))
                                 <div class="col-auto">
                                  <form action="" method="post">
                                    <input type="hidden" value="{{$this->Csrf()}}">
                                    <button class="btn btn-danger btn-sm btn-rounded"><i class="fas fa-trash-alt"></i></button>
                                  </form>
                                </div>
                                 @endif
                              </div>  
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
     </div>
 </div>
@endsection