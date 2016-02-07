<?php

class Obra {
    
    private $id, $nombre, $artista, $imagen;
    
    function __construct($nombre=null, $artista=null, $imagen=null, $id=null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->artista = $artista;
        $this->imagen = $imagen;
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->nombre;
    }

    function getArtista() {
        return $this->artista;
    }

    function getImagen() {
        return $this->imagen;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setArtista($artista) {
        $this->artista = $artista;
    }

    function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    public function getJson() {
        $r = '{';
        foreach ($this as $indice => $valor) {
            $r .= '"' . $indice . '"' . ':' . '"' . $valor . '"' . ',' ;
        }
        $r = substr($r, 0, -1);
        $r .= '}';
        return $r;
    }
    
    function set($valores, $inicio=0) {
        $i = 0;
        foreach ($this as $indice => $valor) {
            $this->$indice = $valores[$i+$inicio];
            $i++;
        }
    }
    
     public function __toString() {
        $r = '';
        foreach ($this as $key => $valor){
            $r .= "$valor ";
        }
        return $r;
    }
    
    public function getArray($valores=true) {
        $array = array();
        foreach ($this as $key => $valor) {
            if($valores===true){
                $array[$key] = $valor;
            }else{
                $array[$key] = null;
            }
        }
        return $array;
    }
    
    function read() {
        foreach ($this as $key => $valor){
            $this->$key = Request::req($key);
        }
    }

}
