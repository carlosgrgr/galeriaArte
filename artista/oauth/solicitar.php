<?php
session_start();
require '../../clases/AutoCarga.php';
require_once '../../clases/Google/autoload.php';
$cliente = new Google_Client();
$cliente->setApplicationName('ProyectoEnviarCorreoDesdeGmail');
$cliente->setClientId('144315405047-hu44apt2g5q2akupkjalbk66ctmm0irb.apps.googleusercontent.com');
$cliente->setClientSecret('A40mAiwufd0-UvupZDkCMJCE');
$cliente->setRedirectUri('https://galeriaarte-carlosgrgr.c9users.io/artista/oauth/guardar.php');
$cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
$cliente->setAccessType('offline');
if (!$cliente->getAccessToken()) {
   $auth = $cliente->createAuthUrl(); //solicitud
   header("Location: $auth");
} 