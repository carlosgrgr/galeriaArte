<?php

class Mail {
    static function sendMail($destino){
        $origen = "carlosgrgrweb@gmail.com";
        $asunto = "Validación";
        $sha1 = sha1($destino . Constants::SEMILLA);
        $mensaje = "Hola. Se ha dado de alta en la base de datos. 
        Ya solo queda activar la cuenta pulsando sobre el siguiente enlace:
        https://usuarios-carlosgrgr.c9users.io/phpactivar.php?email=$destino&sha1=$sha1";
        $cliente = new Google_Client();
        $cliente->setApplicationName('ProyectoEnviarCorreoDesdeGmail');
        $cliente->setClientId('144315405047-hu44apt2g5q2akupkjalbk66ctmm0irb.apps.googleusercontent.com');
        $cliente->setClientSecret('A40mAiwufd0-UvupZDkCMJCE');
        $cliente->setRedirectUri('https://gestionusuario-carlosgrgr.c9users.io/oauth/guardar.php');
        $cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
        $cliente->setAccessToken(file_get_contents('./oauth/token.conf'));
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
                $r = $service->users_messages->send('me', $mensaje);
            } catch (Exception $e) {
                print("Error en el envío de correo" . $e->getMessage());
            }
        }else{
            echo "no conectado con gmail";
        }
        return $r = $r["labelIds"][0];
    }
}
