<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
require("db.php");

        if (ISSET($_POST['submit']))
        {
        $username = $_SESSION['username'];
        $select = mysqli_query($con, "SELECT * FROM inschrijvingen WHERE username = '".$username."'");
        if(mysqli_num_rows($select)) {
            echo "<h3>U heeft al ingeschreven</h3>";
        }
        else{
                    
            ISSET($_POST['games']);
            // Check connection

            if($con === false){
                die("ERROR: Could not connect. "
                    . mysqli_connect_error());
            }

            $email = $_REQUEST['email'];
            $username = $_SESSION['username']; 
            $games = $_REQUEST['games'];

            $sql = "INSERT INTO inschrijvingen  VALUES ('$email', '$username', '$games')";
         
            if(mysqli_query($con, $sql)){
                echo "<h3>Submit succesful</h3>";

            } else{
                echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($con);
            }
         
            // Close connection
            mysqli_close($con);
        }
    }
                        
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="stylelogin.css" />
</head>
<body>
    <div class="form">
        <p>Welkom bij het inschrijfformulier <?php echo $_SESSION['username']; ?>!</p>
        <p>Hieronder kunt u zich inschrijven.</p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>

<form class="form" method="post" name="gameselector">
        <label for="Games">Choose a game</label>
        <p>Username: <?php echo $_SESSION['username']; ?></p>
            <p>
               <label for="email">Email:</label>
               <input type="text" name="email" id="email" required>
            </p>
                <label for="games">Selecteer een game:</label>
                <select name="games" id="games" required>
                <option value="rocket league">rocket-league</option>
                <option value="fortnite">fortnite</option>
                <option value="apex legends">apex-legends</option>
                <option value="minecraft">minecraft</option>
        <input type="submit" name="submit" value="submit" class="">
</form>



