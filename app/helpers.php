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

if (!function_exists('CreateResponse')) {
    function CreateResponse(array $header,array $body,int $code)
    {
        switch ($code) {
            case 200:
                return response()->json([
                    'status_code'=>$code,
                    'status_text'=>array_key_exists('status_text',$body)?$body['status_text']:"",
                    "message"=>array_key_exists('message',$body)?$body['message']:"",
                    "serverData"=>array_key_exists('serverData',$body)?$body['serverData']:null,
                ],$code,$header);
                break;
            case 302:
                return response()->json([
                    'status_code'=>$code,
                    'status_text'=>array_key_exists('status_text',$body)?$body['status_text']:"",
                    "message"=>array_key_exists('message',$body)?$body['message']:"",
                    "serverData"=>array_key_exists('serverData',$body)?$body['serverData']:null,
                ],$code,$header);
                break;
            case 500:
                return response()->json([
                    'status_code'=>$code,
                    'status_text'=>array_key_exists('status_text',$body)?$body['status_text']:"",
                    "message"=>array_key_exists('message',$body)?$body['message']:"Something Went Wrong",
                    "serverData"=>null,
                ],$code,$header);
                break;
            default:
                return response()->json([
                    'status_code'=>500,
                    'status_text'=>"INVALID STATUS CODE",
                    "message"=>array_key_exists('message',$body)?$body['message']:"Something Went Wrong",
                    "serverData"=>null,
                ],500,$header);
                break;
        }
    }
}

?>