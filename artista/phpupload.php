<?php
require '../clases/AutoCarga.php';

$sesion = new Session();
$archivo = Request::post("archivo");
$nombre = Request::post("nombre");
var_dump($nombre);
var_dump($archivo); 
$artista = $sesion->getUser();
$carpeta = "../images/". $artista->getEmail();

$subir = new FileUpload("archivo");
if(!file_exists($carpeta)) {
    mkdir($carpeta, 0777);
}

$subir->upload();



//$sesion->sendRedirect("index.php");
