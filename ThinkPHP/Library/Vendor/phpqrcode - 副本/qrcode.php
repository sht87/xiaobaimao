<?php

Class Qcode{

    public function code($url){

        //error_reporting(E_ERROR);
        require_once 'phpqrcode.php';

        $QRcode = new \QRcode();

        $url = $QRcode->png($url);
        return $url;
    }


}

