<?php

require __DIR__ .'/../class/PHPWebSocket.class.php';
require __DIR__ .'/../class/client.class.php';
require __DIR__ .'/../dyn/fonctions.inc.php';

class CadavreWebSocket extends PHPWebSocket
{
	protected $db;
	protected $idJeu;
	protected $listeJoueurs;
	protected $fileAttente;
	protected $listeSpec;
	protected $createur;
	protected $jec;
	protected $joueurs;
	protected $isChatLoaded;
	protected $lastPhrase;
	
	public function __construct(&$db)
	{
		$this->db 			= $db; 
		$this->idJeu 		= null;
		$this->listeJoueurs = array();
		$this->listeSpec	= array();
		$this->fileAttente	= array();
		$this->joueurs 		= array();
		$this->createur		= null;
		$this->jec 			= null;
		$this->lastPhrase	= null;
		$this->isChatLoaded = false;
	}
	
	/**
	
	
		Fonctions appelées par le client
	
	
	**/	
	
	//Met fin au jeu
	public function close_game($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		
		try 
		{
			$res = $this->db->query("SELECT phrase, u.login as login
								FROM phrase, utilisateur u
								WHERE u.idUtilisateur = phrase.idUtilisateur
								AND phrase.idCadavre = '".e($this->idJeu)."'
								ORDER BY idPhrase ASC;");
			if(!$res)
				return;
			$retour = array("details" => array());
		
			$phraseFinale = "";
			while($row = $res->fetch())
			{
				$retour["details"][] = array("nom" => $row["login"],
												"phrase" => $row["phrase"]);
				$phraseFinale .= " ".$row["phrase"];
			}
			
			$retour["phraseFinale"] = trim($phraseFinale);			

			$this->db->query("UPDATE cadavre SET isEnCours = '0', phraseFinale = '".e($phraseFinale)."', dateFin = NOW()
				WHERE idCadavre = '".e($this->idJeu)."' ;");			
		}catch(Exception $e)
		{
			$this->send($clientID,"error","Erreur SQL");
			return;
		}
						
		
		$this->sendAll("close_game",json_encode($retour));
		
		foreach($this->joueurs as $id => $client)
			$this->close($id);
			
		$this->idJeu 		= null;
		$this->listeJoueurs = array();
		$this->listeSpec	= array();
		$this->fileAttente	= array();
		$this->joueurs 		= array();
		$this->createur		= null;
		$this->jec 			= null;
		$this->lastPhrase	= null;
		$this->isChatLoaded = false;
	}
	
	//Reception d'une phrase
	public function phrase($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
			
		if($this->fileAttente[$this->jec] == $this->joueurs[$clientID])
		{
			if(isset($data->msg))
			{
				$phrase = trim($data->msg);
				if($phrase != "")
				{
					$old 				= $this->joueurs[$clientID];
					$this->lastPhrase	= $old->toJSON(array("phrase" => $phrase));
					
					try
					{
						$this->db->query("INSERT INTO phrase(phrase,idCadavre,idUtilisateur)
										VALUES('".e($phrase)."','".e($this->idJeu)."','".e($old->idClient)."');");
					}catch(Exception $e)
					{
						$this->send($clientID,"error","Erreur SQL");
						return;
					}
					$this->passerTour($clientID,false);
				}
			}
		}
	}
	
	//Met fin au salon de chat
	public function close_chat($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		if($this->isChatLoaded and $this->createur == $this->joueurs[$clientID])
		{
			$this->sendAll("stop_chat",$this->joueurs[$clientID]->toJSON()); //On notifie les autres de sa connection
			$this->isChatLoaded = false;
		}
	}
	
	//Démarre le salon de chat
	public function load_chat($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		if(!$this->isChatLoaded and $this->createur == $this->joueurs[$clientID])
		{
			$this->sendAll("load_chat",$this->joueurs[$clientID]->toJSON()); //On notifie les autres de sa connection
			$this->isChatLoaded = true;
		}
	}
	
	//Envoi d'un message dans le chat
	public function chat($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		if($this->isChatLoaded and isset($data->msg))
		{
			$this->sendAll("chat",json_encode(date("d/m/Y H\hi")." <b>".$this->joueurs[$clientID]->nom."</b>: ".$data->msg)); //On notifie les autres de sa connection
		}
	}
	
	//Demande de connexion d'un user sur un jeu donné
	public function join($clientID,$jeu)
	{
		if(isset($this->joueurs[$clientID]))
			return;
			
		if(isset($jeu->idJeu) and isset($jeu->idUser))
		{
			$idJeu	 = base64_decode($jeu->idJeu);
			$idUser	 = base64_decode($jeu->idUser);
			
			if($this->idJeu == null) // Si tout premier join on initialise le jeu
			{
				try
				{
					$res = $this->db->query("SELECT isEnCours FROM cadavre WHERE idCadavre = '".e($idJeu)."' ;");
					if(!$res)
						return;
					$row = $res->fetch();
					if(!$row or ($row and !$row["isEnCours"]))
					{
						$this->send($clientID,"error",($row ? "Le jeu demandé n'existe pas": "La partie est terminée"));
						return;
					}						
					$this->idJeu 	= $idJeu;	
				}catch(Exception $e)
				{
					$this->send($clientID,"error","Erreur SQL");
					return;
				}
				
			}
			$this->joinMembre($clientID, $idUser);
		}
	}
	
	//Passe le tour
	public function passer($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		$this->passerTour($clientID, false);
	}
	
	//Déplace un joueur en spectateur
	public function move_to_spec($clientID,$data)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		if($this->joueurs[$clientID]->inGame)
		{
			$this->joueurs[$clientID]->inGame = false;
			$this->sendAll("move_to_spec",$this->joueurs[$clientID]->toJSON());
			if($this->jec >= 0 and $this->fileAttente[$this->jec] == $this->joueurs[$clientID])//On notifie les gens si le passage affecte le tour
			{						
				$this->passerTour($clientID,true);
			}else
				$this->leaveFile($clientID);
		}		
	}
	
	//Replace un joueur dans la partie
	public function move_to_player($clientID,$data)
	{		
		if(! isset($this->joueurs[$clientID]))
			return;
			
		if(!$this->joueurs[$clientID]->inGame)
		{
			$this->joueurs[$clientID]->inGame = true;
			$this->sendAll("move_to_player",$this->joueurs[$clientID]->toJSON());

			$this->fileAttente[] = $this->joueurs[$clientID];
			if(count($this->fileAttente) == 1) //Si aucun joueur
			{
				$this->majFile();
			}
		}			
	}
	
	//Déconnecte le joueur du jeu
	public function deconnecter($clientID)
	{
		if(! isset($this->joueurs[$clientID]))
			return;
		$client = $this->joueurs[$clientID];
		
		$this->sendAll("leave",$client->toJSON());
		
		$wasJec = ($this->jec >= 0 and $this->fileAttente[$this->jec] == $client); // S'il était en train de jouer 				
		$this->leaveFile($clientID);	

		if($this->createur == $client)		
			$this->close_chat($clientID,false);	
			
		unset($this->joueurs[$clientID]); //A laisser avant le majFile
		if($wasJec)
			$this->majFile();
					
		if($this->createur == $client)
		{	
			$this->close_chat($clientID,false);
			$this->createur = null;
			foreach($this->joueurs as $id => $client2)
			{
				$this->verifAdminIfNeeded($id);
				continue;
			}
		}
	}
	
	
	/**
	
	
		Fonctions appelées par le daemon
	
	
	**/
	
	//Envoi un payload à un client donné
	public function send($clientID, $action, $data = false) 
	{
		if(is_bool($data)) //$data = $isBinary
		{
			parent::send($clientID,$action, false);
		}else
		{
			parent::send($clientID,json_encode(array("action"=>$action,"data"=> ($action == "error" ? BMErreur($data) : $data))), false);
		}
	}
	
	//Notifie tous les clients (sauf predicatClientID si passé en paramètre)
	public function sendAll($action, $data, $predicatClientID = -1)
	{
		foreach( $this->clients as $id=> $client)
			if( $id != $predicatClientID)
				$this->send($id, $action,$data);
	}


	private function getListeMembres()
	{
		$listeMembres = array();
		foreach ( $this->joueurs as $id => $client )
			$listeMembres[] = $client->toJSON();
			
		return json_encode($listeMembres);
	}
	
	private function joinMembre($clientID, $idUser)
	{
		try 
		{
			$res = $this->db->query("SELECT login FROM utilisateur WHERE idUtilisateur = '".e($idUser)."' ;");
			if(!$res)
				return;
			if(!$row = $res->fetch())
			{
				$this->send($clientID,"error","L'utilisateur est introuvable");
				return;
			}
			
			
			$this->joueurs[$clientID]	= new Client($clientID, $idUser, $row["login"]);
			
			$listeJoueurs = $this->getListeMembres();
			
			$this->send($clientID,"get_my_id",$clientID); //On envoie la liste des joueurs au nouveau connecté
			$this->send($clientID,"list",$listeJoueurs); //On envoie la liste des joueurs au nouveau connecté
			$this->sendAll("join",$this->joueurs[$clientID]->toJSON(),$clientID); //On notifie les autres de sa connection		
			
			$this->verifAdminIfNeeded($clientID);
			
			if($this->isChatLoaded and $this->createur != null)
				$this->send($clientID,"load_chat",$this->createur->toJSON());
			
			$this->fileAttente[] = $this->joueurs[$clientID];
			if(count($this->fileAttente) == 1)
			{
				$this->majFile();
			}elseif($this->jec >= 0)
				$this->send($clientID,"jec",$this->fileAttente[$this->jec]->toJSON());
		}catch(Exception $e)
		{
			var_dump($e);
			$this->send($clientID,"error","Erreur SQL");
			return;
		}
	}

	
	private function verifAdminIfNeeded($clientID)
	{
		if($this->createur == null) // S'il n'y a pas/plus d'admin, on réaffecte et notifie
		{
			$this->createur = $this->joueurs[$clientID];
			$this->send($clientID,"admin",$this->joueurs[$clientID]->toJSON());
		}
	}
	

	
	private function passerTour($clientID, $withLeave = true)
	{
		if($withLeave)
			$this->leaveFile($clientID);
		$this->majFile();		
	}
	
	private function majFile()
	{
		if(count($this->fileAttente) == 0)
		{
			$this->sendAll("nobody","");
			$this->jec = -1;
		}else
		{
			$this->jec ++;
			if($this->jec < 0 or $this->jec >= count($this->fileAttente))
				$this->jec = 0;
			
			$this->sendAll("jec",$this->fileAttente[$this->jec]->toJSON());
			
			if($this->lastPhrase != null)
			{
				$this->send($this->fileAttente[$this->jec]->clientID,"last_phrase",$this->lastPhrase);
			}
		}
	}
	
	private function leaveFile($clientID)
	{
		for($i = 0;$i < count($this->fileAttente);$i++)
		{
			if($this->fileAttente[$i] == $this->joueurs[$clientID])
			{
				unset($this->fileAttente[$i]);
				$this->fileAttente = array_values($this->fileAttente); //Ré-indexation
				continue;
			}
		}
	}
}
?>