<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Regístrate</title>

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
      <p class="login-box-msg">Complete sus datos.</p>
  @if ($this->existSession("error"))
    <div class="alert alert-danger">
        {{$this->getSession("error")}}
    </div>
    @php
    $this->destroySession("error")
    @endphp
    @endif
      <form action="{{route("user/create-account/save")}}" method="post">
        <input type="hidden" name="token_" value="{{$this->Csrf()}}">
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="name" name="name" placeholder="Nombres compleatos..">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" id="email" name="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
         
        <div class="row">
          
          <!-- /.col -->
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">Register</button>
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

<script>
    $(document).ready(function(){
        let NombreUser = $('#name');
        let EmailUser = $('#email');
        let PasswordUser = $('#password');

        NombreUser.focus();
        NombreUser.keypress(function(event){
            if(event.which == 13){
                event.preventDefault();
                if($(this).val().trim().length == 0){
                    $(this).focus();
                }else{
                    EmailUser.focus();
                }
            }
        });

        EmailUser.keypress(function(event){
            if(event.which == 13){
                event.preventDefault();
                if($(this).val().trim().length == 0){
                    $(this).focus();
                }else{
                    PasswordUser.focus();
                }
            }
        });
    });
</script>
</body>
</html>
