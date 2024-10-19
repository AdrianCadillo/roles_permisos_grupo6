@extends(layouts("app"))

@section('title_page','Permisos')


@section('content')
 <div class="card">
    <div class="card-header">
        Página no authorizado
    </div>

    <div class="card-body">
        <div class="alert alert-danger">
            No estas authorizado para ver está página!
        </div>
    </div>
 </div>
@endsection