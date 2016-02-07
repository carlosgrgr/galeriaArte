<?php
require '../clases/AutoCarga.php';
$bd = new DataBase();
$sesion = new Session();
$gestor = new ManageArtista($bd);
$email = Request::post("email");
$clave = Request::post("clave");
$artista = $gestor->get($email);
$email = $artista->getEmail();


//si el usuario no existe
if($email === null){
    header("Location:login.php?error=exist");
}else if(sha1($clave) !== $artista->getClave()){
    header("Location:login.php?error=clave");
}else if($artista->getActivo() === "0"){
    header("Location:login.php?error=activo");
}else{
    $sesion->setUser($artista);
    $sesion->sendRedirect("index.php");
}