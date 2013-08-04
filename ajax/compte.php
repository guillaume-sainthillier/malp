<?php

	require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",0,"xml");

	if(isset($_POST["adresse"]) and isset($_POST["cp"])
		and isset($_POST["mail"]) and isset($_POST["mdp"]) and isset($_POST["nom"])
		and isset($_POST["prenom"]) and isset ($_POST["tel"]) and isset($_POST["ville"]))
	{
		$adresse = trim($_POST["adresse"]);
		$cp 	 = trim($_POST["cp"]);
		$mail	 = trim($_POST["mail"]);
		$mdp	 = trim($_POST["mdp"]);
		$nom	 = trim($_POST["nom"]);
		$prenom	 = trim($_POST["prenom"]);
		$tel	 = trim($_POST["tel"]);
		$ville	 = trim($_POST["ville"]);
		$idUser  = $_SESSION["id"];
		
		if(isset($_POST["idUser"]) and $_SESSION["admin"] < 3)
			$f->ajaxErreur("","Vous devez être modérateur pour effectuer cette action");
		elseif(isset($_POST["idUser"]))
			$idUser = e($_POST["idUser"]);
		//Verifs format champs
		if($mdp == "")
			$f->ajaxErreur("","Le mot de passe ne doit pas être vide",false);
		if($nom == "")
			$f->ajaxErreur("","Le nom ne doit pas être vide",false);
		if($prenom == "")
			$f->ajaxErreur("","Le prénom ne doit pas être vide",false);
			
		if(!preg_match(REGEX_PWD, $mdp))
			$f->ajaxErreur("","Le mot de passe n'est pas valide",false);
			
		if(!preg_match(REGEX_MAIL, $mail))
			$f->ajaxErreur("","Le mail <b>".s($mail)."</b> n'est pas valide",false);
		
		if($tel != "")
		{
			$tel = preg_replace("/[\D+]/","",$tel);
			if(!preg_match(REGEX_TEL, $tel))
			$f->ajaxErreur("","Le numéro de téléphone n'est pas valide",false);
		}
		
		
		//Arrivé là, tout est OK
		$f->query("UPDATE utilisateur SET nom='".e($nom)."',prenom='".e($prenom)."',mail='".e($mail)."',mdp='".e($mdp)."',codeP='".e($cp)."',adresse='".e($adresse)."',ville='".e($ville)."',tel='".e($tel)."' WHERE idUtilisateur='".$idUser."';");
		$f->ajaxOK("","La modification du compte s'est déroulée avec succès",false);
	}
	
	$f->endAjax();
?>