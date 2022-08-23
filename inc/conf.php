<?php
if (file_exists($conf_path)){
    $string = file_get_contents($conf_path);
    $json = json_decode($string, true);
    foreach ($json as $key => $value){
        define($key, $value);   //mysql host
    }
}   else    {
    die('Error to initialize!');
}