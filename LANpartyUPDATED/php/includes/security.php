<?php

function CheckData($Username, $password, $arrayDatabase){
    foreach($arrayDatabase as $db){
        if($db["nameAdmin"] == $Username){
            if(CheckPasswordHash($db["Password"], $password)){
                return true;
            }
        }
    }
    return false;
}

function HashPassword($_password){
    $HashedPassword = password_hash($_password, PASSWORD_BCRYPT);
    return $HashedPassword;
}

function CheckPasswordHash($_passwordCheck, $_password){
    if(password_verify($_password, $_passwordCheck)){
        return true;
    }
    return false;
}