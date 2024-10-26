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
            <form action="{{route("user/profile/modificar")}}" method="post" id="form_update_perfil" enctype="multipart/form-data">
               <input type="hidden" name="token_" value="{{$this->Csrf()}}">
                <div class="row">
                    <div class="col-12">
                        @if ($this->existSession("success"))
                            <div class="alert alert-success">
                                {{$this->getSession("success")}}
                            </div>
                            @php
                                $this->destroySession("success")
                            @endphp
                        @endif

                        @if ($this->existSession("error"))
                            <div class="alert alert-danger">
                                {{$this->getSession("error")}}
                            </div>
                            @php
                                $this->destroySession("error")
                            @endphp
                        @endif
                        <div class="form-group">
                            <label for=""><b>Seleccionar foto</b></label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                     </div>
                      <div class="col-xl-6 col-lg-6 col-12">
                          <div class="form-group">
                              <label for=""><b>Nombre del usuario</b></label>
                              <input type="text" name="name" class="form-control" value="{{$this->user()[0]->name}}">
                          </div>
                      </div>
          
                      <div class="col-xl-6 col-lg-6 col-12">
                          <div class="form-group">
                              <label for=""><b>Correo electrónico</b></label>
                              <input type="text" name="email" class="form-control" value="{{$this->user()[0]->email}}">
                          </div>
                      </div>
               </div>
            </form>
            </div>

            <div class="card-footer text-center">
                <button class="btn btn-success" onclick="document.getElementById('form_update_perfil').submit()">Guardar cambios <i class="fas fa-save"></i></button>
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
                    <input type="password" class="form-control" id="password_actual" placeholder="Contraseña actual">
                </div>

                <div class="m-2">
                    <input type="password" class="form-control" id="nuevo_password" disabled placeholder="Nueva contraseña">
                </div>
                <div class="m-2">
                    <input type="password" class="form-control" id="confirmar_password" disabled placeholder="Confirmar la contraseña">
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-success" disabled  id="save_password">Guardar</button>
            </div>
        </div>
    </div>
  </div>
@endsection

@section('js')
    <script>
        var RUTA = "{{URL_BASE}}";
        $(document).ready(function(){
            let PasswordActual = $('#password_actual');
            let PasswordNuevo = $('#nuevo_password');
            let PasswordConfirm = $('#confirmar_password');
            let ButtonSavePassword = $('#save_password');
 
            PasswordActual.keyup(function(){
               if($(this).val().trim().length > 0){
                 if(ValidatePassword($(this).val()) === 'ok'){
                    PasswordNuevo.attr("disabled",false); 
                    PasswordConfirm.attr("disabled",false); 
                    PasswordNuevo.focus();
                 }else{
                    PasswordNuevo.attr("disabled",true);
                    PasswordConfirm.attr("disabled",true); 
                    ButtonSavePassword.attr("disabled",true);
                 }
               }else{
                PasswordNuevo.attr("disabled",true);
                PasswordConfirm.attr("disabled",true); 
                ButtonSavePassword.attr("disabled",true);
               }
            });
        PasswordNuevo.keypress(function(evento){
            
            if(evento.which == 13){
            
            if($(this).val().trim().length == 0){
            $(this).focus();
            }else{
            PasswordConfirm.focus();
            }
            }
            }); 

        PasswordConfirm.keyup(function(){
          if($(this).val().trim() === PasswordNuevo.val().trim()){
            ButtonSavePassword.attr("disabled",false);
          }else{
            ButtonSavePassword.attr("disabled",true);
          }
            
        });

        PasswordNuevo.keyup(function(){
          if($(this).val().trim() === PasswordConfirm.val().trim()){
            ButtonSavePassword.attr("disabled",false);
          }else{
            ButtonSavePassword.attr("disabled",true);
          }
            
        });

        ButtonSavePassword.click(function(){
          let FormPasswordUpdate = new FormData();
          FormPasswordUpdate.append("token_","{{$this->Csrf()}}");
          FormPasswordUpdate.append("password_nuevo",PasswordNuevo.val()); 
        axios({
            method: 'POST',
            url: RUTA+'user/update/password',
            data:FormPasswordUpdate
        }).then(function(response){
             if(response.data.response === 'ok'){
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Tu nuevo password a sido modificado correctamente!",
                    icon:"success"
                }).then(function(){
                PasswordNuevo.val("");PasswordActual.val("");
                PasswordConfirm.val("");
                PasswordNuevo.attr("disabled",true);
                PasswordConfirm.attr("disabled",true); 
                ButtonSavePassword.attr("disabled",true); 
                });
             }else{
                if(response.data.response === 'error'){
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error al actualizar tu password!",
                        icon:"error"
                    });
                }else{
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Token Csrf incorrecto!",
                        icon:"error"
                    });
                }
             }
        });
        });
        });

        /**Funcion que valide la contraseña que escribe el usuario con la BD*/

        function ValidatePassword(passwordUser){
            let respuesta = '';
            $.ajax({
                url:RUTA+"verificar-password-actual/"+passwordUser,
                method:"GET",
                dataType:"json",
                async:false,
                success:function(response){
                    respuesta = response.response;
                }
            });

            return respuesta;
        }
    </script>
@endsection