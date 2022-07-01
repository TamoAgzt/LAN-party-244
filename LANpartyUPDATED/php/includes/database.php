<?php

function ConnectToDatabase(){
    $dbhost = '127.0.0.1';
    $dbname='testen';
    $dbuser = 'root';
    $dbpass = '';
    $_PDO = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return($_PDO);
}

function ReturnData($_sql) {
    $DBconnect = ConnectToDatabase();
    $statement = $DBconnect->prepare($_sql);
    $aResult = $statement->execute(); 
    $arr_rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    $DBconnect=NULL;
    return($arr_rows);      
}

function NotEmpty($_){

}