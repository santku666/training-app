<?php

if (!function_exists('sanatize_name')) {
    function sanatize_name(string $input){
        $input=trim($input);
        $input=stripslashes($input);
        $input=htmlspecialchars($input);
        $input=preg_replace("/[^a-zA-Z0-9 ]/","",$input);
        return $input;
    }
}

?>