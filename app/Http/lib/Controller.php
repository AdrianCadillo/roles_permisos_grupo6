<?php 
namespace app\Http\lib;

use app\models\Role_Permiso;
use app\models\Usuario_Role;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Controller 
{ 
    use Request,Csrf,Fecha,MiddlewareAuth;
    /**
     * 
     */
    public function saludar()
    {
        return "HOLA MUNDO!!";
    }


    /**
     * Me traiga los roles de un usuario
     */
    public function UserRoles($id){
        $usuariorole = new Usuario_Role;

        $roles = $usuariorole->query()
                             ->Join("usuarios as u","ur.id_usuario","=","u.id_usuario")
                             ->Join("roles as r","ur.id_rol","=","r.id_rol")
                             ->where("ur.id_usuario","=",$id)
                             ->select("r.id_rol","r.nombre_rol")
                             ->get();

        return $roles;
                                   
    }

    /**
     * Método para obtener los permisos de un determinado rol
     */
    public function can(string $permiso):bool{

        $roleData = null;
        if ($this->existSession($this->SessionUser)) {
            $roleData = $this->getSession($this->SessionProfile);
        } else {
            $roleData = openssl_decrypt($_COOKIE[$this->SessionProfile], "aes-128-cbc", "curso");
        }
      $modelrolepermiso = new Role_Permiso;

      return count($modelrolepermiso->query()->Join("roles as r","rp.id_rol","=","r.id_rol")
                 ->Join("permisos as p","rp.id_permiso","=","p.id_permiso")
                 ->select("rp.id_permiso","p.alias_permiso")
                 ->where("r.id_rol","=",$roleData)
                 ->And("p.alias_permiso","=",$permiso)
                 ->get()) > 0 ? true :false;
    }

    /**
     * Para envios de correos electrónicos
     */
    public function sendEmail(array $dataReceptor,string $Asunto,string $Content){
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer();

        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();  
            $mail->CharSet = "utf8";                                          //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USERNAME;                     //SMTP username
            $mail->Password   = MAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = MAIL_SMTP_SECURE;            //Enable implicit TLS encryption
            $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(MAIL_SEND_EMAIL,MAIL_SEND_NAME);
            $mail->addAddress($dataReceptor[0]->email,$dataReceptor[0]->name);     //Add a recipient
           

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $Asunto;
            $mail->Body    = $Content;
           

            return $mail->send();
             
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}