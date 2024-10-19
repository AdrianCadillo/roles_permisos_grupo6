<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{assets("plugins/fontawesome-free/css/all.min.css")}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{assets("plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{assets("dist/css/adminlte.min.css")}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Tecnology</b>Soft</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingrese sus credenciales!</p>

      <form action="{{route("login")}}" method="post">
         <input type="hidden" name="token_" value="{{$this->Csrf()}}">
         @if ($this->existSession("error"))
             <div class="alert alert-danger">
                {{$this->getSession("error")}}
             </div>
             @php $this->destroySession("error")  @endphp
         @endif

         @if ($this->existSession("close_success"))
             <div class="alert alert-success">
                {{$this->getSession("close_success")}}
             </div>
             @php $this->destroySession("close_success")  @endphp
         @endif
        <div class="form-group">
            <label for="rol" class="form-label"><b>Rol</b></label>
            <select name="rol" id="rol" class="form-control">
                @foreach ($roles as $role)
                    <option value="{{$role->id_rol}}">{{$role->nombre_rol}}</option>
                @endforeach
            </select>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="login" id="login" class="form-control"
          value="{{$this->existSession("login")?$this->getSession("login"):''}}" @php $this->destroySession("login") @endphp placeholder="Username|email..">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">
                Recordar mi sesión
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <div class="col-auto mx-1">
            <a href="{{route("user/create-account")}}">Regístrate</a>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{assets("plugins/jquery/jquery.min.js")}}"></script>
<!-- Bootstrap 4 -->
<script src="{{assets("plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{assets("dist/js/adminlte.min.js")}}"></script>

<script>
    $(document).ready(function(){
        let InpuLogin = $('#login');
        InpuLogin.focus();

        InpuLogin.keypress(function(evento){
          if(evento.which == 13){
            evento.preventDefault();
            if($(this).val().trim().length == 0){
                $(this).focus();
            }else{
                $('#password').focus();
            }
          }
        });
    });
</script>
</body>
</html>
