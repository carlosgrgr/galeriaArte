<?php

class QueryString { 
//para componer los enlaces que tenemos que hacer en nuestra página;
//los enlaces se construirán de la siguiente manera: enlace?pagina=x&filtro=y&rpp=z&order=w;

    private $params;
//array asociativo en el que metemos todos los valores

    function __construct() {
	$this->params = $_GET;	//$_Get es todos los parametros que llegan por get
    }

    function get($nombre) {
	//pasamos el nombre del valor que te llega y devolver el valor
        //?pagina=x&filtro=y&rpp=z&order=w;
        return $this->params[$nombre];
    }



    function getParamsWithout($parametrosDelete){
	//devuelve el queryString sin los datos que haya en $parametrosDelete;
        return $this->getParams(array(), $parametrosDelete);
    }

    function getParams($parametrosAdd = array(), $parametrosDelete = array()) {
	//pasarle un array con los parametros que quieres que te añada al queryString y con otro 
        //array que te borre los parametros que quieras que devuelva el queryString resultante
        $copia = $this->params;
        foreach ($parametrosDelete as $$parametro => $valor) {
            unset($copia[$parametro]);
        }
        foreach ($parametrosAdd as $parametro => $valor){
            $copia[$parametro] = $valor;
        }
        $r = "";
        foreach ($copia as $parametro => $valor) {
            $r .= $parametro . "=" . urlencode($valor) . "&";
        }
        //borramos el ultimo andpersand y lo retornamos
        return substr($r, 0, -1);
        
    }

    function set($nombre, $valor) {
	//añadir parametro con su nombre y valor
        $this->params[$nombre] = $valor;
    }

    function delete($nombre) {
	//del querystring que tengo, borrar elemento con el nombre $nombre
        unset($this->params[$nombre]);
    }

    function __toString() {
	//devolverme la cadena así pagina=x&filtro=y&rpp=z&order=w
        $r = "?";
        foreach ($this->params as $nombre => $valor) {
            $r .= "$clave=$valor&";
        }
        return $r;
    }

}
