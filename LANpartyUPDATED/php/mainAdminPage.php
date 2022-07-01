<?php
session_start();
if(!$_SESSION["logedin"]){
    header("Location: ../adminLogin.php");

}

echo('
<html>
<head>
    <title>AdminPage</title>
    <link rel="stylesheet" type="text/css" href="../css/admin-additional.css" />
</head>
<body>
    <ul>
        <li><a href="#">adminPage</a></li>
        <li><a href="./adminPageFunctions/Inschrijving.php">inschrijvingen</a></li>
        <li><a href="./adminPageFunctions/sponseradder.php">sponseradder</a></li>
        <li><a href="./adminPageFunctions/AdminAccounts.php">admin accounts</a></li>
  </ul>

  <h1>Welkom to the admin Page</h1>
  <p>Hierboven kunt u navigeren!</p>
</body>
</html>
');