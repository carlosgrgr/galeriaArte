<?php

class Util {
    static function getSelect($name, $parametros, $valorSeleccionado=null, $blanco=true, $atributos="", $id=null) {
//        <select name='x' id='y' z >
//            [<option value='a'>&nbsp;</option>]
//            <option selected value='a'>b</option>
//            <option value='c'>d</option>
//        </select>
       
        if($id !== null){
            $id = "id='$id'";
        }else{
            $id = "";
        }
        $r = "<select name='$name' $id $atributos>\n";
        
        //linea en blanco
        if($blanco === true){
            $r .= "<option value=''>&nbsp;</option>\n";
        }
        
        foreach ($parametros as $indice => $valor) {
            $seleted = "";
            if($valorSeleccionado !== null && $indice === $valorSeleccionado){
                $seleted = "selected";
            }
            $r .= "<option $seleted value='$indice'>$valor</option>\n";
        }
        $r .= "</select>\n";
        return $r;
    }
}
