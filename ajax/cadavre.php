<?php

	require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",1);

	
	$f->query("INSERT INTO cadavre (dateDebut,isEnCours,idUtilisateur)
				VALUES (NOW(),'1','".$_SESSION["id"]."');");
	
	$f->retourAjax["id"] = $f->db->lastId();
	$f->ajaxOK("Jeu ajouté","Le jeu a été créé avec succès, vous allez être redirigé vers le jeu...");
	$f->endAjax();
?>