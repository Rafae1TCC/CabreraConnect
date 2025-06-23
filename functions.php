<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function check_login($con) {

    if(isset($_SESSION['user_id'])) {


        $id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE user_id = '$id' LIMIT 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    //redirect to login
    header("Location: log-reg.php");
    die;

}

function check_perm($con) {

    if(isset($_SESSION['user_id'])) {

        $id = $_SESSION['user_id'];
        $query = "SELECT user_perm,isverified FROM users WHERE user_id = '$id' LIMIT 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    
}

function random_num($length){

    $text = "";
    if($length<5);
    {
        $length=5;
    }
    $len = rand(4, $length);

    for($i = 0; $i < $len; $i++){

        $text .= rand(0,9);
    }
    return $text;
}

define('SECRET_KEY', 'CcTx0Xg1ZIBOoZPpsUN6S2vOWE4Ztt0H');
define('SECRET_IV', 'MVe7Lx8sxcFijQKv');

function encryptData($data) {
    $encryption_key = SECRET_KEY;
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_encrypt($data, 'AES-256-CBC', $encryption_key, 0, $iv);
}
function decryptData($data) {
    $encryption_key = SECRET_KEY;
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_decrypt($data, 'AES-256-CBC', $encryption_key, 0, $iv);
}

function sendemail_verify($email,$name,$verify_token) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();                                            //Send using SMTP
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

    $mail->Host       = 'mail.bajasolution.com.mx';                     //Set the SMTP server to send through
    //$mail->Username   = 'bajasolutioninfo@bajasolution.com.mx';                     //SMTP username
    //$mail->Password   = 'uZtE1gUshz92';                               //SMTP password

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    //$mail->setFrom('bajasolutioninfo@bajasolution.com.mx', 'Baja Solutions');
    //$mail->addAddress($email,$name);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $email_template = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Verificación de cuenta - Baja Solutions</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
            .card { width: 100%; max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border: 1px solid #ccc; }
            .header { background-color: #000000; padding: 10px; text-align: center; color: white; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { padding: 20px; }
            .content h2 { color: #000000; }
            .content p { margin: 0 0 10px; }
            .button { 
                background-color: #000000;
                color: white; 
                padding: 10px 20px; 
                border-radius: 5px; 
                display: inline-block; 
                margin-top: 20px; 
                text-decoration: none; 
                text-align: center; 
                margin-bottom: 20px;
            }
            .footer { 
                text-align: center; 
                font-size: 12px; 
                color: #777; 
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='card'>
            <div class='header'>
                <h1>Baja Solutions</h1>
            </div>
            <div class='content'>
                <h2>¡Te has registrado en Baja Solutions!</h2>
                <p>Hola $name,</p>
                <p>Gracias por registrarte en nuestra plataforma. Para completar tu registro, por favor haz clic en el enlace a continuación para verificar tu cuenta.</p>
                <div style='text-align: center;'>
                    <a href='http://localhost/projects/BajaSolutionsPaginaWebXAMPP/verify-email.php?token=$verify_token' class='button'>Verificar cuenta</a>
                </div>
                <p>Si no solicitaste esta acción, simplemente ignora este correo.</p>
            </div>
            <div class='footer'>
                <p>&copy; 2024 Baja Solutions. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->Subject = 'Verificacion de correo electronico Baja Solution';
    $mail->Body    = $email_template;

    $mail->send();
    $_SESSION["status"] = "Correo de verificación enviado";
}