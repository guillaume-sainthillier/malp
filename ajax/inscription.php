<?php

	require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",0,"xml");

	if(isset($_POST["adresse"]) and isset($_POST["cp"]) and isset($_POST["login"]) 
		and isset($_POST["mail"]) and isset($_POST["mdp"]) and isset($_POST["nom"])
		and isset($_POST["prenom"]) and isset ($_POST["tel"]) and isset($_POST["ville"]))
	{
		$adresse = trim($_POST["adresse"]);
		$cp 	 = trim($_POST["cp"]);
		$login	 = trim($_POST["login"]);
		$mail	 = trim($_POST["mail"]);
		$mdp	 = trim($_POST["mdp"]);
		$nom	 = trim($_POST["nom"]);
		$prenom	 = trim($_POST["prenom"]);
		$tel	 = trim($_POST["tel"]);
		$ville	 = trim($_POST["ville"]);
		
		//Verifs format champs
		if($login == "")
			$f->ajaxErreur("","Le nom de compte ne doit pas être vide",false);
		if($mdp == "")
			$f->ajaxErreur("","Le mot de passe ne doit pas être vide",false);
		if($nom == "")
			$f->ajaxErreur("","Le nom ne doit pas être vide",false);
		if($prenom == "")
			$f->ajaxErreur("","Le prénom ne doit pas être vide",false);
			
		if(!preg_match(REGEX_USER, $login))
			$f->ajaxErreur("","Le nom de compte <b>".s($login)."</b> n'est pas valide",false);
			
		if(!preg_match(REGEX_PWD, $mdp))
			$f->ajaxErreur("","Le mot de passe n'est pas valide",false);
			
		if(!preg_match(REGEX_MAIL, $mail))
			$f->ajaxErreur("","Le mail <b>".s($mail)."</b> n'est pas valide",false);
		
		if($tel != "")
		{
			$tel = preg_replace("/[^0-9\+]/","",$tel);
			if(!preg_match(REGEX_TEL, $tel))
			$f->ajaxErreur("","Le numéro de téléphone n'est pas valide",false);
		}
		
		
		//Vérif disponibilité login
		$res = $f->query("SELECT * FROM utilisateur WHERE lower(login) = '".e(strtolower($login))."' ;");
		if($res->numRows() > 0)
		{
			$f->ajaxErreur("","Le nom de compte <b>".s($login)."</b> est déjà utilisé",false);
		}
		
		
		//Arrivé là, tout est OK
		$f->query("INSERT INTO utilisateur(nom,prenom,mail,login,mdp,codeP,adresse,ville,tel,rang)
					VALUES('".e($nom)."','".e($prenom)."','".e($mail)."','".e($login)."','".e($mdp)."','".e($cp)."',
							'".e($adresse)."','".e($ville)."','".e($tel)."','1');");

		$_SESSION['id'] = $f->db->lastId();		
		$_SESSION['login'] = s($login);
		$_SESSION['admin'] = 1;
		$f->ajaxOK("","Votre inscription s'est déroulée avec succès, vous allez être redirigé sur votre compte",false);
	}
	
	$f->endAjax();
?>