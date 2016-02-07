<?php

class ControladorObra {
    static function handle() {
        //gestionar la petición
        $bd = new DataBase();
        $gestor = new ManageObra($bd);
        $gestorArtista = new ManageArtista($bd);
        
        $action = Request::req("action");
        $do = Request::req("do");
        $metodo = $action . ucfirst($do);
        if (method_exists(get_class(), $metodo)) {
            echo "El método existe";
            self::$metodo($gestor, $gestorArtista);
        } else {
            echo "El método no existe";
            self::readView($gestor, $gestorArtista);
        }
    }

    private static function readView($gestor, $gestorArtista) {
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
        $email = Request::get("email");
        $artista = $gestorArtista->get($email);
        $plantilla = $artista->getPlantilla();
        $plantillaArtista = file_get_contents("../plantillas/$plantilla");
//        $artistas = "";
//        foreach ($listaArtistas as $key => $value) {
//            $artistai = str_replace('{contenido}', $value->getName(), $plantillaArtista);
//            $artistai = str_replace('{texto}', $value->getCountryCode(), $ciudadi);
//            $artistai = str_replace('{ID}', $value->getID(), $ciudadi);
//            $artistas .= $artistai;
//        }
        
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
    
    private static function insertView($gestor) {
        
    }
    
    private static function insertSet($gestor) {
        
    }
    
    private static function deleteSet($gestor) {
        echo "borrar";
        $r = $gestor->delete(Request::get("ID"));
        //ControladorCity::readView($gestor);
        header("Location:?r=$r&op=delete");
    }

    private static function editView($gestor) {
        
    }
    
    private static function editSet($gestor) {
        
    }
}
