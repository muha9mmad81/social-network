<?php

function generateUniqueCode($code, $model){
    $codeExist = $model::where('token', $code)->first();
    if($codeExist){
        $newCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);;
        generateUniqueCode($newCode, $model);
    }else{
        return $code;
    }
}