<?php

class ControladorArtista {

    static function handle() {
        //gestionar la petición
        $bd = new DataBase();
        $sesion = new Session();
        if(!$sesion->isLogged()){
            header("Location:login.php");
            exit();
        }

        $gestor = new ManageArtista($bd);
        $action = Request::req("action");
        $do = Request::req("do");
        $metodo = $action . ucfirst($do);
        if (method_exists(get_class(), $metodo)) {
            echo "El método existe";
            self::$metodo($gestor, $sesion);
        } else {
            echo "El método no existe";
            self::readView($gestor, $sesion);
        }
    }

    private static function readView($gestor, $sesion) {
        $bd = new DataBase();
        $artista = $sesion->getUser();
        $gestorObra = new ManageObra($bd);
        $gestorArtista = new ManageArtista($bd);
        $op = Request::get("op");
        $r = Request::get("r");
        $mensaje = "";
        if($op){
            if($r != -1){
                $mensaje = "La $op se ha realizado con éxito";
            } else {
                $mensaje = "No se ha podido realizar la $op";
            }
        }

        $plantillaArtista = file_get_contents('../plantillas/_backprofile.html');

        
        //Plantilla para el formulario
        $formEditPlantilla = file_get_contents('../plantillas/_editForm.html');
        
        $listaArtistas = $gestorArtista->getList(1, "", Constants::NRPP, "artista=:artista", array("artista"=>$artista->getEmail()));
        
        $datosFormulario = str_replace('{pkEmail}', $artista->getEmail(), $formEditPlantilla);
        
        $plantillaArtista = str_replace("{mensaje}", $mensaje, $plantillaArtista);
        $plantillaArtista = str_replace("{contenido}", $datosFormulario, $plantillaArtista);
        $plantillaArtista = str_replace("{nombreArtista}", $artista->getAlias(), $plantillaArtista);
        $plantillaArtista = str_replace("{plantillaActual}", $artista->getPlantilla(), $plantillaArtista);
        
        
        
        
        //Plantilla para los cuadros del artista
        $cuadrobox = file_get_contents('../plantillas/_cuadrobox.html');
        
        $listaObras = $gestorObra->getList(1, "", Constants::NRPP, "artista=:artista", array("artista"=>$artista->getEmail()));
        $listaCuadros = "";
        
        foreach ($listaObras as $key => $value) {
            $datosCuadro = str_replace('{url}', "../images/".$artista->getEmail()."/".$value->getImagen(), $cuadrobox);
            $datosCuadro = str_replace('{nombre}', $value->getName(), $datosCuadro);
            $datosCuadro = str_replace('{artista}', $value->getArtista(), $datosCuadro);
            $datosCuadro = str_replace('{id}', $value->getId(), $datosCuadro);
            $listaCuadros .= $datosCuadro;
        }
        $plantillaArtista = str_replace("{cuadrobox}", $listaCuadros, $plantillaArtista);
        echo $plantillaArtista;
    }

    private static function insertView($gestor, $sesion) {
        $bd = new DataBase();
        $artista = $sesion->getUser();
        $gestorObra = new ManageObra($bd);
        $gestorArtista = new ManageArtista($bd);
        $op = Request::get("op");
        $r = Request::get("r");
        $mensaje = "";
        if($op){
            if($r != ""){
                $mensaje = "La $op se ha realizado con éxito";
            } else {
                $mensaje = "No se ha podido realizar la $op";
            }
        }
        
        $plantillaArtista = file_get_contents('../plantillas/_backprofile.html');

        
        //Plantilla para el formulario
        $formEditPlantilla = file_get_contents('../plantillas/_uploadFileForm.html');
        
        $listaArtistas = $gestorArtista->getList(1, "", Constants::NRPP, "artista=:artista", array("artista"=>$artista->getEmail()));
        $datosFormulario = str_replace('{pkEmail}', $artista->getEmail(), $formEditPlantilla);
        
        $plantillaArtista = str_replace("{mensaje}", $mensaje, $plantillaArtista);
        $plantillaArtista = str_replace("{contenido}", $datosFormulario, $plantillaArtista);
        $plantillaArtista = str_replace("{nombreArtista}", $artista->getAlias(), $plantillaArtista);
        
        
        //Plantilla para los cuadros del artista
        $cuadrobox = file_get_contents('../plantillas/_cuadrobox.html');
        
        $listaObras = $gestorObra->getList(1, "", Constants::NRPP, "artista=:artista", array("artista"=>$artista->getEmail()));
        $listaCuadros = "";
        
        foreach ($listaObras as $key => $value) {
            $datosCuadro = str_replace('{url}', "../images/".$artista->getEmail()."/".$value->getImagen(), $cuadrobox);
            $datosCuadro = str_replace('{nombre}', $value->getName(), $datosCuadro);
            $datosCuadro = str_replace('{artista}', $value->getArtista(), $datosCuadro);
            $listaCuadros .= $datosCuadro;
        }
        $plantillaArtista = str_replace("{cuadrobox}", $listaCuadros, $plantillaArtista);
        echo $plantillaArtista;
    }
    
    private static function insertSet($gestor, $sesion) {
        $bd = new DataBase();
        $gestorObra = new ManageObra($bd);
        $artista = $sesion->getUser();
        $subir = new FileUpload("archivo");
        $nombreFoto = Request::post("nombre");
        $extension = $subir->getExtension();
        $ruta = $nombreFoto . "." . $extension;
        $carpeta = "../images/". $artista->getEmail() . "/";          
        $subir->setDestino($carpeta);
        $subir->setNombre($nombreFoto);
        $obra = new Obra($nombreFoto, $artista->getEmail(), $ruta);
        $r = $gestorObra->insert($obra);
        if($r!==null){
            if($subir->upload()){
                $sesion->sendRedirect("?action=insert&do=view&op=subida&r=$r");
            } else {
                $sesion->sendRedirect("?action=insert&do=view&op=subida&r=-1");
            }
        }  else {
            $sesion->sendRedirect("?action=insert&do=view&op=subida&r=-1");
        }
    }
    
    private static function deleteSet($gestor, $sesion) {
        $bd = new DataBase();
        $gestorObra = new ManageObra($bd);
        $r = $gestorObra->delete(Request::get("id"));
        //ControladorCity::readView($gestor);
        $sesion->sendRedirect("?action=read&do=view&r=$r&op=borrado");
    }
    
    private static function editSet($gestor, $sesion) {
        $bd = new DataBase();
        $artista = $sesion->getUser();
        $pkemail = Request::post("pkEmail");
        $newAlias = Request::post("alias");
        $newClave = Request::post("clave");
        $newPlantilla = Request::post("plantilla");

        if($newAlias != "") {
            $artista->setAlias($newAlias);
        }
        if($newPlantilla != "") {
            $artista->setPlantilla($newPlantilla);
        }
        if($newClave != "") {
            $artista->setClave(sha1($newClave));
        }
        
        if(Request::post("email") !== ""){
            $sesion->sendRedirect("?action=change&do=email");
        }
        
        $r = $gestor->set($artista, $pkemail);
        
        $bd->close();
        $sesion->sendRedirect("?action=read&do=view&op=edicion&r=$r");
    }
    
    private static function deleteAccount($gestor, $sesion) {
        $bd = new DataBase();
        $artista = $sesion->getUser();
        $pkemail = $artista->getEmail();
        $artista->setActivo("0");
        $r = $gestor->set($artista, $pkemail);
        $bd->close();
        $sesion->destroy();
        header("Location:index.php?r=$r");
    }
    
}
