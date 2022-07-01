<?php
session_start();
if(!$_SESSION["logedin"]){
    header("Location: ../../adminLogin.php");
}

include("../includes/security.php");
include("../includes/database.php");

if(!empty($_POST)){
    if($_POST['add'] == "add"){
        $connection = ReturnData("INSERT INTO `admin` (`admin_ID` ,`nameAdmin`, `Password`) VALUES (NULL,'".$_POST["adminName"]."', '".HashPassword($_POST["adminpass"])."');");
    }else if ($_POST['remove'] == "remove"){
        $connection = ReturnData("DELETE FROM `admin` WHERE `admin_ID` = ".$_POST["removeID"].";");
    }
}

echo('
<html>
<head>
    <title>AdminPage</title>
    <link rel="stylesheet" type="text/css" href="../../css/admin-additional.css" />
</head>
<body>
    <ul>
        <li><a href="../mainAdminPage.php">adminPage</a></li>
        <li><a href="./inschrijving.php">inschrijvingen</a></li>
        <li><a href="./sponseradder.php">sponseradder</a></li>
        <li><a href="#">admin accounts</a></li>
    </ul>
    <form method="post">
        <input type="hidden" name="add" value="add"/>
        <input type="hidden" name="remove" value="add"/>

        <label for="adminName">Naam Admin</label>
        <input type="text" name="adminName" id="adminName"></br>

        <label for="adminpass">password admin</label>
        <input type="password" name="adminpass" id="adminpass"></br>

        <input type="submit" value="add account"></br>
    </form>

    <form method="post">
        <input type="hidden" name="remove" value="remove"/>
        <input type="hidden" name="add" value="remove"/>

        <label for="removeName">ID Admin</label>
        <input type="text" name="removeID" id="removeID"></br>

        <input type="submit" value="remove account"></br>
    </form>


    <table border="1">
');

$connection = ReturnData("SELECT * FROM `admin`;");
foreach($connection as $rows){
    echo("<tr>");
        foreach($rows as $item){
        echo("<th>".$item."</th>");
        }
    echo("</tr>");
}
echo('
    </tabel>
   </body>
</html>
');
                