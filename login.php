<!DOCTYPE html>

<!-- 
//************************************
//Ricky Dupuits: Frond end.
//Bram Hendriks: Back End.
//
//
//
//
//Version: v0.1
//*************************************
-->
<?php
    include("./php/includes/security.php");
    include("./php/includes/database.php");

if(!empty($_POST)){
    $connection = ReturnData("INSERT INTO `users` (`User_ID`, `naam`, `password`, `gamer_tag`) VALUES (NULL, '".$_POST["Username"]."', '".HashPassword($_POST["Password"])."', '".$_POST["GamerTag"]."');");
    $_POST = null;
}
    
echo('
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>VISTA LANparty</title>
            <link rel="stylesheet" type="text/css" href="css/style.css"/>
            <link
            rel="stylesheet"
            href="https://unpkg.com/swiper/swiper-bundle.min.css"
            />
            
        </head>
        <body>	
                <img class="wallpaper" src="images/LAN-Event.jpg">
                <!--<img class="sign" src="images/sign.png">-->
            <nav class="stickynav">
                <ul class="menu">
                    <li><a href="./index.html">Home</a></li>
                    <li><a href="#">Events</a></li>
                    <li><a href="#which_games">Which Games</a></li>
                    <li><a href="https://discord.gg/q3hEvcKSf9">Discord</a></li>
                    <li><a href="./login.php">inschrijven</a></li>
                </ul>
            </nav>

            <form method="post">
                <label for="Username">Username: </label>
                <input type="text" id="Username" name="Username"> </br>

                <label for="Password">Password: </label>
                <input type="text" id="Password" name="Password"> </br>

                <label for="GamerTag">Gamer Tag: </label>
                <input type="text" id="GamerTag" name="GamerTag"> </br>

                <input type="submit" value="Regristreer"> </br>
            </form>

        </body>
    </html>
');