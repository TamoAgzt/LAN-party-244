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
    <h1><svg style="width:32px;height:32px" viewBox="0 0 32 32">
    <path fill="currentColor" d="M19 7C17.9 7 17 7.9 17 9V11C17 12.1 17.9 13 19 13H21V15H17V17H21C22.1 17 23 16.1 23 15V13C23 11.9 22.1 11 21 11H19V9H23V7H19M9 7V17H13C14.1 17 15 16.1 15 15V9C15 7.9 14.1 7 13 7H9M11 9H13V15H11V9M3 7C1.9 7 1 7.9 1 9V17H3V13H5V17H7V9C7 7.9 6.1 7 5 7H3M3 9H5V11H3V9Z" />
</svg> Tournament medemogelijk gemaakt door : '.$sponsors[$rand_keys].' </h1>
	</head>
	<body>');
    
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
    echo('');
    //echo($match->PlayerA->username." V ".$match->PlayerB->username."</br>");
    //echo('</div>');
}

// Player a = 1 ::: player b = 2
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
	//echo($match->GetWinner()->username.'<br/>');
}

echo('</div>
	</body>
</html>
');