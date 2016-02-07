<?php
require '../clases/AutoCarga.php';
$sesion = new Session();

//Gestionando errores
$error = "";
$aviso = "";
switch(Request::get("error")){
    case "exist":
        $error = "Ya existe una cuenta con ese correo";
        break;
    case "claves":
        $error = "Las contraseñas no son iguales";
        break;
    case "noenviado":
        $error = "Se ha producido algún error";
        break;
}
switch(Request::get("aviso")){
    case "activada":
        $aviso = "La cuenta se ha activado";
        if(session_status() == 2) {
            $sesion->destroy();
        }
        break;
    case "enviado":
        $aviso = "Revise su correo para activar su cuenta";
        break;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <!--
        <meta name="google-signin-scope" content="profile email">
        <meta name="google-signin-client_id" content="144315405047-hu44apt2g5q2akupkjalbk66ctmm0irb.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        -->
        <link rel="stylesheet" href="../css/style.css" type="text/css" />
        <script type="text/javascript" src="../js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="../js/javascript.js"></script>
        <title>Nuevo artista</title>
    </head>
    <body class="log">
        <div class="login-box">
            <div class="lb-header">
                <a href="login.php" id="login-box-link">Login</a>
                <a href="#" class="active" id="signup-box-link">Sign Up</a>
            </div>
        
            <form class="email-login" action="phpaltaArtista.php" method="post">
                <div class="u-form-group">
                    <input type="email" placeholder="Email" name="email"/>
                </div>
                <div class="u-form-group">
                    <input type="password" placeholder="Password" name="clave"/>
                </div>
                <div class="u-form-group">
                    <input type="password" placeholder="Confirm Password" name="clave2"/>
                </div>
                <div class="u-form-group">
                    <input type="submit" value="Sign Up"/>
                </div>
                <div class="u-form-group">
                    <p class="error"><?= $error ?></p>
                    <p class="aviso"><?= $aviso ?></p>  
                </div>
            </form>
        </div>
      
        <!--
        <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
        <div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="true"></div>
        <a href="#" onclick="signOut();">Sign out Google</a>
        -->
    </body>
</html>