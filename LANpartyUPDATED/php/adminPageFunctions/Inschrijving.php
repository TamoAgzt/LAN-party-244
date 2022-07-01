<?php
session_start();
if(!$_SESSION["logedin"]){
    header("Location: ../../adminLogin.php");
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
                <li><a href="#">inschrijvingen</a></li>
                <li><a href="./sponseradder.php">sponseradder</a></li>
                <li><a href="./AdminAccounts.php">admin accounts</a></li>
            </ul>
        </body>
    </html>
    ');