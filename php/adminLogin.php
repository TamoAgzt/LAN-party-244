<?php

include("./includes/security.php");
include("./includes/database.php");

if(!empty($_POST)){
    $connection = ReturnData("SELECT * FROM `admin`;");
    if(CheckData($_POST['adminName'],$_POST['adminpass'],$connection)){
        header("Location: ./mainAdminPage.php");
    }else{
        echo("wachtwoord of naam klopt niet");
    }
}

echo('
    <html>
        <head>
        </head>
        <body>
            <h1>Login admin</h1>
            <form method="post">
                <label for="adminName">Naam Admin</label>
                <input type="text" name="adminName" id="adminName"></br>
                <label for="adminName">password admin</label>
                <input type="password" name="adminpass" id="adminpass"></br>
                <input type="submit" name="submit" id="submit" value="login"></br>
            </form>
        </body>
    </html>
');
