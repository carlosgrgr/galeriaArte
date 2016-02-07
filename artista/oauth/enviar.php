<?php
session_start();
require '../../clases/AutoCarga.php';
require_once '../../clases/Google/autoload.php';
require_once '../../clases/class.phpmailer.php';  //las últimas versiones también vienen con autoload
$origen = "carlosgrgr@gmail.com";
$alias = "pepe perez";
$destino = Request::get("correo");
$asunto = "prueba envio correo";
$mensaje = "¿¿¿hola???";

$cliente = new Google_Client();

$cliente->setApplicationName('ProyectoEnviarCorreoDesdeGmail');
$cliente->setClientId('144315405047-hu44apt2g5q2akupkjalbk66ctmm0irb.apps.googleusercontent.com');
$cliente->setClientSecret('A40mAiwufd0-UvupZDkCMJCE');
$cliente->setRedirectUri('https://galeriaarte-carlosgrgr.c9users.io/artista/oauth/guardar.php');
$cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
$cliente->setAccessToken(file_get_contents('token.conf'));
if ($cliente->getAccessToken()) {
    $service = new Google_Service_Gmail($cliente);
    try {
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->From = $origen; 							//El correo que manda
        $mail->FromName = $alias; 						//El nombre con el que llega
        $mail->AddAddress($destino);						//A donde se manda
        $mail->AddReplyTo($origen, $alias);
        $mail->Subject = $asunto;						//Asunto
        $mail->Body = $mensaje;							//Mensaje
        $mail->preSend();
        $mime = $mail->getSentMIMEMessage();
        $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
        $mensaje = new Google_Service_Gmail_Message();
        $mensaje->setRaw($mime);
        $service->users_messages->send('me', $mensaje);
        echo "se ha enviado";
    } catch (Exception $e) {
        print("Error en el envío de correo" . $e->getMessage());
    }
}else{
    echo "no conectado con gmail";
}