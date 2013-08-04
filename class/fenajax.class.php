<?php

require_once "../class/fen.class.php";

class fenAjax extends fen
{
	public $retourAjax; // array utilisé pour la communication ajax
	public $typeAjax;
	/**
		fenAjax : Initialise un objet fenêtre de communication Ajax
			- titre		: Titre de la page
			- rangAdmin	: rang d'administration minimum pour accéder à cette page
	**/
	public function __construct($titre, $rangAdmin = 0, $typeAjax = "json")
	{
		$this->typeAjax = $typeAjax;
		parent::__construct($titre,$rangAdmin);
		
		
		$this->retourAjax = array("erreur" => true,
									"msg" => BMErreur("Mauvais emplois de la page",false),
									"header" => $this->titre
									);
		if($typeAjax == "xml")
			header("Content-type:text/xml");
	}
	
	public function displayHeader()		{}
	public function displayTopMenu()	{}
	public function displayMenuGauche()	{}
	public function displayFooter()		{}
	
		
	/**
		endAjax : Termine la communication Ajax ( ne rien mettre après cette fonction)
	**/
	public function endAjax()
	{
		$erreurs = 	ob_get_contents();		
		if($erreurs != "")
		{
			$this->ajaxErreur("Une erreur inconnue est survenue",$erreurs);
		}
		
		if($this->typeAjax == "json")
			die(json_encode($this->retourAjax));
		else
			die($this->parseXML());
	}
	
	public function parseXML()
	{
		 echo "<?xml version=\"1.0\" encoding=\"".$this->charset."\"?>
				<news>";
				$this->echoTableau($this->retourAjax);
				echo "</news>";
	}
	
	public function echoTableau($tableau)
	{
		foreach($tableau as $cle => $val)
		{
			echo "<$cle>";
			if(is_array($val))
				$this->echoTableau($val);
			else
				echo "<![CDATA[".($val)."]]>";
			echo "</$cle>";
		}
	}
	
	/**
		ajaxErreur : Termine la communication Ajax en renvoyant une erreur ( ne rien mettre après cette fonction)
						- entete 	: Titre de l'erreur
						- msg 		: Détail de l'erreur
						- fullWidth : Vrai par défaut, n'affiche pas le message d'erreur sur toute sa longueur si faux
	**/
	public function ajaxErreur($entete,$msg, $fullWidth = true)
	{
		ob_end_clean();
		$this->retourAjax["erreur"] = true;
		$this->retourAjax["msg"] = BMErreur($msg,$fullWidth);
		$this->retourAjax["header"] = $entete;
		
		if($this->typeAjax == "json")
			die(json_encode($this->retourAjax));
		else
			die($this->parseXML());
	}
	
	/**
		ajaxOK : Termine la communication Ajax en renvoyant une confirmation de bon fonctionnement
						- entete 	: Titre de l'information
						- msg 		: Détail de l'information
						- fullWidth : Vrai par défaut, n'affiche pas le message d'erreur sur toute sa longueur si faux
	**/
	public function ajaxOK($entete,$msg, $fullWidth = true)
	{
		$this->retourAjax["erreur"] = false;
		$this->retourAjax["msg"] = BMOK($msg,$fullWidth);
		$this->retourAjax["header"] = $entete;
	}

	/**
		_die : Arrête tout traitement et affiche la page avec un message d'erreur et une entête
				- entête	: Titre de l'erreur
				- msg		: Détail de l'erreur
	**/
	public function _die($entete, $msg)
	{
		$html = ob_get_contents();
		$this->ajaxErreur($entete,$html.$msg);
	}

}