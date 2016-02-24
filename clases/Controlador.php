<?php

class Controlador {
    static function handle() {
        //gestionar la petición
        $bd = new DataBase();
        $gestor = new ManageArtista($bd);
        $gestorArtista = new ManageArtista($bd);
        
        $action = Request::req("action");
        $do = Request::req("do");
        $metodo = $action . ucfirst($do);
        if (method_exists(get_class(), $metodo)) {
            self::$metodo($gestor, $gestorArtista);
        } else {
            self::readView($gestor);
        }
    }

    private static function readView($gestor) {
//        $filtro = Request::get("filtro");
//        if ($filtro === null) {
//            $params = array();
//            $condicion = "1=1";
//        } else {
//            $params["filtro"] = $filtro . "%";
//            $condicion = "Name like :filtro";
//        }
//
//        $order = Request::get("order");
//        $orderby = "Name, CountryCode, ID";
//        if ($order !== null) {
//            $orderby = "$order, $orderby";
//        }
//
//        $registros = $gestor->count($condicion, $params);
//        $paginacion = new Pager($registros, Request::get("rpp"), Request::get("pagina"));
//        $parametros = new QueryString();
//
//        $op = null;
//        
//        $listaArtistas = $gestor->getList($paginacion->getPaginaActual(), $orderby, $paginacion->getRpp(), $condicion, $params);
        $plantillaArtista = file_get_contents('plantillas/_main.html');

        $artistabox = file_get_contents('plantillas/_artistabox.html');
        $gestorArtista = new ManageArtista(new DataBase());
        $listaArtistas = $gestorArtista->getList();
        $listaCuadros = "";
        
        foreach ($listaArtistas as $key => $value) {
            $datosArtista = str_replace('{alias}', $value->getAlias(), $artistabox);
            $datosArtista = str_replace('{email}', $value->getEmail(), $datosArtista);
            $listaCuadros .= $datosArtista;
        }
        $plantillaArtista = str_replace("{artistabox}", $listaCuadros, $plantillaArtista);
        echo $plantillaArtista;
    }

    private static function deleteSet($gestor) {
        
    }
    
    private static function seeGalery($gestor, $gestorArtista) {
        $artista = $gestorArtista->get($email);
        $plantilla = $artista->getPlantilla();
        $plantillaArtista = file_get_contents("../plantillas/$plantilla");
        
        $cuadrobox = file_get_contents('../plantillas/_imagenobra.html');
        $gestorObra = new ManageObra(new DataBase());
        $listaObras = $gestorObra->getList(1, "", Constants::NRPP, "artista=:artista", array("artista"=>$artista->getEmail()));
        $listaCuadros = "";
        
        foreach ($listaObras as $key => $value) {
            $datosCuadro = str_replace('{url}', "../images/".$artista->getEmail()."/".$value->getImagen(), $cuadrobox);
            $listaCuadros .= $datosCuadro;
        }
        $plantillaArtista = str_replace("{imagenobra}", $listaCuadros, $plantillaArtista);
        echo $plantillaArtista;
    }
}
