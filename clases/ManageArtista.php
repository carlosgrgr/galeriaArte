<?php

class ManageArtista {
    
    private $bd = null;
    private $tabla = "artista";
    
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }
    
    function get($email) {
        //devuelve el objeto de la fila cuyo email coincide con el email que le estoy pasando;
        //devuelve el objeto entero;
        $parametros = array();
        $parametros["email"] = $email;
        $this->bd->select($this->tabla, "*", "email =:email", $parametros);
        $fila = $this->bd->getRow();
        $artista = new Artista();
        $artista->set($fila);
        return $artista;
    }
    
    function count($condicion="1=1", $parametros=array()){
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }
            
    function delete($email) {
        //borrar por id
        $parametros = array();
        $parametros["email"] = $email;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
//    function forzarDelete($email) {
//        $parametros = array();
//        $parametros['CountryCode'] = $Code;
//        $gestor = new ManageCity($this->bd);
//        $gestor->deleteCities($parametros);
//        $this->bd->delete("countrylanguage", $parametros);
//        $parametros = array();
//        $parametros["Code"] = $Code;
//        return $this->delete($this->tabla, $parametros);
//    }
    
    function deleteArtistas($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    function erase(Artista $artista) {
        //borrar por nombre
        //dice ele numero de filas borratas
        return $this->delete($artista->getEmail());
    }
    
    function set(Artista $artista, $pkEmail) {
        //update de todos los campos 
        //pasamos el codigo que tenia y como en este si se puede cambiar el codigo, cambiamos todos los campos
        //dice el numero de filas modificades
        $parametros = $artista->getArray();
        $parametrosWhere = array();
        $parametrosWhere["email"] = $pkEmail;
        $this->bd->update($this->tabla, $parametros, $parametrosWhere);
    }
    
    function insert(Artista $artista) {
        //se le pasa un objeto City y lo inserta en la tabla
        //dice el numero de filas insertadas;
        $parametrosSet = array();
        $parametrosSet["email"]=$artista->getEmail();
        $parametrosSet["clave"]=$artista->getClave();
        $parametrosSet["alias"]=$artista->getAlias();
        $parametrosSet["fechaalta"]=$artista->getFechaalta();
        $parametrosSet["activo"]=$artista->getActivo();
        $parametrosSet["administrador"]=$artista->getAdministrador();
        $parametrosSet["plantilla"]=$artista->getPlantilla();
        return $this->bd->insert($this->tabla, $parametrosSet);
    }
    
    function getList($pagina=1, $orden="", $nrpp=Constants::NRPP, $condicion ="1=1", $parametros=array()) {
        $ordenPredeterminado = "$orden, fechaalta, email";
        if(trim($orden)==="" || trim($orden)===null){
            $ordenPredeterminado = "fechaalta, email";
        }
        $registroInicial = ($pagina - 1) * $nrpp;
        $this->bd->select($this->tabla, "*", $condicion, $parametros, $ordenPredeterminado,
                "$registroInicial, $nrpp");
        $r = array();
        while ($fila = $this->bd->getRow()){
            $artista = new Artista();
            $artista->set($fila);
            $r[] = $artista;
        }
        return $r;
    }
    
    function getValuesSelect() {
        $this->bd->query($this->tabla, "email, alias", array(), "alias");
        $array = array();
        while ($fila = $this->bd->getRow()){
            $array[$fila[0]] = $fila[1];
        }
        return $array;
    }
    
}
