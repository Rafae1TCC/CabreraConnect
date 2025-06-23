<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_FILES['pdf']) && (isset($_POST['email_client']) && isset($_POST['name_client'])) || (isset($_POST['email_seller']) && isset($_POST['name_seller']))) {
    $email_client = $_POST['email_client'];
    $name_client = $_POST['name_client'];
    $email_seller = $_POST['email_seller'];
    $name_seller = $_POST['name_seller'];

    // Guardar el PDF en el servidor temporalmente
    $filePath = 'temp/' . $_FILES['pdf']['name'];
    move_uploaded_file($_FILES['pdf']['tmp_name'], $filePath);

    // Enviar el correo con el PDF adjunto
    $mail = new PHPMailer(true);
    try {
        // Configuración de SMTP
        $mail->isSMTP();
        //$mail->Host = 'mail.bajasolution.com.mx';
        $mail->SMTPAuth = true;
        //$mail->Username = 'bajasolutioninfo@bajasolution.com.mx';
        ///$mail->Password = 'uZtE1gUshz92';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Destinatarios
        //$mail->setFrom('bajasolutioninfo@bajasolution.com.mx', 'Baja Solutions');
        //$mail->addAddress('direccion@bajasolution.com.mx', 'Baja Solutions');
        $mail->addAddress($email_seller, $name_seller);
        $mail->addAddress($email_client, $name_client);

        // Contenido del correo
        $mail->isHTML(true);
        $email_template = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Nota de venta - Baja Solutions</title>
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
                    <h2>Estimado usuario,</h2>
                    <p>Adjunto en este correo encontrarás la nota de venta generada recientemente.</p>
                    <p>¡Gracias por confiar en nosotros!</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Baja Solutions. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Asunto y cuerpo del correo
        $mail->Subject = 'Nota de venta - Baja Solutions';
        $mail->Body = $email_template;

        // Adjuntar el PDF
        $mail->addAttachment($filePath);

        // Enviar correo
        $mail->send();
        echo 'Correo enviado con éxito';

        // Eliminar el archivo temporal
        unlink($filePath);
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
} else {
    echo 'No se recibió el PDF o la información del cliente.';
}
?>
