<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION']; 

        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirma tu cuenta en UpTask';

        $contenido = "
            <html>
                <p><strong>Hola {$this->nombre}</strong>,</p>
                <p>Has creado tu cuenta en UpTask. Para confirmarla, haz clic en el siguiente enlace:</p>
                <p><a href='http://localhost:3000/confirmar?token={$this->token}'>Confirmar Cuenta</a></p>
                <br>
                <p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>
            </html>
        ";

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Body = $contenido;

        $mail->send();
    }
}
