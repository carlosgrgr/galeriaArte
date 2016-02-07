<?php

class Pager {

    private $registros, $rpp, $paginaActual;
	//$registros es el número de registros totales
        //$rpp es el número de registros por párrafos
        //$paginaActual es la página en la que estamos

    function __construct($registros, $rpp = Constantes::NRPP, $paginaActual = 1) {
        //nos aseguramos 100% que los parámetros no lleguen con null
        if($rpp === null){
            $rpp = Constants::NRPP;
        }
        if($paginaActual === null){
            $paginaActual = 1;
        }
        $this->registros = $registros;
	$this->rpp = $rpp;
	$this->paginaActual = $paginaActual;
    }

//getters y setters
    public function getRegistros() {
        return $this->registros;
    }

    public function getRpp() {
        return $this->rpp;
    }

    public function getPaginaActual() {
        return $this->paginaActual;
    }

    
    function getPrimera(){
	//devuelve 1;
        return 1;
    }

    function getAnterior(){
	//devuelve la página anterior a la actual
        return max(1, $this->paginaActual-1);
    }

    function getSelect($id, $name = null){
	//Construir el select/option con 10/50/100 registro por página
	//que seleccion el valor que tengo habilitado
	//id es el id que le vamos a dar al <select> y name el name. si no hay name será el mismo que el id
        if($name===null){
            $name = $id;
        }
        $array = array(10=>"10 rpp", 50=>"50 rpp", 50=>"100 rpp");
        return Util::getSelect($name, $array, $this->rpp, false, "", $id);
    }

    function getSiguiente(){
	//devuelve la página siguiente a la actual
        return min($this->getPaginas(), $this->paginaActual+1);
    }

    function getPaginas(){
        return ceil($this->registros/$this->rpp);
    }

    public function setRegistros($registros) {
        $this->registros = $registros;
    }

    public function setRpp($rpp) {
        $this->rpp = $rpp;
    }

    public function setPaginaActual($paginaActual) {
        $this->paginaActual = $paginaActual;
    }

    function getEnlacesPaginas($rango, $enlace, $nombreParametroPagina, $pagina = 0){
	
    }

}