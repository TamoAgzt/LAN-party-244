<?php
session_start();
if(!$_SESSION["logedin"]){
    header("Location: ../adminLogin.php");

}

echo('
    <html>
        <head>
            <title>AdminPage</title>
            <link rel="stylesheet" href="../styleAdmin.css">
        </head>
        <body>
            <ul>
                <li><a href="#"><b>adminPage</b></a></li>
                <li><a href="./adminPageFunctions/sponseradder.php"><b>sponseradder</b></a></li>
                <li><a href="./adminPageFunctions/AdminAccounts.php"><b>admin accounts</b></a></li>
            </ul>
            <div class="container">
            <h1>Welkom to the admin Page</h1>
            </div>
        </body>
    </html>
    ');