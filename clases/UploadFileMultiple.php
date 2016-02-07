<?php

class UploadFileMultiple {
    private $myArray = [ Array() ];
    const CONSERVAR = 1,REMPLAZAR = 2, RENOMBRAR = 3;
    private $politica = self::RENOMBRAR, $error = false, $long = 0;
    private $arrayDeTipos = Array(
        "JPG"=>1,
        "jpg"=>1,
        "png"=>1,
        "PNG"=>1
    );
    
    function __construct($parametro, $destino = "../../../ClientesSAS/") {
        if(isset($_FILES[$parametro])){
            $arrayTemp = $this->tranformar($_FILES[$parametro]);
            foreach ($arrayTemp as $indiceNumerico => $valor) {
                if($valor["name"] !== ""){
                    $this->long++;
                    $this->myArray[$indiceNumerico]["destino"] = $destino;
                    $this->myArray[$indiceNumerico]["ubicacionTemporal"] = $valor["tmp_name"];
                    $this->myArray[$indiceNumerico]["nombre"] = pathinfo($valor["name"])["filename"];
                    $this->myArray[$indiceNumerico]["extension"] = pathinfo($valor["name"])["extension"];
                    $this->myArray[$indiceNumerico]["tamaño"] = $valor["size"];
                    $this->myArray[$indiceNumerico]["tamañoMax"] = 1000000000;
                    $this->myArray[$indiceNumerico]["parametro"] = $parametro;
                    $this->myArray[$indiceNumerico]["errorArchivo"] = false;
                    $this->myArray[$indiceNumerico]["error"] = $valor["error"];
                    $this->myArray[$indiceNumerico]["subido"] = false;
                }else{
                    $this->myArray["errorArchivo"] = true;
                }
            }
        }else{
            echo $destino;
            $this->error = true;
        }
    }

    public function getArray() {
        return $this->myArray;
    }

    public function setArray($array) {
        $this->myArray = $array;
    }

    public function getDestino($indice) {
        return $this->myArray[$indice]["destino"];
    }

    public function getSize() {
        return $this->long;
    }

    public function getNumeroSubidos() {
        $subidos = 0;
        foreach ($this->myArray as $indice => $valor) {
            if($valor["subido"])
                $subidos++;
        }
        return $subidos;
    }

    public function setSize($long) {
        $this->long = $long;
    }

    public function getName($indice) {
        return $this->myArray[$indice]["nombre"];
    }

    public function getTamaño($indice) {
        return $this->myArray[$indice]["tamaño"];
    }

    public function getExtension($indice) {
        return $this->myArray[$indice]["extension"];
    }
    
    public function getPolitica() {
        return $this->politica;
    }

    public function setName($name,$indice) {
        $this->myArray[$indice]["nombre"] = $name;
    }

    public function setDestino($destino,$indice) {
        $this->myArray[$indice]["destino"] = $destino;
    }
//
//    public function setPolitica($politica) {
//        $this->politica = $politica;
//    }

    public function upload(){
        foreach ($this->myArray as $archivo => $valor) {
            if($valor["subido"])
                continue;
            if($valor["error"])
                continue;
            if($valor["errorArchivo"] != UPLOAD_ERR_OK)
                continue;
            if($valor["tamaño"] > $valor["tamañoMax"])
                continue;
            if(!$this->isTipo($valor["extension"]))
                continue;
            if(!(is_dir($valor["destino"]) && substr($valor["destino"], -1) === "/"))
                continue;
            echo $valor["destino"] . $valor["nombre"] . "." . $valor["extension"];
            if($this->politica === self::RENOMBRAR && file_exists($valor["destino"] . $valor["nombre"] . "." . $valor["extension"]))
                $valor["nombre"] = $this->remplazar($archivo,$valor["nombre"]);
            if(move_uploaded_file($valor["ubicacionTemporal"], $valor["destino"] . $valor["nombre"] . "." . $valor["extension"])){
                    $this->myArray[$archivo]["subido"] = true;
            }else{
                $this->myArray[$archivo]["subido"] = false;
            }
        }
    }
    
    private function remplazar($indice, $nombre){
        $i = 1;
        while(file_exists($this->myArray[$indice]["destino"] . $nombre . "_" . $i . "." . $this->myArray[$indice]["extension"])){
            $i++;
        }
        return $nombre."_".$i;
    }
    
    public function addTipo($tipo){
        if(!$this->isTipo($tipo)){
            $this->arrayDeTipos[$tipo]=1;
            return true;
        }
        return false;
    }
    
    public function removeTipo($tipo){
        if($this->isTipo($tipo)){
            unset($this->arrayDeTipos[$tipo]);
            return true;
        }
        return false;
    }
    
    public function isTipo($tipo){
        return isset($this->arrayDeTipos[$tipo]);
    }
    
    public function tranformar($array){
        $miArray = Array();
        foreach ($array as $datoFiles => $valorDatos) {
            foreach ($valorDatos as $indiceArchivo => $valorArchivo) {
                $miArray[$indiceArchivo][$datoFiles] = $valorArchivo;
            }
        }
        return $miArray;
    }
    
    public function existirDir($valor){
        if(!is_dir($valor))
            mkdir($valor);
    }
    
}
