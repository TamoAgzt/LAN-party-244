<?php

include("../includes/security.php");
include("../includes/database.php");
include("../includes/uploadImage.php");

session_start();
if(!$_SESSION["logedin"]){
    header("Location: ../../adminLogin.php");
}

if(!empty($_POST)){
    if($_POST['add'] == "add"){
        $foto = ImageChecker();
        if($foto != ""){
            $connection = ReturnData("INSERT INTO `sponsers` (`sponser_ID`, `Sponser_Naam`, `Sponser_Adres`, `Sponser_Locatie`, `Sponser_beschrijving`, `Sponser_tier`, `SponserFoto`) VALUES (NULL, '".$_POST["naamBedrijf"]."', '".$_POST["adresBedrijf"]."', '".$_POST["locatieBedrijf"]."', '".$_POST["beschrijvingBedrijf"]."', '".$_POST["TierBedrijf"]."', '".$foto."');");
            $_POST = null;
        }else{
        echo("failed");
        }
    }else if ($_POST['remove'] == "remove"){
        $connection = ReturnData("DELETE FROM `sponsers` WHERE `sponser_ID` = ".$_POST["idBedrijf"].";");
    }
}
    
echo('
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../css/admin-additional.css" />
</head>
<body>
    <ul>
        <li><a href="../mainAdminPage.php">adminpage</a></li>
        <li><a href="./Inschrijving.php"">inschrijvingen</a></li>
        <li><a href="#">sponseradder</a></li>
        <li><a href="./AdminAccounts.php">admin accounts</a></li>
    </ul>

      <h1>add sponsers</h1>
      <form method="post" enctype="multipart/form-data" class="add">
      <input type="hidden" name="add" value="add"/>
      <input type="hidden" name="remove" value="add"/>
      <label for="naamBedrijf">Naam van sponser</label>
      <input type="text" name="naamBedrijf" id="naamBedrijf">
</br>
      <label for="adresBedrijf">adres van sponser</label>
      <input type="text" name="adresBedrijf" id="adresBedrijf"></br>

      <label for="locatieBedrijf">locatie van sponser</label>
      <input type="text" name="locatieBedrijf" id="locatieBedrijf"></br>

      <label for="beschrijvingBedrijf">beschrijving van sponser</label>
      <input type="text" name="beschrijvingBedrijf" id="beschrijvingBedrijf"></br>

      <label for="TierBedrijf">tier van sponser</label>
      <input type="text" name="TierBedrijf" id="TierBedrijf"></br>

      <input type="file" name="file"> </br>

      <input type="submit" value="add"></br>
    </form>
<div class="vertispacer"></div>
    <h1>Remove sponsors</h1>
    <form method="post" class="remove">
      <input type="hidden" name="remove" value="remove"/>
      <input type="hidden" name="add" value="remove"/>
      <label for="idBedrijf">id van sponser</label>
      <input type="text" name="idBedrijf" id="idBedrijf">
</br>
      <input type="submit" value="remove"></br>
    </form>
<table border="1">
');
    $connection = ReturnData("SELECT * FROM `sponsers`;");
    foreach($connection as $rows){
        echo("<tr>");
        $counter = 0;
        foreach($rows as $item){
            if($counter < 6){
                echo("<th>".$item."</th>");
                $counter++;
            }else {
                echo('<th> <img src="data:image/png;base64,'.base64_encode($item).'"/> </th>');
            }
        }
        echo("</tr>");
    }
echo('
    </tabel>
    </body>
    </html>

');
    