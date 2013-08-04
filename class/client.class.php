<?php


class Client {


	public $clientID;
	public $idClient;
	public $nom;
	public $inGame;
	
	public function __construct($clientID, $idClient,$nom, $inGame = true)
	{
		$this->clientID 	= $clientID;
		$this->idClient 	= $idClient;
		$this->nom 			= $nom;
		$this->inGame		= $inGame;
		
		return $this;
	}
	
	
	public function toJSON($tabRetour = array())
	{
		$tabRetour["id"] 	 = $this->clientID;
		$tabRetour["nom"] 	 = $this->nom;
		$tabRetour["inGame"] = $this->inGame;
		return json_encode($tabRetour);
	}
}

?>