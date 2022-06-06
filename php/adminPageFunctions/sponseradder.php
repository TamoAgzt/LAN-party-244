<?php

include("../includes/security.php");
include("../includes/database.php");

if(!empty($_POST)){
    if($_POST['add'] == "add"){
        $connection = ReturnData("INSERT INTO `sponsers` (`sponser_ID`, `Sponser_Naam`, `Sponser_Adres`, `Sponser_Locatie`, `Sponser_beschrijving`, `Sponser_tier`) VALUES (NULL, '".$_POST["naamBedrijf"]."', '".$_POST["adresBedrijf"]."', '".$_POST["locatieBedrijf"]."', '".$_POST["beschrijvingBedrijf"]."', '".$_POST["TierBedrijf"]."');");
        $_POST = null;
    }else if ($_POST['remove'] == "remove"){
        $connection = ReturnData("DELETE FROM `sponsers` WHERE `sponser_ID` = ".$_POST["idBedrijf"].";");
    }
}
    
echo('
    <html>
        <head>
        </head>
        <body>
            <ul>
                <li><a href="../mainAdminPage.php">adminpage</a></li>
                <li><a href="./inschrijving.php"">inschrijvingen</a></li>
                <li><a href="#">sponseradder</a></li>
                <li><a href="./AdminAccounts.php">admin accounts</a></li>
            </ul>

            <h1>add sponsers</h1>
            <form method="post">
            <input type="hidden" name="add" value="add"/>
            <input type="hidden" name="remove" value="add"/>
            <label for="naamBedrijf">Naam van sponser</label>
            <input type="text" name="naamBedrijf" id="naamBedrijf"></br>

            <label for="adresBedrijf">adres van sponser</label>
            <input type="text" name="adresBedrijf" id="adresBedrijf"></br>

            <label for="locatieBedrijf">locatie van sponser</label>
            <input type="text" name="locatieBedrijf" id="locatieBedrijf"></br>

            <label for="beschrijvingBedrijf">beschrijving van sponser</label>
            <input type="text" name="beschrijvingBedrijf" id="beschrijvingBedrijf"></br>

            <label for="TierBedrijf">tier van sponser</label>
            <input type="text" name="TierBedrijf" id="TierBedrijf"></br>

            <input type="submit" value="add"></br>
            </form>

            <form method="post">
            <input type="hidden" name="remove" value="remove"/>
            <input type="hidden" name="add" value="remove"/>
            <label for="idBedrijf">id van sponser</label>
            <input type="text" name="idBedrijf" id="idBedrijf"></br>
            <input type="submit" value="remove"></br>
            
            </form>
        <table border="1">
');
    $connection = ReturnData("SELECT * FROM `sponsers`;");
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
    