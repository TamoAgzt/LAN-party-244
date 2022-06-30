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
        <link rel="stylesheet" href="styleAdmin.css">
        </head>
        <body>
            <div class="Login">
            <h1>Login admin</h1>
            <form method="post">
                <label for="adminName" class="label">Naam Admin</label>
                <input type="text" name="adminName" id="adminName" class="button"></br>
                <label for="adminName" class="label">password admin</label>
                <input type="password" name="adminpass" id="adminpass" class="button"></br>
                <input type="submit" name="submit" id="submit" value="login" class="button"></br>
            </form>
            </div>
        </body>
    </html>
');
