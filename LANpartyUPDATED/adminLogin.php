<?php

include("./php/includes/security.php");
include("./php/includes/database.php");

session_start();

if(!empty($_POST)){
    $connection = ReturnData("SELECT * FROM `admin`;");
    if(CheckData($_POST['adminName'],$_POST['adminpass'],$connection)){
        header("Location: ./php/mainAdminPage.php");
        $_SESSION["logedin"] = "true";
    }else{
        echo("wachtwoord of naam klopt niet");
    }
}

echo('
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./css/admin-additional.css" />
</head>
<body>
    <h1>Login admin</h1>
    <form method="post" class="login">
        <label for="adminName">Naam Admin</label>
        <input type="text" name="adminName" id="adminName">
        <div class="spacer"></div>
        <label for="adminName">password admin</label>
        <input type="password" name="adminpass" id="adminpass">
        <div class="spacer"></div>
        <input type="submit" name="submit" id="submit" value="login"></br>
    </form>
</body>
</html>
');
