<?php
require_once 'PHPMailer/PHPMailerAutoload.php';
class SMTPMail{
    private $destinatario;
    private $subject;
    private $message;

    public function enviar(){
        $mail = new PHPMailer();
        $mail->IsSMTP();

        //Configuracion servidor mail
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $mail->SMTPAutoTLS = false;
        $mail->Host = "smtp-relay.brevo.com";
        $mail->Port = 587;
        $mail->Username ='jinoalva@gmail.com';//Usuario con el que se creo la cuenta en https://es.sendinblue.com/
        $mail->Password = 'UbrdTjDMQ06mBGOa';//Clave SMTP se crea en https://app.sendinblue.com/settings/keys/smtp

        $mail->setFrom('adminuser@unitru.edu.pe', 'Contacto web');//Correo que aparecerá en los correos que se envía
        $mail->AddAddress($this->destinatario);
        $mail->Subject = $this->subject;
        $mail->isHTML(true);
        $mail->Body = $this->message;

        if ($mail->Send()) {
            error_log('SMTPMAIL::Envio de correo->success');
            return true;
        } else {
            error_log('SMTPMAIL::Envio de correo->error: '.$mail->ErrorInfo);
            return false;
        }
    }

    public function getDestinatario()
    {
        return $this->destinatario;
    }

    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }


}