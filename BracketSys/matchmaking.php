<?php

include("./players.php");

class MatchMaker {
	// Properties
	public $matches = array();
	public $matchId = 0;
	
	// Methods
	public function NewMatch($PlayerA, $PlayerB) {
		$NewID = $this->matchId + 1;
		$this->matchId++;
		
		$NewMatch = new Match($NewID, $PlayerA, $PlayerB);
		array_push($this->matches, $NewMatch);
		
		return $NewMatch;
	}
	public function GetMatches() {
		return $this->matches;
	}
}

$MM = new MatchMaker();

class Match {
	// Properties
	public $id;
	public $PlayerA;
	public $PlayerB;
	public $IsFinished = false;
	private $Winner;
	
	// Methods
	public function __construct($id, $PlayerA, $PlayerB) {
		$this->id = $id;
		
		$this->PlayerA = $PlayerA;
		$this->PlayerB = $PlayerB;
  	}
	public function EndMatch($WinnerName, $plrs) {
		$Winner;
		foreach ($plrs as $plr) {
			if ($plr->username == $WinnerName) {
				$Winner = $plr;
				break;
			}
		}
  		if ($Winner != null ){
        	$this->Winner = $Winner;
			$this->IsFinished = true;
      	}
  	}
	public function GetWinner() {
  		if ($this->Winner != null) {
        	return $this->Winner;
      	} else {
			return null;
		}
	}
}
