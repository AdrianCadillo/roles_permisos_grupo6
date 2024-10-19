<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Activación de mi cuenta</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{assets("plugins/fontawesome-free/css/all.min.css")}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{assets("plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{assets("dist/css/adminlte.min.css")}}">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Tecnology</b>Soft</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Activar mi cuenta!</p>

       @if ($this->existSession("success_send_email"))
           <div class="alert alert-success">
             {{$this->getSession("success_send_email")}}
           </div>
           @php
             $this->destroySession("success_send_email")    
           @endphp
       @endif

       @if ($this->existSession("error_code"))
           <div class="alert alert-danger">
             {{$this->getSession("error_code")}}
           </div>
           @php
             $this->destroySession("error_code")    
           @endphp
       @endif
      <form action="{{route("user/activacion/account/code/".$this->get("id"))}}" method="post">
        <input type="hidden" name="token_" value="{{$this->Csrf()}}">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="codigo" placeholder="Ingresar su código..">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
         
        <div class="row">
          
          <!-- /.col -->
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">Activar mi cuenta</button>
          </div>
          <!-- /.col -->
        </div>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="{{assets("plugins/jquery/jquery.min.js")}}"></script>
<!-- Bootstrap 4 -->
<script src="{{assets("plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{assets("dist/js/adminlte.min.js")}}"></script>
</body>
</html>
