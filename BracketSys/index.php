<?php

$sponsors = array('lidl','subway','mcdonalds','devilskey Studio',
			'it-rivel','w3schools','the turtle','torn gems','moware');

$rand_keys = rand(0,8);


echo('
<html>
	<head>
		<title>BracketSys</title>
		<link rel="stylesheet" href="./teststyle.css">
		<link rel="stylesheet" href="./style.css">
		<script src="./Matches.js"></script>
    <h1><svg style="width:32px;height:32px;" viewBox="0 0 32 32">
    <path fill="currentColor" d="M19 7C17.9 7 17 7.9 17 9V11C17 12.1 17.9 13 19 13H21V15H17V17H21C22.1 17 23 16.1 23 15V13C23 11.9 22.1 11 21 11H19V9H23V7H19M9 7V17H13C14.1 17 15 16.1 15 15V9C15 7.9 14.1 7 13 7H9M11 9H13V15H11V9M3 7C1.9 7 1 7.9 1 9V17H3V13H5V17H7V9C7 7.9 6.1 7 5 7H3M3 9H5V11H3V9Z" />
</svg> Tournament medemogelijk gemaakt door : '.$sponsors[$rand_keys].' </h1>
	</head>
	<body>'
);

// Is een beetje retarted maar werkt wel - Rick 2022

// Includes
include_once("./include/database.php");
include_once("./matchmaking.php");
include_once("./players.php");
include_once("./Brackets.php");

//	Create 16 players
for ($i = 0; $i < 16; $i++) {
	$plr = $PlrCtrl->NewPlayer();
}
$playerlist = $PlrCtrl->GetPlayers();

// Create matches for 16 players
for($i = 0; $i < 16; $i+=2) {
  $MM->NewMatch($playerlist[$i], $playerlist[$i+1]);
}

// Matchmaking
$matches = $MM->matches;
echo('<div class="players">');
foreach($matches as $match){
    echo('<div id="matches'.$match->id.'">');
    echo($match->PlayerA->username. " <svg class='vs' viewBox='0 0 24 24'><path d='M4.5 17.42L5.58 18.5L3.28 20.78C3 21.07 2.5 21.07 2.22 20.78S1.93 20 2.22 19.72L4.5 17.42M18.29 5.42L18.29 4L12 10.29L5.71 4L5.71 5.42L11.29 11L7.5 14.81C6.32 13.97 4.68 14.07 3.63 15.12L7.88 19.37C8.93 18.32 9.03 16.68 8.2 15.5L18.29 5.42M21.78 19.72L19.5 17.42L18.42 18.5L20.72 20.78C21 21.07 21.5 21.07 21.78 20.78S22.07 20 21.78 19.72M16.5 14.81L13.42 11.71L12.71 12.42L15.81 15.5C14.97 16.68 15.07 18.32 16.12 19.37L20.37 15.12C19.32 14.07 17.68 13.97 16.5 14.81Z'/></svg> " .$match->PlayerB->username."</br>");
    echo('</div>');
}

//CreateBrackets($matches);
// Player a = 1 ::: player b = 2
$winenrs = array();
foreach($matches as $match){
	$RandWinner = rand(-1000, 1000)/1000;

	if ($RandWinner > 0) {
		$match->EndMatch($match->PlayerA->username, $playerlist);
	} elseif ($RandWinner < 0) {
		$match->EndMatch($match->PlayerB->username, $playerlist);
	}
	echo("<script>
		AddWinnerMatch(".$match->id.", '".$match->GetWinner()->username."')
		</script>");

	array_push($winenrs, $match->GetWinner());
	//echo($match->GetWinner()->username.'<br/>');
}

echo('
			<input type="button" value="Reset round">
			<input type="button" value="Next round" onclick="NextRound()">
		</div>
	</body>
</html>
');



		//    TODO html Php    \\
// Maak een loop van het knockout systeem  | 
/* En zorg dat het doorgaat tot
	de laatste speler */
// Make milans css html work               | milan


		//    TODO html css js    \\

// Namen switchen en dan aangeven wie      |
// Tegen wie moet                          |
// Zodat het ook echt goed uit ziet        | 
// Speler list change css                  | tomi


  		//    Done TODO task      \\
// Fix ricks zijn meester werk             | *
// Boven tournooinaam                      | *
// Lijst sponser maak die naam van het     | *
// Tournament
// Matches visible                         | *
// Visible maken wie er gewonnen heeft     | *
// While input (1 ) v (2)                  | *
// Zorg dat het er uit ziet als een        | *
// Tournament bracket systeem              | *
