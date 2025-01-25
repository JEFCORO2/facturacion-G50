<?php

function CantidadEnLetra($tyCantidad){
    $enLetras = new EnLetras;
    return $enLetras->ValorEnLetras($tyCantidad, "SOLES");
}

class EnLetras{
    var $Void = "";
    var $SP = " ";
    var $Dot = ".";
    var $Zero = "0";
    var $Neg = "Menos";

    function ValorEnLetras($x, $Moneda){
        $s = "";
        $Ent = "";
        $Frc = "";
        $Signo = "";

        if (floatval($x) < 0) {
            $Signo = $this->Neg . " ";
        } else {
            $Signo = "";
        }

        if (intval(number_format($x,2,'.','')) != $x) {
            $s = number_format($x,2,'.','');
        } else {
            $s = number_format($x,2,'.',''); 
        }

        $Pto = strpos($s, $this->Dot);

        if ($Pto === false) {
            $Ent = $s;
            $Frc = $this->Void;
        }else {
            $Ent = substr($s , 0 , $Pto);
            $Frc = substr($s, $Pto+1);
        }

        if ($Ent == $this->Zero || $Ent == $this->Void) {
            $s = "CERO";
        }elseif (strlen($Ent) > 7) {
            $s = $this->SubValLetra(intval(substr($Ent, 0 , strlen($Ent) - 7))) . "MILLONES" . $this->SubValLetra(intval(substr($Ent, -6, 6)));
        }else {
            $s = $this->SubValLetra(intval($Ent));
        }
    }

    function SubValLetra($numero){
        
    }

}