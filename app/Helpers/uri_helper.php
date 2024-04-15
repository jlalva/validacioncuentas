<?php
    function url(){
        $url = $_SERVER["REQUEST_URI"];
        $url = explode("/",$url);
        $url = $url[2];
        return $url;
    }