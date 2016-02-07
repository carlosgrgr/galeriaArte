<?php
require './clases/AutoCarga.php';
$bd = new DataBase();
$gestor = new ManageArtista($bd);
$email = Request::get("email");
$sha1 = Request::get("sha1");

if(sha1($email . Constants::SEMILLA) === $sha1) {
    echo sha1($email . Constants::SEMILLA);
    $artista = new Usuario();
    $artista = $gestor->get($email);
    $artista -> setActivo(1);
    $r = $gestor -> set($artista, $email);
    echo $r;
    header("index.php");
}