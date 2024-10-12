@extends(layouts("app"))

@section('title_page','Permisos')


@section('content')
 <div class="card">
     <div class="card-header bg-info">
        <h3>Permisos existentes</h3>
     </div>

     <div class="card-body table-responsive">
        <a href="{{route("permiso/create")}}" class="btn btn-primary mb-2">Agregar uno nuevo <i class="fas fa-plus"></i></a>
        <table class="table table-bordered nowrap responsive" id="TablaPermisos" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre permiso</th>
                    <th>Alias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
     </div>
 </div>
@endsection