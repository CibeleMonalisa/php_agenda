<?php
    function formataData($data) {
        if (strstr($data, '/')) {
                $dataSeparada = explode('/', $data);
                return $dataSeparada[2] . '-' . $dataSeparada[1] . '-' . $dataSeparada[0];
        }   else if (strstr($data, '-')) {
                $dataSeparada = explode('-', $data);
                return $dataSeparada[2] . '/' . $dataSeparada[1] . '/' . $dataSeparada[0];
        }
    }

    //echo formataData("01/08/2021");
?>