<?php
require '../clases/AutoCarga.php';
$sesion = new Session();

//Gestionando errores
$error = "";
$aviso = "";
switch(Request::get("error")){
    case "exist":
        $error = "Ese email no corresponde a ningún usuario";
        break;
    case "clave":
        $error = "La contraseña no es correcta";
        break;
    case "activo":
        $error = "La cuenta no está activa. Revise su correo";
        break;
    case "noactivada":
        $error = "Algo no ha salido bien";
        break;
}
switch(Request::get("aviso")){
    case "activada":
        $aviso = "La cuenta se ha activado";
        break;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        
        <meta name="google-signin-scope" content="profile email">
        <meta name="google-signin-client_id" content="144315405047-hu44apt2g5q2akupkjalbk66ctmm0irb.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        
        <script type="text/javascript" src="../js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="../js/javascript.js"></script>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
        <title>Iniciar Sesión</title>
    </head>
    <body class="log">


    <?php
    if(!$sesion->isLogged()) {
    ?>
        <div class="login-box">
            <div class="lb-header">
                <a href="#" class="active" id="login-box-link">Login</a>
                <a href="altaArtista.php" id="signup-box-link">Sign Up</a>
            </div>
            <div class="social-login">
                <a href="#">
                    <i class="fa fa-facebook fa-lg"></i>
                    Login in with facebook
                </a>
                
                <a href="#">
                    <i class="fa fa-google-plus fa-lg" ></i>
                    log in with Google
                </a>
                
            </div>
        
            <form class="email-login" action="phplogin.php" method="post">
                <div class="u-form-group">
                    <input type="email" placeholder="Email" name="email"/>
                </div>
                <div class="u-form-group">
                    <input type="password" placeholder="Password" name="clave"/>
                </div>
                <div class="u-form-group">
                    <input type="submit" value="Log In"/>
                </div>
                <div class="u-form-group">
                    <a href="remember.php" class="forgot-password">Forgot password?</a>
                </div>
                <div class="u-form-group">
                    <p class="error"><?= $error ?></p>
                    <p class="aviso"><?= $aviso ?></p>
                </div>
                
            </form>
        </div>

    <?php
    } else {
        header("Location:phplogin.php");
        exit();
    }
    ?>
    </body>
</html>
