<?php

Class Qcode{

    public function code($url,$file){

        //error_reporting(E_ERROR);
        require_once 'phpqrcode.php';

        $QRcode = new \QRcode();

        $url = $QRcode->png($url,$file);
        return $url;
    }


}

