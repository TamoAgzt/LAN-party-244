<?php

class PlayerController {
	// Properties
	private $players;
	public $plrs = array();
	
	// Methods
	public function NewPlayer() {
		$NewID = $this->players + 1;
		$this->players++;
		
		$NewPlr = new Player($NewID);
		array_push($this->plrs, $NewPlr);
		
		return $NewPlr;
	}
	public function GetPlayers() {
		return $this->plrs;
	}
}

class Player {
	// Properties
	private $id;
	public $username;

	// Methods
	public function __construct($id) {
    	$this->id = $id;
		$this->username = 'player'.$id;
  	}
}

$PlrCtrl = new PlayerController();
